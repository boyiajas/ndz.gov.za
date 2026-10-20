#!/usr/bin/env python3
"""
Scrapes all Intention to Award documents from ndz.gov.za:
- https://ndz.gov.za/intention-to-award-2020-2021/
- https://ndz.gov.za/intention-to-award-2018-2019/
- https://ndz.gov.za/intention-to-award/
- https://ndz.gov.za/awarded-tenders/

Downloads the files (.pdf, .xlsx, .docx, .doc, .zip) to:
- storage/app/public/uploads/intention_to_award/

And generates a JSON manifest at storage/app/intention_to_award_manifest.json
for import into the procurement_notices table.
"""

import os
import re
import sys
import json
import time
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
OUTPUT_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/intention_to_award")
MANIFEST_PATH = os.path.join(BASE_DIR, "storage/app/intention_to_award_manifest.json")

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

PAGES = [
    {
        'url': 'https://ndz.gov.za/intention-to-award-2020-2021/',
        'category': 'Intention To Award',
        'type': 'tender',
        'financial_year': '2020-2021',
        'dir': OUTPUT_DIR,
        'url_prefix': '/storage/uploads/intention_to_award/',
        'file_prefix': 'intention_2020_2021',
    },
    {
        'url': 'https://ndz.gov.za/intention-to-award-2018-2019/',
        'category': 'Intention To Award',
        'type': 'tender',
        'financial_year': '2018-2019',
        'dir': OUTPUT_DIR,
        'url_prefix': '/storage/uploads/intention_to_award/',
        'file_prefix': 'intention_2018_2019',
    },
    {
        'url': 'https://ndz.gov.za/intention-to-award/',
        'category': 'Intention To Award',
        'type': 'tender',
        'financial_year': '2018-2019',
        'dir': OUTPUT_DIR,
        'url_prefix': '/storage/uploads/intention_to_award/',
        'file_prefix': 'intention_archive',
    },
    {
        'url': 'https://ndz.gov.za/awarded-tenders/',
        'category': 'Intention To Award',
        'type': 'tender',
        'financial_year': '2020-2021',
        'dir': OUTPUT_DIR,
        'url_prefix': '/storage/uploads/intention_to_award/',
        'file_prefix': 'awarded_tenders',
    },
]

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
    print(f"[*] Scraping Intention to Award page: {url}")
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
            title = (m_link.group(2) or m_link.group(3)).strip()
            dl_count = int(m_dl.group(1)) if m_dl else 0
            
            ref_match = re.search(r'\b([A-Z0-9\-_]+/[0-9\-_/]+)\b', title)
            if ref_match:
                ref_no = ref_match.group(1)
            else:
                ref_no = f"NDZ-AWD-{config['financial_year']}-{idx:03d}"

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
            return None
        
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
            base_name = f"award_doc_{idx}{ext}"
            
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
            'financial_year': cfg['financial_year'],
            'category': 'Intention To Award',
            'type': cfg['type'],
            'status': 'open',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"Intention to Award - {item['title']}",
            'document_url': f"{cfg['url_prefix']}{target_filename}",
            'download_count': item['download_count'],
            'file_name': target_filename,
            'file_size': format_size(file_size),
        }
    except Exception as e:
        print(f"[!] Error downloading {href} ({item['title'][:30]}): {e}")
        return None

def main():
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    os.makedirs(os.path.dirname(MANIFEST_PATH), exist_ok=True)
    
    all_items = []
    seen_hrefs = set()
    
    for cfg in PAGES:
        items = scrape_page(cfg)
        for it in items:
            key = (it['href'], it['config']['financial_year'])
            if key not in seen_hrefs:
                seen_hrefs.add(key)
                all_items.append(it)
        time.sleep(0.3)
        
    print(f"=== Total unique Intention to Award items across all pages: {len(all_items)} ===")
    print(f"=== Downloading documents to {OUTPUT_DIR} with 8 workers ===")
    
    manifest_records = []
    with ThreadPoolExecutor(max_workers=8) as executor:
        futures = {executor.submit(download_file, it): it for it in all_items}
        for future in as_completed(futures):
            res = future.result()
            if res:
                manifest_records.append(res)
                
    manifest_records.sort(key=lambda r: (r['financial_year'], r['title']))
    
    print(f"=== Successfully processed {len(manifest_records)} / {len(all_items)} documents ===")
    with open(MANIFEST_PATH, 'w') as f:
        json.dump(manifest_records, f, indent=2)
    print(f"=== Manifest saved to {MANIFEST_PATH} ===")

if __name__ == '__main__':
    main()
