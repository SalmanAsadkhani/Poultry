<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedCategory extends Model
{
    protected $fillable = ['breeding_cycle_id' , 'name' , 'status'];


    public function cycle(): BelongsTo
    {
        return $this->belongsTo(BreedingCycle::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }
}
