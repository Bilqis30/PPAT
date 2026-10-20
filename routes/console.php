<?php

use App\Models\Setting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 |--------------------------------------------------------------------------
 | Console Task Scheduler Registration
 |--------------------------------------------------------------------------
 | Scheduler berjalan setiap menit dan membandingkan jam WIB saat ini
 | dengan wa_reminder_time dari database. Perubahan jam di web Admin
 | langsung efektif tanpa perlu restart server.
 */
Schedule::call(function () {
    if (! Schema::hasTable('settings')) {
        return;
    }

    $reminderTime = Setting::get('wa_reminder_time', '07:00') ?: '07:00';
    $currentTime = now()->format('H:i'); // Sudah dalam WIB karena timezone Asia/Jakarta

    if ($currentTime === $reminderTime) {
        Artisan::call('notification:send-event-reminder');
    }
})->everyMinute()->name('wa-reminder-dynamic')->withoutOverlapping();
