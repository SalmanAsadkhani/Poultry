<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChickenSales extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'chicken_categories_id',
        'name',
        'quantity',
        'weight',
        'price',
        'description',
        'status',
    ];


    public function ChickenSales_category() :belongsTo
    {
        return $this->belongsTo(FeedCategory::class , 'chicken_categories_id');
    }


    public function cycle():belongsTo
    {
        return $this->belongsTo(BreedingCycle::class , 'breeding_cycle_id');
    }

}
