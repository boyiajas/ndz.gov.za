<?php

namespace App\Console\Commands;

use App\Models\ProcurementNotice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncBriefingLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'briefing-links:sync-live 
                            {--skip-download : Skip downloading and only import existing manifest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Briefing Link documents from the live site to dev box and import records into database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $skipDownload = $this->option('skip-download');
        $manifestPath = storage_path('app/briefing_links_manifest.json');

        if (!$skipDownload) {
            $this->info('Starting Python downloader for Briefing Link documents from ndz.gov.za...');
            $scriptPath = base_path('scripts/download_and_import_briefing_links.py');
            $cmd = 'python3 ' . escapeshellarg($scriptPath) . ' --base-dir ' . escapeshellarg(base_path());

            $exitCode = 0;
            passthru($cmd, $exitCode);

            if ($exitCode !== 0) {
                $this->error('Python download script finished with error code: ' . $exitCode);
                return self::FAILURE;
            }
        }

        if (!File::exists($manifestPath)) {
            $this->error("Manifest file not found at: {$manifestPath}");
            return self::FAILURE;
        }

        $records = json_decode(File::get($manifestPath), true);
        if (!is_array($records)) {
            $this->error('Invalid manifest JSON.');
            return self::FAILURE;
        }

        $this->info("Importing " . count($records) . " Briefing Link documents into database...");
        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        $imported = 0;
        $updated = 0;

        foreach ($records as $item) {
            $notice = ProcurementNotice::where('category', 'Briefing Link')
                ->where(function ($query) use ($item) {
                    $query->where('document_url', $item['document_url'])
                        ->orWhere('reference_no', $item['reference_no'])
                        ->orWhere('title', $item['title']);
                })
                ->first();

            $data = [
                'type' => $item['type'] ?? 'tender',
                'category' => 'Briefing Link',
                'status' => $item['status'] ?? 'open',
                'financial_year' => $item['financial_year'] ?? 'Current',
                'reference_no' => $item['reference_no'] ?? null,
                'title' => $item['title'],
                'description' => $item['description'] ?? "Briefing Link - {$item['title']}",
                'document_url' => $item['document_url'],
                'download_count' => (int) ($item['download_count'] ?? 0),
                'file_name' => $item['file_name'] ?? null,
                'file_size' => $item['file_size'] ?? null,
            ];

            if ($notice) {
                $notice->update($data);
                $updated++;
            } else {
                ProcurementNotice::create($data);
                $imported++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import completed successfully! Created: {$imported}, Updated: {$updated}.");

        return self::SUCCESS;
    }
}
