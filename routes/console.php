<?php

use App\Jobs\CloseExpiredListingsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Auto-close expired procurement listings (tenders, quotations) and vacancies
| once a day at midnight. Uses the queued job and processes the queue cleanly.
|
*/
Schedule::command('listings:close-expired --queue --work')
    ->dailyAt('00:00')
    ->name('close-expired-listings')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/cron-listings.log'));
