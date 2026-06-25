<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\WeatherService;
use Illuminate\Console\Command;

class CheckWeatherForUpcomingEvents extends Command
{
    /**
     * Jalankan: php artisan weather:check-upcoming
     * Idealnya dijadwalkan tiap hari via scheduler.
     */
    protected $signature = 'weather:check-upcoming {--days=3 : Jumlah hari sebelum acara untuk mulai mengecek}';

    protected $description = 'Cek dan simpan laporan cuaca otomatis untuk event yang akan terjadi H-3 (atau sesuai opsi --days)';

    public function handle(WeatherService $weatherService): int
    {
        $daysBefore = (int) $this->option('days');

        // Ambil semua event dalam rentang hari ini sampai H-3 ke depan, yang sudah punya venue
        $events = Event::with('venues')
            ->whereBetween('event_date', [now()->toDateString(), now()->addDays($daysBefore)->toDateString()])
            ->whereHas('venues')
            ->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada event dalam rentang H-' . $daysBefore . ' yang perlu dicek.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$events->count()} event untuk dicek cuacanya.");

        foreach ($events as $event) {
            $this->line("Mengecek cuaca untuk: {$event->name} (tanggal {$event->event_date->format('d M Y')})");

            $reports = $weatherService->checkWeatherForEvent($event);

            $this->info('  -> ' . count($reports) . ' laporan cuaca disimpan.');
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
