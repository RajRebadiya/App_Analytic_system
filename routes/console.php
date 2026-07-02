<?php

use App\Models\ApiLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notifications:send-scheduled')->everyMinute();

// Delete API logs older than 7 days every night at 12:00 AM (midnight)
Schedule::call(function () {
    ApiLog::where('created_at', '<', now()->subDays(7))->delete();
})->dailyAt('00:00');
