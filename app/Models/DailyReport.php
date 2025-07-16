<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'date',
        'days_number',
        'mortality_count',
        'description',
        'feed_type',
        'actions_taken',
        'status',
    ];

    public function cycle()
    {
        return $this->belongsTo(BreedingCycle::class, 'breeding_cycle_id');
    }
}
