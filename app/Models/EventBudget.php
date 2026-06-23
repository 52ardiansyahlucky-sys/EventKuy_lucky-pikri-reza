<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'item_name',
        'category',
        'quantity',
        'unit_price',
        'subtotal',
        'notes',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
