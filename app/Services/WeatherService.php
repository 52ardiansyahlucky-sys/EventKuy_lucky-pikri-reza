<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Venue;
use App\Models\WeatherReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.base_url');
    }

    /**
     * Cek cuaca untuk SEMUA venue yang terhubung ke sebuah event,
     * lalu simpan hasilnya sebagai WeatherReport.
     *
     * @return array<WeatherReport>
     */
    public function checkWeatherForEvent(Event $event): array
    {
        $reports = [];

        foreach ($event->venues as $venue) {
            $report = $this->checkWeatherForVenue($event, $venue);
            if ($report) {
                $reports[] = $report;
            }
        }

        return $reports;
    }

    /**
     * Cek cuaca untuk satu venue tertentu pada tanggal event.
     */
    public function checkWeatherForVenue(Event $event, Venue $venue): ?WeatherReport
    {
        if (! $venue->hasCoordinates()) {
            Log::warning("Venue #{$venue->id} ({$venue->name}) tidak punya koordinat, skip cek cuaca.");
            return null;
        }

        $forecastData = $this->fetchForecast($venue->latitude, $venue->longitude);

        if (! $forecastData) {
            return null;
        }

        $matched = $this->findClosestForecast($forecastData, $event->event_date);

        if (! $matched) {
            return null;
        }

        $rainProbability = ($matched['pop'] ?? 0) * 100; // API kasih 0-1, kita ubah ke persen
        [$level, $recommendationText] = $this->generateRecommendation($rainProbability, $matched);

        return WeatherReport::updateOrCreate(
            [
                'event_id' => $event->id,
                'venue_id' => $venue->id,
                'forecast_date' => $event->event_date->format('Y-m-d'),
            ],
            [
                'weather_main' => $matched['weather'][0]['main'] ?? null,
                'weather_description' => $matched['weather'][0]['description'] ?? null,
                'temperature' => $matched['main']['temp'] ?? null,
                'humidity' => $matched['main']['humidity'] ?? null,
                'rain_probability' => round($rainProbability, 2),
                'wind_speed' => $matched['wind']['speed'] ?? null,
                'recommendation_level' => $level,
                'recommendation_text' => $recommendationText,
                'checked_at' => now(),
            ]
        );
    }

    /**
     * Panggil endpoint 5 day / 3 hour forecast dari OpenWeatherMap.
     */
    protected function fetchForecast(float $lat, float $lon): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'id',
            ]);

            if ($response->failed()) {
                Log::error('OpenWeatherMap API error: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Gagal menghubungi OpenWeatherMap: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * API forecast memberi data per 3 jam selama 5 hari ke depan.
     * Cari slot waktu yang paling dekat dengan tanggal event (ambil jam 12:00 siang sebagai acuan).
     */
    protected function findClosestForecast(array $forecastData, Carbon $eventDate): ?array
    {
        $list = $forecastData['list'] ?? [];

        if (empty($list)) {
            return null;
        }

        $targetTimestamp = $eventDate->copy()->setTime(12, 0)->timestamp;

        $closest = null;
        $smallestDiff = null;

        foreach ($list as $item) {
            $diff = abs($item['dt'] - $targetTimestamp);

            if ($smallestDiff === null || $diff < $smallestDiff) {
                $smallestDiff = $diff;
                $closest = $item;
            }
        }

        return $closest;
    }

    /**
     * Logic rekomendasi otomatis kesiapan tenda/pawang berdasarkan % kemungkinan hujan.
     *
     * @return array{0: string, 1: string} [level, teks rekomendasi]
     */
    protected function generateRecommendation(float $rainProbability, array $forecastItem): array
    {
        $windSpeed = $forecastItem['wind']['speed'] ?? 0;

        if ($rainProbability >= 70) {
            return [
                'siaga',
                "Kemungkinan hujan sangat tinggi ({$rainProbability}%). Sangat disarankan menyiapkan tenda penuh (full cover) dan koordinasi dengan pawang hujan H-3. Pertimbangkan rencana cadangan indoor.",
            ];
        }

        if ($rainProbability >= 40) {
            return [
                'waspada',
                "Ada kemungkinan hujan sedang ({$rainProbability}%). Disarankan menyiapkan tenda parsial/cover tambahan di area terbuka, dan siapkan kontak pawang hujan sebagai antisipasi.",
            ];
        }

        if ($windSpeed >= 8) {
            return [
                'waspada',
                "Kemungkinan hujan rendah ({$rainProbability}%), namun angin cukup kencang ({$windSpeed} m/s). Pastikan tenda/dekorasi outdoor dipasang dengan pengaman ekstra.",
            ];
        }

        return [
            'aman',
            "Kemungkinan hujan rendah ({$rainProbability}%). Cuaca diperkirakan cukup mendukung untuk acara outdoor. Tetap sediakan tenda standar sebagai antisipasi umum.",
        ];
    }
}
