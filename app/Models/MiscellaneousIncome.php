<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiscellaneousIncome extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'misc_categories_id',
        'name',
        'quantity',
        'price',
        'description',
        'status',
    ];


    public function miscellaneous_income_category() :belongsTo
    {
        return $this->belongsTo(FeedCategory::class , 'misc_categories_id');
    }


    public function cycle():belongsTo
    {
        return $this->belongsTo(BreedingCycle::class , 'breeding_cycle_id');
    }

}
