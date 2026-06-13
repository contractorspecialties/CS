<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone'
    ];

    /**
     * Get the contractor profile that owns this customer record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the complete historical proposal deck issued to this account.
     */
    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class)->where('status', '!=', 'archived');
    }

    /**
     * Get the billing statements registered under this customer footprint.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->where('status', '!=', 'archived');
    }
}