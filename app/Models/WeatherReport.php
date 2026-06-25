<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'venue_id',
        'forecast_date',
        'weather_main',
        'weather_description',
        'temperature',
        'humidity',
        'rain_probability',
        'wind_speed',
        'recommendation_level',
        'recommendation_text',
        'checked_at',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'checked_at' => 'datetime',
        'temperature' => 'decimal:2',
        'rain_probability' => 'decimal:2',
        'wind_speed' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    // Badge warna untuk UI berdasarkan level rekomendasi
    public function getLevelColorAttribute(): string
    {
        return match ($this->recommendation_level) {
            'aman' => 'green',
            'waspada' => 'yellow',
            'siaga' => 'red',
            default => 'gray',
        };
    }
}
