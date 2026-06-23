<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRundown extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'activity',
        'start_time',
        'end_time',
        'person_in_charge',
        'notes',
        'order',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
