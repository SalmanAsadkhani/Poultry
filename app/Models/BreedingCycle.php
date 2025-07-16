<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreedingCycle extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'chicken_count',
        'chicken_price',
        'notes',
        'status',
    ];

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
}
