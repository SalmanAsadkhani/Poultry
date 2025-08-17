<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MiscellaneousIncomeCategory extends Model
{
    protected $fillable = ['breeding_cycle_id' , 'name' , 'status'];


    public function cycle(): BelongsTo
    {
        return $this->belongsTo(BreedingCycle::class);
    }

    public function miscellaneous_incomes(): HasMany
    {
        return $this->hasMany(MiscellaneousIncome::class ,'misc_categories_id' );
    }



}
