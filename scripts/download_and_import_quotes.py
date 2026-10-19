#!/usr/bin/env python3
"""
Scrapes all quotation documents from ndz.gov.za for available financial years,
downloads the PDF files to storage/app/public/uploads/quotes/,
and generates a JSON manifest for import into the database.
"""

import os
import re
import sys
import json
import time
import urllib.parse
from concurrent.futures import ThreadPoolExecutor, as_completed
import requests

BASE_DIR = "/var/www/Laravel_Projects/ndz.gov.za"
OUTPUT_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/quotes")
MANIFEST_PATH = os.path.join(BASE_DIR, "storage/app/quotes_manifest.json")

YEARS = [
    '2026-2027',
    '2025-2026',
    '2024-2025',
    '2023-2024',
    '2022-2023',
    '2021-2022',
    '2020-2021',
    '2018-2019',
    '2017-2018',
    '2016-2017'
]

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def format_size(num_bytes):
    if num_bytes < 1024:
        return f"{num_bytes} B"
    elif num_bytes < 1024 * 1024:
        return f"{round(num_bytes / 1024, 1)} KB"
    else:
        return f"{round(num_bytes / (1024 * 1024), 2)} MB"

def clean_filename(filename):
    clean = re.sub(r'[^a-zA-Z0-9_\.-]', '_', filename)
    clean = re.sub(r'_+', '_', clean)
    return clean

def scrape_quote_page(session, year):
    candidates = [
        f"https://ndz.gov.za/open-quotations-{year}/",
        f"https://ndz.gov.za/open-quotes-{year}/",
        f"https://ndz.gov.za/quotes-{year}/",
    ]
    
    html = None
    matched_url = None
    for url in candidates:
        try:
            resp = session.get(url, headers=HEADERS, timeout=20, verify=False)
            if resp.status_code == 200 and 'attachment-link' in resp.text:
                html = resp.text
                matched_url = url
                break
        except Exception:
            pass
            
    if not html:
        print(f"[!] No active quote page found for {year}")
        return []

    print(f"[*] Found quote page for {year}: {matched_url}")
    rows = re.findall(r'<tr class=\"pdf\">.*?</tr>', html, re.DOTALL | re.IGNORECASE)
    items = []
    for i, row in enumerate(rows, start=1):
        m_idx = re.search(r'class=\"attachment-index\">(\d+)<', row)
        m_link = re.search(r'<a href=\"([^\"]+)\"[^>]*title=\"([^\"]*)\"[^>]*>(.*?)</a>', row)
        m_dl = re.search(r'class=\"attachment-downloads\">(\d+)<', row)
        
        idx = int(m_idx.group(1)) if m_idx else i
        if not m_link:
            continue
        href = m_link.group(1).strip()
        title = (m_link.group(2) or m_link.group(3)).strip()
        dl_count = int(m_dl.group(1)) if m_dl else 0
        
        ref_match = re.search(r'\b([A-Z0-9\-_]+/[0-9\-_/]+)\b', title)
        ref_no = ref_match.group(1) if ref_match else f"NDZ-RFQ-{year}-{idx:03d}"

        items.append({
            'year': year,
            'index': idx,
            'title': title,
            'href': href,
            'download_count': dl_count,
            'reference_no': ref_no
        })
    print(f"[+] Found {len(items)} quote items for {year}")
    return items

def download_quote_file(item):
    session = requests.Session()
    session.headers.update(HEADERS)
    href = item['href']
    year = item['year']
    idx = item['index']
    
    try:
        resp = session.get(href, headers=HEADERS, timeout=35, allow_redirects=True, verify=False, stream=True)
        if resp.status_code != 200:
            print(f"[-] Failed HTTP {resp.status_code} for {item['title'][:40]}")
            return None
        
        final_url = resp.url
        parsed_path = urllib.parse.urlparse(final_url).path
        base_name = os.path.basename(parsed_path)
        base_name = urllib.parse.unquote(base_name)
        if not base_name or '.' not in base_name:
            base_name = f"quote_{idx}.pdf"
        
        safe_name = clean_filename(base_name)
        target_filename = f"{year}_{idx:03d}_{safe_name}"
        if not target_filename.lower().endswith(('.pdf', '.doc', '.docx', '.xls', '.xlsx', '.zip')):
            target_filename += '.pdf'
        
        file_path = os.path.join(OUTPUT_DIR, target_filename)
        
        if os.path.exists(file_path) and os.path.getsize(file_path) > 0:
            file_size = os.path.getsize(file_path)
        else:
            with open(file_path, 'wb') as f:
                for chunk in resp.iter_content(chunk_size=16384):
                    if chunk:
                        f.write(chunk)
            file_size = os.path.getsize(file_path)
            print(f"[↓] Downloaded quote {target_filename} ({format_size(file_size)})")

        return {
            'financial_year': year,
            'category': 'Quotations',
            'type': 'quote',
            'status': 'open' if year in ['2026-2027', '2025-2026'] else 'closed',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"Quotation documentation for {year} - {item['title']}",
            'document_url': f"/storage/uploads/quotes/{target_filename}",
            'download_count': item['download_count'],
            'file_name': target_filename,
            'file_size': format_size(file_size),
        }
    except Exception as e:
        print(f"[!] Error downloading quote {href} ({item['title'][:30]}): {e}")
        return None

def main():
    import urllib3
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
    
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    os.makedirs(os.path.dirname(MANIFEST_PATH), exist_ok=True)
    
    all_items = []
    session = requests.Session()
    
    target_years = sys.argv[1].split(',') if len(sys.argv) > 1 and sys.argv[1] else YEARS
    
    print(f"=== Starting Quotations Crawl for {len(target_years)} years ===")
    for year in target_years:
        items = scrape_quote_page(session, year)
        all_items.extend(items)
        time.sleep(0.3)
        
    print(f"=== Total quotations found across all years: {len(all_items)} ===")
    print(f"=== Downloading quotation files to {OUTPUT_DIR} with 8 workers ===")
    
    manifest_records = []
    with ThreadPoolExecutor(max_workers=8) as executor:
        futures = {executor.submit(download_quote_file, item): item for item in all_items}
        for future in as_completed(futures):
            res = future.result()
            if res:
                manifest_records.append(res)
    
    print(f"=== Successfully processed {len(manifest_records)} / {len(all_items)} quotation documents ===")
    
    with open(MANIFEST_PATH, 'w') as f:
        json.dump(manifest_records, f, indent=2)
    print(f"=== Manifest saved to {MANIFEST_PATH} ===")

if __name__ == '__main__':
    main()
