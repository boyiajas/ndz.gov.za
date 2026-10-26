<?php

namespace App\Jobs;

use App\Models\ProcurementNotice;
use App\Models\Vacancy;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CloseExpiredListingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * Scans tenders, quotations, and vacancies whose closing date has passed
     * and automatically updates their status from 'open' to 'closed'.
     *
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        $now = Carbon::now();
        $closedTenders = 0;
        $closedQuotes = 0;
        $closedVacancies = 0;

        // 1. Process Expired Procurement Notices (Tenders and Quotations)
        $expiredNotices = ProcurementNotice::query()
            ->where('status', ProcurementNotice::STATUS_OPEN)
            ->whereNotNull('closing_date')
            ->where('closing_date', '<=', $now)
            ->get();

        foreach ($expiredNotices as $notice) {
            $oldStatus = $notice->status;
            $notice->update(['status' => ProcurementNotice::STATUS_CLOSED]);

            if ($notice->type === ProcurementNotice::TYPE_QUOTE) {
                $closedQuotes++;
            } else {
                $closedTenders++;
            }

            $closingDateStr = $notice->closing_date instanceof \DateTimeInterface
                ? $notice->closing_date->format('Y-m-d H:i')
                : (string) $notice->closing_date;

            AuditService::log(
                action: 'auto_close',
                module: 'Procurement',
                description: "System auto-closed expired {$notice->type} notice '{$notice->reference_no}' - {$notice->title} (Closing date was {$closingDateStr})",
                auditable: $notice,
                oldValues: ['status' => $oldStatus],
                newValues: ['status' => ProcurementNotice::STATUS_CLOSED],
                ipAddress: '127.0.0.1',
                userAgent: 'Queue Worker / Scheduler'
            );
        }

        // 2. Process Expired Vacancies / Job Posts
        $expiredVacancies = Vacancy::query()
            ->where('status', Vacancy::STATUS_OPEN)
            ->whereNotNull('closing_date')
            ->where('closing_date', '<=', $now)
            ->get();

        foreach ($expiredVacancies as $vacancy) {
            $oldStatus = $vacancy->status;
            $vacancy->update(['status' => Vacancy::STATUS_CLOSED]);
            $closedVacancies++;

            $closingDateStr = $vacancy->closing_date instanceof \DateTimeInterface
                ? $vacancy->closing_date->format('Y-m-d H:i')
                : (string) $vacancy->closing_date;

            AuditService::log(
                action: 'auto_close',
                module: 'Vacancies',
                description: "System auto-closed expired vacancy '{$vacancy->reference_no}' - {$vacancy->title} (Closing date was {$closingDateStr})",
                auditable: $vacancy,
                oldValues: ['status' => $oldStatus],
                newValues: ['status' => Vacancy::STATUS_CLOSED],
                ipAddress: '127.0.0.1',
                userAgent: 'Queue Worker / Scheduler'
            );
        }

        $summary = [
            'tenders_closed' => $closedTenders,
            'quotes_closed' => $closedQuotes,
            'vacancies_closed' => $closedVacancies,
            'total_closed' => $closedTenders + $closedQuotes + $closedVacancies,
            'processed_at' => $now->toIso8601String(),
        ];

        Log::info('CloseExpiredListingsJob executed successfully:', $summary);

        return $summary;
    }
}
