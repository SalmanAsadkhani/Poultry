<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Miscellaneous extends Model
{

    protected $fillable = [
        'breeding_cycle_id',
        'miscellaneous_category_id',
        'name',
        'quantity',
        'price',
        'description',
        'status',
    ];



    public function miscellaneous_category() :belongsTo
    {
        return $this->belongsTo(MiscellaneousCategory::class , 'miscellaneous_category_id');
    }
}
