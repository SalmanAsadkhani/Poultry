<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'expense_category_id',
        'name',
        'quantity',
        'unit_price',
        'description',
        'status',
    ];

    public function expense_category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }


    public function getTotalPriceAttribute(): float|int
    {
        return $this->unit_price * $this->quantity;
    }

}
