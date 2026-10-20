#!/usr/bin/env python3
"""
Scrapes all tender documents from ndz.gov.za for available financial years,
downloads the PDF files to storage/app/public/uploads/tenders/,
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

def get_config():
    base = os.getenv("LARAVEL_BASE_PATH", os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    target_years = YEARS
    args = sys.argv[1:]
    i = 0
    while i < len(args):
        if args[i] == '--base-dir' and i + 1 < len(args):
            base = args[i + 1]
            i += 2
        elif not args[i].startswith('--'):
            target_years = args[i].split(',')
            i += 1
        else:
            i += 1
    return os.path.abspath(base), target_years

BASE_DIR, _ = get_config()
OUTPUT_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/tenders")
MANIFEST_PATH = os.path.join(BASE_DIR, "storage/app/tenders_manifest.json")

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

def scrape_year_page(session, year):
    url = f"https://ndz.gov.za/open-tenders-{year}/"
    print(f"[*] Scraping listing page for {year}: {url}")
    try:
        resp = session.get(url, headers=HEADERS, timeout=25, verify=False)
        if resp.status_code != 200:
            print(f"[!] Warning: HTTP {resp.status_code} for {year}")
            return []
        
        html = resp.text
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
            
            # Extract ref if visible in title, else create one
            ref_match = re.search(r'\b([A-Z0-9\-_]+/[0-9\-_/]+)\b', title)
            ref_no = ref_match.group(1) if ref_match else f"NDZ-TDR-{year}-{idx:03d}"

            items.append({
                'year': year,
                'index': idx,
                'title': title,
                'href': href,
                'download_count': dl_count,
                'reference_no': ref_no
            })
        print(f"[+] Found {len(items)} items for {year}")
        return items
    except Exception as e:
        print(f"[!] Error scraping {year}: {e}")
        return []

def download_file(item):
    session = requests.Session()
    session.headers.update(HEADERS)
    href = item['href']
    year = item['year']
    idx = item['index']
    
    # Target filename prefix
    # First try head request or get to see redirect URL
    target_filename = None
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
            base_name = f"tender_{idx}.pdf"
        
        safe_name = clean_filename(base_name)
        target_filename = f"{year}_{idx:03d}_{safe_name}"
        if not target_filename.lower().endswith(('.pdf', '.doc', '.docx', '.xls', '.xlsx', '.zip')):
            target_filename += '.pdf'
        
        file_path = os.path.join(OUTPUT_DIR, target_filename)
        
        # Check if already exists and has content
        if os.path.exists(file_path) and os.path.getsize(file_path) > 0:
            file_size = os.path.getsize(file_path)
            # print(f"[=] Cached: {target_filename}")
        else:
            with open(file_path, 'wb') as f:
                for chunk in resp.iter_content(chunk_size=16384):
                    if chunk:
                        f.write(chunk)
            file_size = os.path.getsize(file_path)
            print(f"[↓] Downloaded {target_filename} ({format_size(file_size)})")

        return {
            'financial_year': year,
            'category': 'Open Tenders',
            'type': 'tender',
            'status': 'open' if year in ['2026-2027', '2025-2026'] else 'closed',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"Tender documentation for {year} - {item['title']}",
            'document_url': f"/storage/uploads/tenders/{target_filename}",
            'download_count': item['download_count'],
            'file_name': target_filename,
            'file_size': format_size(file_size),
        }
    except Exception as e:
        print(f"[!] Error downloading {href} ({item['title'][:30]}): {e}")
        return None

def main():
    import urllib3
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
    
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    os.makedirs(os.path.dirname(MANIFEST_PATH), exist_ok=True)
    
    all_items = []
    session = requests.Session()
    
    _, target_years = get_config()
    
    print(f"=== Starting Tender Crawl for {len(target_years)} years ===")
    for year in target_years:
        items = scrape_year_page(session, year)
        all_items.extend(items)
        time.sleep(0.5)
        
    print(f"=== Total tenders found across all years: {len(all_items)} ===")
    print(f"=== Downloading tender files to {OUTPUT_DIR} with 8 workers ===")
    
    manifest_records = []
    with ThreadPoolExecutor(max_workers=8) as executor:
        futures = {executor.submit(download_file, item): item for item in all_items}
        for future in as_completed(futures):
            res = future.result()
            if res:
                manifest_records.append(res)
    
    print(f"=== Successfully processed {len(manifest_records)} / {len(all_items)} documents ===")
    
    with open(MANIFEST_PATH, 'w') as f:
        json.dump(manifest_records, f, indent=2)
    print(f"=== Manifest saved to {MANIFEST_PATH} ===")

if __name__ == '__main__':
    main()
