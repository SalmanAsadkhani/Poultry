<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'date',
        'days_number',
        'mortality_count',
        'feed_count',
        'total_mortality',
        'description',
        'feed_type',
        'actions_taken',
        'status',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(BreedingCycle::class, 'breeding_cycle_id');
    }



}

