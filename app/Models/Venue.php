<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'address',
        'city',
        'latitude',
        'longitude',
        'capacity',
        'rental_price',
        'photo',
        'facilities',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rental_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_venue')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function weatherReports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WeatherReport::class);
    }

    // Helper untuk Mhs 3: cek apakah venue ini punya koordinat lengkap
    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    // URL foto, fallback ke placeholder kalau belum ada foto
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://placehold.co/600x400?text=No+Photo';
    }
}
