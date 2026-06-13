<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasAttachments;

class Estimate extends Model
{
    use HasAttachments;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_email',
        'client_phone',
        'project_title',
        'project_description',
        'customer_notes', 
        'subtotal_cents',
        'tax_cents',
        'total_cents',
        'status',
        'secure_token',
    ];

    /**
     * Get the contractor that owns this estimate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the itemized retail line rows attached to this estimate.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EstimateItem::class);
    }

    /**
     * Get the multi-crew work orders or service appointments tracked under this project umbrella.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the repeating schedule rules established under this project canvas.
     */
    public function recurrenceTemplates(): HasMany
    {
        return $this->hasMany(RecurrenceTemplate::class, 'estimate_id');
    }
}