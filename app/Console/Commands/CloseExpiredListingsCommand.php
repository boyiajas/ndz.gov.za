<?php

namespace App\Console\Commands;

use App\Jobs\CloseExpiredListingsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CloseExpiredListingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listings:close-expired 
                            {--queue : Dispatch as a queued background job} 
                            {--work : Process queued jobs immediately using queue worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update status of tenders, quotes, and vacancies from open to closed if their closing date has lapsed.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('[' . now()->toDateTimeString() . '] Checking for expired tenders, quotes, and vacancies...');

        if ($this->option('queue')) {
            $this->info('Dispatching CloseExpiredListingsJob to the queue...');
            CloseExpiredListingsJob::dispatch();
            $this->info('CloseExpiredListingsJob dispatched to queue successfully.');

            if ($this->option('work')) {
                $this->info('Processing queue jobs immediately...');
                Artisan::call('queue:work', [
                    '--stop-when-empty' => true,
                    '--tries' => 3,
                ], $this->getOutput());
            }

            return Command::SUCCESS;
        }

        // Run synchronously
        $job = new CloseExpiredListingsJob();
        $result = $job->handle();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Tenders Closed', $result['tenders_closed']],
                ['Quotations Closed', $result['quotes_closed']],
                ['Vacancies Closed', $result['vacancies_closed']],
                ['Total Closed', $result['total_closed']],
            ]
        );

        $this->info("Completed at {$result['processed_at']}. Total listings auto-closed: {$result['total_closed']}.");

        return Command::SUCCESS;
    }
}
