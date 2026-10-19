#!/usr/bin/env python3
"""
Scrapes all Bid Documents and Quote Documents from ndz.gov.za:
- https://ndz.gov.za/bid-documents-current/
- https://ndz.gov.za/bid-documents-2025-2026/
- https://ndz.gov.za/quote-documents-current/
- https://ndz.gov.za/quote-documents-2025-2026/

Downloads the files (.pdf, .xlsx, .docx, .doc, .zip) to:
- storage/app/public/uploads/bid_documents/
- storage/app/public/uploads/quote_documents/

And generates a JSON manifest at storage/app/bid_and_quote_docs_manifest.json
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

BASE_DIR = "/var/www/Laravel_Projects/ndz.gov.za"
BID_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/bid_documents")
QUOTE_DIR = os.path.join(BASE_DIR, "storage/app/public/uploads/quote_documents")
MANIFEST_PATH = os.path.join(BASE_DIR, "storage/app/bid_and_quote_docs_manifest.json")

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

PAGES = [
    {
        'url': 'https://ndz.gov.za/bid-documents-current/',
        'category': 'Bid Documents',
        'type': 'tender',
        'financial_year': 'Current',
        'dir': BID_DIR,
        'url_prefix': '/storage/uploads/bid_documents/',
        'file_prefix': 'bid_current',
    },
    {
        'url': 'https://ndz.gov.za/bid-documents-2025-2026/',
        'category': 'Bid Documents',
        'type': 'tender',
        'financial_year': '2025-2026',
        'dir': BID_DIR,
        'url_prefix': '/storage/uploads/bid_documents/',
        'file_prefix': 'bid_2025-2026',
    },
    {
        'url': 'https://ndz.gov.za/quote-documents-current/',
        'category': 'Quote Documents',
        'type': 'quote',
        'financial_year': 'Current',
        'dir': QUOTE_DIR,
        'url_prefix': '/storage/uploads/quote_documents/',
        'file_prefix': 'quote_doc_current',
    },
    {
        'url': 'https://ndz.gov.za/quote-documents-2025-2026/',
        'category': 'Quote Documents',
        'type': 'quote',
        'financial_year': '2025-2026',
        'dir': QUOTE_DIR,
        'url_prefix': '/storage/uploads/quote_documents/',
        'file_prefix': 'quote_doc_2025-2026',
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
    print(f"[*] Scraping page: {url}")
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
                p_code = 'BID' if config['type'] == 'tender' else 'RFQ-DOC'
                ref_no = f"NDZ-{p_code}-{config['financial_year']}-{idx:03d}"

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
            base_name = f"doc_{idx}{ext}"
            
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
            'category': cfg['category'],
            'type': cfg['type'],
            'status': 'open',
            'reference_no': item['reference_no'],
            'title': item['title'],
            'description': f"{cfg['category']} - {item['title']}",
            'document_url': f"{cfg['url_prefix']}{target_filename}",
            'download_count': item['download_count'],
            'file_name': target_filename,
            'file_size': format_size(file_size),
        }
    except Exception as e:
        print(f"[!] Error downloading {href} ({item['title'][:30]}): {e}")
        return None

def main():
    os.makedirs(BID_DIR, exist_ok=True)
    os.makedirs(QUOTE_DIR, exist_ok=True)
    os.makedirs(os.path.dirname(MANIFEST_PATH), exist_ok=True)
    
    print("=== Scraping Bid Documents & Quote Documents Pages ===")
    all_items = []
    for cfg in PAGES:
        items = scrape_page(cfg)
        all_items.extend(items)
        time.sleep(0.3)
        
    print(f"=== Total document links found: {len(all_items)} ===")
    print("=== Downloading all documents with 8 concurrent workers ===")
    
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
