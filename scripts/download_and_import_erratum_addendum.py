#!/usr/bin/env python3
"""
Scrapes all Erratum And Addendum documents from ndz.gov.za:
- https://ndz.gov.za/erratum-and-addendum/

Downloads the files (.pdf, etc.) to:
- storage/app/public/uploads/erratum_and_addendum/

And generates a JSON manifest at storage/app/erratum_and_addendum_manifest.json
for import into the procurement_notices table.
"""

import os
import re
import sys
import json
import time
import html
import urllib.parse
from concurrent.futures import ThreadPoolExecutor, as_completed
import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def get_base_dir():
    base = os.getenv("LARAVEL_BASE_PATH", os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    args = sys.argv[1:]
    i = 0
    while i < len(args):
        if args[i] == '--base-dir' and i + 1 < len(args):
            base = args[i + 1]
            break
        i += 1
    return os.path.abspath(base)

BASE_DIR = get_base_dir()
OUTPUT_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/erratum_and_addendum")
MANIFEST_PATH = os.path.join(BASE_DIR, "storage/app/erratum_and_addendum_manifest.json")

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

PAGE_CONFIG = {
    'url': 'https://ndz.gov.za/erratum-and-addendum/',
    'category': 'Erratum And Addendum',
    'type': 'tender',
    'financial_year': 'Current',
    'dir': OUTPUT_DIR,
    'url_prefix': '/storage/uploads/erratum_and_addendum/',
    'file_prefix': 'erratum_addendum',
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

def scrape_page(config):
    url = config['url']
    print(f"[*] Scraping Erratum And Addendum page: {url}")
    try:
        resp = requests.get(url, headers=HEADERS, timeout=25, verify=False)
        if resp.status_code != 200:
            print(f"[!] Failed to fetch {url}: HTTP {resp.status_code}")
            return []
        
        all_rows = re.findall(r'<tr[^>]*>.*?</tr>', resp.text, re.DOTALL | re.IGNORECASE)
        rows = [r for r in all_rows if 'attachment-link' in r]
        items = []
        for i, row in enumerate(rows, start=1):
            m_idx = re.search(r'class=[\"\']attachment-index[\"\']>(\d+)<', row)
            m_link = re.search(r'<a href=\"([^\"]+)\"[^>]*title=\"([^\"]*)\"[^>]*>(.*?)</a>', row)
            m_dl = re.search(r'class=[\"\']attachment-downloads[\"\']>(\d+)<', row)
            
            idx = int(m_idx.group(1)) if m_idx else i
            if not m_link:
                continue
            href = m_link.group(1).strip()
            raw_title = (m_link.group(2) or m_link.group(3)).strip()
            title = html.unescape(raw_title)
            dl_count = int(m_dl.group(1)) if m_dl else 0
            
            ref_no = f"NDZ-EA-{idx:03d}"

            items.append({
                'config': config,
                'index': idx,
                'title': title,
                'href': href,
                'download_count': dl_count,
                'reference_no': ref_no
            })
            
        print(f"[+] Found {len(items)} items on {url}")
        return items
    except Exception as e:
        print(f"[!] Error fetching {url}: {e}")
        return []

def download_file(item):
    session = requests.Session()
    session.headers.update(HEADERS)
    
    cfg = item['config']
    href = item['href']
    idx = item['index']
    target_dir = cfg['dir']
    
    try:
        resp = session.get(href, headers=HEADERS, timeout=35, allow_redirects=True, verify=False, stream=True)
        if resp.status_code != 200:
            print(f"[-] HTTP {resp.status_code} downloading {item['title'][:40]}")
            return {
                'index': idx,
                'financial_year': cfg['financial_year'],
                'category': cfg['category'],
                'type': cfg['type'],
                'status': 'open',
                'reference_no': item['reference_no'],
                'title': item['title'],
                'description': f"Erratum And Addendum - {item['title']}",
                'document_url': href,
                'download_count': item['download_count'],
                'file_name': f"erratum_addendum_{idx:03d}.pdf",
                'file_size': 'N/A',
            }
        
        final_url = resp.url
        parsed_path = urllib.parse.urlparse(final_url).path
        base_name = os.path.basename(parsed_path)
        base_name = urllib.parse.unquote(base_name)
        if not base_name or '.' not in base_name:
            ct = resp.headers.get('Content-Type', '').lower()
            if 'sheet' in ct or 'excel' in ct:
                ext = '.xlsx'
            elif 'word' in ct or 'document' in ct:
                ext = '.docx'
            elif 'zip' in ct:
                ext = '.zip'
            else:
                ext = '.pdf'
            base_name = f"erratum_doc_{idx}{ext}"
            
        safe_name = clean_filename(base_name)
        target_filename = f"{cfg['file_prefix']}_{idx:03d}_{safe_name}"
        file_path = os.path.join(target_dir, target_filename)
        
        if os.path.exists(file_path) and os.path.getsize(file_path) > 0:
            file_size = os.path.getsize(file_path)
        else:
            with open(file_path, 'wb') as f:
                for chunk in resp.iter_content(chunk_size=16384):
                    if chunk:
                        f.write(chunk)
            file_size = os.path.getsize(file_path)
            print(f"[↓] Downloaded {target_filename} ({format_size(file_size)})")

        return {
            'index': idx,
            'financial_year': cfg['financial_year'],
            'category': cfg['category'],
            'type': cfg['type'],
            'status': 'open',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"Erratum And Addendum - {item['title']}",
            'document_url': f"{cfg['url_prefix']}{target_filename}",
            'download_count': item['download_count'],
            'file_name': target_filename,
            'file_size': format_size(file_size),
        }
    except Exception as e:
        print(f"[!] Error downloading {href} ({item['title'][:30]}): {e}")
        return {
            'index': idx,
            'financial_year': cfg['financial_year'],
            'category': cfg['category'],
            'type': cfg['type'],
            'status': 'open',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"Erratum And Addendum - {item['title']}",
            'document_url': href,
            'download_count': item['download_count'],
            'file_name': f"erratum_addendum_{idx:03d}.pdf",
            'file_size': 'N/A',
        }

def main():
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    os.makedirs(os.path.dirname(MANIFEST_PATH), exist_ok=True)
    
    items = scrape_page(PAGE_CONFIG)
    print(f"=== Total unique Erratum And Addendum items: {len(items)} ===")
    print(f"=== Downloading documents to {OUTPUT_DIR} with 8 workers ===")
    
    manifest_records = []
    with ThreadPoolExecutor(max_workers=8) as executor:
        futures = {executor.submit(download_file, it): it for it in items}
        for future in as_completed(futures):
            res = future.result()
            if res:
                manifest_records.append(res)
                
    manifest_records.sort(key=lambda r: r['index'])
    
    print(f"=== Successfully processed {len(manifest_records)} / {len(items)} documents ===")
    with open(MANIFEST_PATH, 'w') as f:
        json.dump(manifest_records, f, indent=2)
    print(f"=== Manifest saved to {MANIFEST_PATH} ===")

if __name__ == '__main__':
    main()
