<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function feedCategories()
    {
        return $this->hasMany(FeedCategory::class);
    }

    public function drugCategories()
    {
        return $this->hasMany(DrugCategory::class);
    }

    public function miscellaneousCategories()
    {
        return $this->hasMany(MiscellaneousCategory::class);
    }
}
