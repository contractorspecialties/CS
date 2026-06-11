<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimateItem extends Model
{
    protected $fillable = [
        'estimate_id',
        'description',
        'item_type',
        'quantity',
        'unit_price_cents',
        'total_price_cents',
    ];

    /**
     * Get the parent estimate configuration block.
     */
    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }
}