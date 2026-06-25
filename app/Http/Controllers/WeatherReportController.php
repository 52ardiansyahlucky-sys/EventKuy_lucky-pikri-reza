<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Auth;

class WeatherReportController extends Controller
{
    public function __construct(protected WeatherService $weatherService)
    {
    }

    /**
     * Trigger manual: cek ulang cuaca untuk semua venue di event ini.
     */
    public function refresh(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        if ($event->venues->isEmpty()) {
            return redirect()
                ->route('events.show', $event)
                ->with('error', 'Tambahkan venue ke event ini terlebih dahulu sebelum cek cuaca.');
        }

        $reports = $this->weatherService->checkWeatherForEvent($event);

        if (empty($reports)) {
            return redirect()
                ->route('events.show', $event)
                ->with('error', 'Gagal mengambil data cuaca. Pastikan venue memiliki koordinat lat/long dan API key valid.');
        }

        return redirect()
            ->route('events.show', $event)
            ->with('success', count($reports) . ' laporan cuaca berhasil diperbarui.');
    }
}
