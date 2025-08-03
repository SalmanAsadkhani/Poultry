<?php

namespace App\Models;

use Hekmatinasser\Verta\Facades\Verta;
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


    public function getDailyDateAttribute()
    {
        $value = $this->attributes['date'];
        return \verta($value)->format('Y/m/d');
    }

    public function feedConsumptions()
    {
        return $this->hasMany(FeedConsumption::class);
    }

}

