<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Geocode city name using Google Geocoding API.
     *
     * @return array{latitude: float, longitude: float}|null
     */
    public function geocodeCity(string $city): ?array
    {
        $city = trim($city);
        if ($city === '') {
            return null;
        }

        $apiKey = config('services.google_geocoding.key');
        if (! $apiKey) {
            Log::warning('Geocoding skipped: GOOGLE_GEOCODING_API_KEY not set.');
            return null;
        }

        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $city,
                'key' => $apiKey,
            ]);

            if ($response->failed()) {
                Log::error('Google Geocoding API HTTP error: ' . $response->status() . ' ' . $response->body());
                return null;
            }

            $data = $response->json();

            if (($data['status'] ?? null) !== 'OK') {
                Log::error('Google Geocoding API error: ' . json_encode($data));
                return null;
            }

            $location = $data['results'][0]['geometry']['location'] ?? null;
            if (! $location) {
                return null;
            }

            return [
                'latitude' => (float) $location['lat'],
                'longitude' => (float) $location['lng'],
            ];
        } catch (\Throwable $e) {
            Log::error('Geocoding failed: ' . $e->getMessage());
            return null;
        }
    }
}

