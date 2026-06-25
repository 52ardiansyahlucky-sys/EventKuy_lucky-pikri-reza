<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek cuaca otomatis tiap hari jam 07:00 pagi untuk event yang H-3
Schedule::command('weather:check-upcoming --days=3')
    ->dailyAt('07:00')
    ->withoutOverlapping();
