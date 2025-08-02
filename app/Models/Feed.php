<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feed extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'feed_category_id',
        'name',
        'quantity',
        'bag_count',
        'price',
        'description',
        'status',
    ];


    public function feed_category() :belongsTo
    {
        return $this->belongsTo(FeedCategory::class , 'feed_category_id');
    }


    public function cycle():belongsTo
    {
        return $this->belongsTo(BreedingCycle::class , 'breeding_cycle_id');
    }
}
