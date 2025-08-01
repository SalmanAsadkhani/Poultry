<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drug extends Model
{

    protected $fillable = [
        'breeding_cycle_id',
        'drug_category_id',
        'name',
        'quantity',
        'price',
        'description',
        'status',
    ];


    public function drug_category() :belongsTo
    {
        return $this->belongsTo(DrugCategory::class , 'drug_category_id');
    }

}
