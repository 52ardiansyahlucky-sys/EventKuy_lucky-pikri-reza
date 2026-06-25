<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'event_date',
        'total_budget',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_budget' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rundowns(): HasMany
    {
        return $this->hasMany(EventRundown::class)->orderBy('order');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(EventBudget::class);
    }

    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'event_venue')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function weatherReports(): HasMany
    {
        return $this->hasMany(WeatherReport::class);
    }

    // Helper: total anggaran terpakai (sum dari semua budget item)
    public function getUsedBudgetAttribute(): float
    {
        return $this->budgets->sum('subtotal');
    }
}
