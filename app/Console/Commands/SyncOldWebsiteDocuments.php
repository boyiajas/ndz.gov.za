<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentSubcategory;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncOldWebsiteDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:sync-old-website
                            {--category= : Specific category slug to sync (e.g. office-of-the-municipal-manager)}
                            {--dry-run : Only crawl and list files without downloading or writing to database}
                            {--limit= : Limit number of files to process per page}
                            {--delay=0.3 : Delay in seconds between HTTP requests to be gentle on server}
                            {--skip-existing : Skip download if file already exists on local disk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl ndz.gov.za, download document attachments, and sync them into local categories and subcategories.';

    /**
     * Known mapping of the 13 official categories to their URLs on the old website.
     *
     * @var array<string, array{page: string, subpages?: array<string, string>}>
     */
    protected array $categoryMap = [
        'office-of-the-municipal-manager' => [
            'page' => 'https://ndz.gov.za/office-of-the-municipal-manager/',
        ],
        'corporate-services' => [
            'page' => 'https://ndz.gov.za/corporate-services/',
            'subpages' => [
                'Organisational Structure' => 'https://ndz.gov.za/organisational-structure/',
                'Administration' => 'https://ndz.gov.za/corporate-services/administration/',
                'Information Technology' => 'https://ndz.gov.za/corporate-services/information-and-communication-technology/ict-policies/',
                'Human Resources' => 'https://ndz.gov.za/corporate-services/human-resources/',
                'HR Policies' => 'https://ndz.gov.za/corporate-services/human-resources/hr-policies/',
            ],
        ],
        'budget-treasury' => [
            'page' => 'https://ndz.gov.za/budget-treasury/',
            'subpages' => [
                'Budget Reports' => 'https://ndz.gov.za/budget-treasury/budget-reporting/budget-reports/',
                'Monthly Reports' => 'https://ndz.gov.za/budget-treasury/budget-reporting/monthly-reports/',
                'Quarterly Reports' => 'https://ndz.gov.za/budget-treasury/budget-reporting/quarterly-reports/',
                'Annual Reports' => 'https://ndz.gov.za/budget-treasury/budget-reporting/annual-reports/',
                'Budget Documents' => 'https://ndz.gov.za/budget-treasury/budget-reporting-documents/',
                'MPRA' => 'https://ndz.gov.za/mpra/',
                'Organogram Finance' => 'https://ndz.gov.za/budget-treasury/organogram-finance/',
            ],
        ],
        'public-works-basic-services' => [
            'page' => 'https://ndz.gov.za/public-works-basic-services/',
            'subpages' => [
                'Service Delivery' => 'https://ndz.gov.za/public-works-basic-services/infrastructure-services-overview/service-delivery/',
            ],
        ],
        'community-services' => [
            'page' => 'https://ndz.gov.za/community-services/',
            'subpages' => [
                'Organogram Community Services' => 'https://ndz.gov.za/community-services/organogram-community-services/',
                'Disaster Management' => 'https://ndz.gov.za/community-services/disaster-management/',
                'Sports & Recreation' => 'https://ndz.gov.za/community-services/sports-recreation/',
                'Community Programmes' => 'https://ndz.gov.za/community-services/special-programmes/',
                'Youth Fund' => 'https://ndz.gov.za/community-services/youth/youth-fund/',
                'LED' => 'https://ndz.gov.za/community-services/led/',
                'Arts & Culture' => 'https://ndz.gov.za/community-services/arts-culture/',
            ],
        ],
        'development-town-planning-services' => [
            'page' => 'https://ndz.gov.za/development-and-town-planning-services/',
            'subpages' => [
                'MSDF' => 'https://ndz.gov.za/budget-treasury/budget-reporting-documents/msdf/',
                'Planning and Land Use Documents' => 'https://ndz.gov.za/planning-and-land-use-documents/',
            ],
        ],
        'mpac-reports' => [
            'page' => 'https://ndz.gov.za/mpac-reports/',
        ],
        'annual-reports' => [
            'page' => 'https://ndz.gov.za/annual-reports/',
        ],
        'pms' => [
            'page' => 'https://ndz.gov.za/pms/',
            'subpages' => [
                'Performance Agreements 2026/2027' => 'https://ndz.gov.za/performance-agreements-2026-2027/',
                'Performance Agreements 2025/2026' => 'https://ndz.gov.za/performance-agreements-2025-2026/',
                'Performance Agreements 2024/2025' => 'https://ndz.gov.za/perfomance-agreements-2024-2025/',
                'Performance Agreements 2023/2024' => 'https://ndz.gov.za/perfomance-agreements-2023-2024/',
                'Performance Agreements 2021/2022' => 'https://ndz.gov.za/perfomance-agreements-2021-22/',
                'Performance Agreements 2020/2021' => 'https://ndz.gov.za/perfomance-agreement-2020-21/',
                'Final 2020/2021 SDBIP' => 'https://ndz.gov.za/final-2020-2021-sdbip/',
                'Special Revised 2019/2020 SDBIP' => 'https://ndz.gov.za/special-revised-2019-2020-sdbip/',
                'Revised SDBIP For 2018-2019' => 'https://ndz.gov.za/revised-sdbip-for-2018-2019/',
                '2017-2018 SDBIP' => 'https://ndz.gov.za/pms/201718-sdbip/',
                '201819 SDBIP' => 'https://ndz.gov.za/pms/201819-sdbip/',
                'Performance Contracts' => 'https://ndz.gov.za/pms/performance-contracts/',
                'Mid Year Performance Report' => 'https://ndz.gov.za/pms/mid-year-performance-report/',
                'Third Quarter Report' => 'https://ndz.gov.za/pms/third-quarter-report/',
            ],
        ],
        'idp' => [
            'page' => 'https://ndz.gov.za/idp/',
        ],
        'gazetted-by-laws' => [
            'page' => 'https://ndz.gov.za/by-laws/',
        ],
        'ward-based-plans' => [
            'page' => 'https://ndz.gov.za/ward-based-plans/',
        ],
        'policies' => [
            'page' => 'https://ndz.gov.za/policies/',
        ],
    ];

    /**
     * Storage directory where downloaded documents will be saved.
     */
    protected string $storageDir;

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $categoryFilter = $this->option('category');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $delay = (float) $this->option('delay');
        $skipExisting = (bool) $this->option('skip-existing');

        $this->storageDir = storage_path('app/public/uploads/documents');
        if (!File::isDirectory($this->storageDir)) {
            File::makeDirectory($this->storageDir, 0755, true);
        }

        $this->info('====================================================');
        $this->info('  NDZ MUNICIPALITY - DOCUMENT CRAWLER & SYNCHRONIZER');
        $this->info('====================================================');
        if ($isDryRun) {
            $this->warn('MODE: DRY-RUN (No files will be downloaded or written)');
        } else {
            $this->info('MODE: LIVE SYNC & DOWNLOAD');
            $this->line("Target Storage: {$this->storageDir}");
        }

        $categoriesToSync = $this->categoryMap;
        if ($categoryFilter) {
            if (!isset($categoriesToSync[$categoryFilter])) {
                $this->error("Category '{$categoryFilter}' not found in category map.");
                $this->line('Available categories: ' . implode(', ', array_keys($this->categoryMap)));
                return 1;
            }
            $categoriesToSync = [$categoryFilter => $categoriesToSync[$categoryFilter]];
        }

        $totalDiscovered = 0;
        $totalDownloaded = 0;
        $totalSkipped = 0;
        $totalErrors = 0;

        foreach ($categoriesToSync as $categorySlug => $categoryConfig) {
            $categoryModel = DocumentCategory::where('slug', $categorySlug)->first();
            if (!$categoryModel) {
                $this->warn("Skipping '{$categorySlug}' - not present in local database.");
                continue;
            }

            $this->newLine();
            $this->info("----------------------------------------------------");
            $this->info("Category: [{$categoryModel->name}] ({$categorySlug})");
            $this->info("----------------------------------------------------");

            // 1. Process category page direct attachments
            $pageUrl = $categoryConfig['page'];
            $this->line("Checking category page: <comment>{$pageUrl}</comment>");
            
            $directAttachments = $this->extractAttachments($pageUrl);
            $this->line("  Found " . count($directAttachments) . " direct attachment(s).");

            if ($limit !== null) {
                $directAttachments = array_slice($directAttachments, 0, $limit);
            }

            foreach ($directAttachments as $att) {
                $totalDiscovered++;
                $res = $this->processAttachment(
                    $att,
                    $categoryModel,
                    null, // Direct attachment (no subcategory)
                    $isDryRun,
                    $skipExisting,
                    $delay
                );

                if ($res === 'downloaded') $totalDownloaded++;
                elseif ($res === 'skipped') $totalSkipped++;
                elseif ($res === 'error') $totalErrors++;
            }

            // 2. Process subpages if defined
            if (!empty($categoryConfig['subpages'])) {
                foreach ($categoryConfig['subpages'] as $subName => $subUrl) {
                    $this->newLine();
                    $this->line("  Sub-page: <comment>{$subName}</comment> -> {$subUrl}");

                    // Find or create local subcategory
                    $subSlug = Str::slug($subName);
                    $subModel = DocumentSubcategory::firstOrCreate(
                        [
                            'document_category_id' => $categoryModel->id,
                            'slug' => $subSlug,
                        ],
                        [
                            'name' => $subName,
                            'description' => "Official {$subName} documents.",
                            'sort_order' => $categoryModel->subcategories()->count() + 1,
                            'is_active' => true,
                        ]
                    );

                    $subAttachments = $this->extractAttachments($subUrl);
                    $this->line("    Found " . count($subAttachments) . " attachment(s).");

                    if ($limit !== null) {
                        $subAttachments = array_slice($subAttachments, 0, $limit);
                    }

                    foreach ($subAttachments as $att) {
                        $totalDiscovered++;
                        $res = $this->processAttachment(
                            $att,
                            $categoryModel,
                            $subModel,
                            $isDryRun,
                            $skipExisting,
                            $delay
                        );

                        if ($res === 'downloaded') $totalDownloaded++;
                        elseif ($res === 'skipped') $totalSkipped++;
                        elseif ($res === 'error') $totalErrors++;
                    }

                    // Clean dummy sample listings if real files exist
                    if (!$isDryRun && count($subAttachments) > 0) {
                        Document::where('document_subcategory_id', $subModel->id)
                            ->whereNull('file_url')
                            ->delete();
                    }
                }
            }

            // Clean direct dummy sample listings if real direct files exist
            if (!$isDryRun && count($directAttachments) > 0) {
                Document::where('document_category_id', $categoryModel->id)
                    ->whereNull('document_subcategory_id')
                    ->whereNull('file_url')
                    ->delete();
            }
        }

        $this->newLine(2);
        $this->info("====================================================");
        $this->info("                    SYNC SUMMARY                    ");
        $this->info("====================================================");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Discovered', $totalDiscovered],
                ['Total Downloaded', $totalDownloaded],
                ['Total Skipped', $totalSkipped],
                ['Errors', $totalErrors],
            ]
        );

        return 0;
    }

    /**
     * Parse page HTML and return an array of attachment data.
     *
     * @return array<int, array{index: int, title: string, downloads: int, url: string}>
     */
    protected function extractAttachments(string $url): array
    {
        $html = $this->fetchHtml($url);
        if (!$html) {
            return [];
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Find table rows containing printable-file-attachments links
        $rows = $xpath->query("//tr[.//a[contains(@href, 'printable-file-attachments')]]");
        $results = [];

        foreach ($rows as $row) {
            $linkNode = $xpath->query(".//a[contains(@href, 'printable-file-attachments')]", $row)->item(0);
            $downloadsNode = $xpath->query(".//td[contains(@class, 'attachment-downloads')]", $row)->item(0);
            $indexNode = $xpath->query(".//td[contains(@class, 'attachment-index')]", $row)->item(0);

            if (!$linkNode) {
                continue;
            }

            $title = trim($linkNode->textContent);
            $href = $linkNode->getAttribute('href');
            $downloads = $downloadsNode ? (int) trim($downloadsNode->textContent) : 0;
            $index = $indexNode ? (int) trim($indexNode->textContent) : (count($results) + 1);

            $results[] = [
                'index' => $index,
                'title' => $title,
                'downloads' => $downloads,
                'url' => $href,
            ];
        }

        return $results;
    }

    /**
     * Download and process a single attachment.
     *
     * @param array{index: int, title: string, downloads: int, url: string} $att
     */
    protected function processAttachment(
        array $att,
        DocumentCategory $category,
        ?DocumentSubcategory $subcategory,
        bool $isDryRun,
        bool $skipExisting,
        float $delay
    ): string {
        $destLabel = $subcategory ? "Sub [{$subcategory->name}]" : "Direct [{$category->name}]";

        if ($isDryRun) {
            $this->line("    [DRY-RUN] #{$att['index']} {$att['title']} (Downloads: {$att['downloads']}) -> {$destLabel}");
            return 'skipped';
        }

        if ($delay > 0) {
            usleep((int) ($delay * 1000000));
        }

        $downloadResult = $this->downloadFile($att['url'], $att['title'], $skipExisting);
        if (!$downloadResult) {
            $this->error("    Failed to download #{$att['index']} {$att['title']}");
            return 'error';
        }

        [$filename, $wasDownloaded] = $downloadResult;
        $relativeUrl = "/storage/uploads/documents/{$filename}";

        // Upsert Document in database
        Document::updateOrCreate(
            [
                'document_category_id' => $category->id,
                'document_subcategory_id' => $subcategory?->id,
                'title' => $att['title'],
            ],
            [
                'description' => "Imported official municipal document: {$att['title']}.",
                'file_url' => $relativeUrl,
                'download_count' => $att['downloads'],
                'status' => Document::STATUS_PUBLISHED,
                'published_at' => now(),
                'sort_order' => $att['index'],
            ]
        );

        $action = $wasDownloaded ? '<info>Downloaded</info>' : '<comment>Skipped (cached)</comment>';
        $this->line("    {$action}: #{$att['index']} {$att['title']} -> {$relativeUrl} ({$destLabel})");

        return $wasDownloaded ? 'downloaded' : 'skipped';
    }

    /**
     * Download the file by following redirects from the printable-file-attachments URL.
     *
     * @return array{0: string, 1: bool}|null Returns [filename, wasDownloaded] or null on failure.
     */
    protected function downloadFile(string $url, string $title, bool $skipExisting): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 NDZ-Sync',
        ]);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError || $httpCode >= 400 || !$content) {
            return null;
        }

        // Determine filename
        $parsedPath = parse_url($finalUrl, PHP_URL_PATH);
        $filename = $parsedPath ? basename($parsedPath) : '';

        // If filename is missing or without extension, generate one
        if (!$filename || !str_contains($filename, '.')) {
            $ext = 'pdf';
            $filename = Str::slug($title) . '.' . $ext;
        }

        // Sanitize filename to avoid directory traversal or bad characters
        $filename = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
        $destinationPath = $this->storageDir . DIRECTORY_SEPARATOR . $filename;

        // Check if file already exists with identical size
        if ($skipExisting && File::exists($destinationPath) && File::size($destinationPath) === strlen($content)) {
            return [$filename, false];
        }

        File::put($destinationPath, $content);
        return [$filename, true];
    }

    /**
     * Fetch HTML using cURL with SSL bypass and realistic headers.
     */
    protected function fetchHtml(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) NDZ-Sync-Agent',
        ]);

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400 || !$html) {
            return null;
        }

        return $html;
    }
}
