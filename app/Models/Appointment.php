<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasAttachments;

class Appointment extends Model
{
    use HasAttachments;

    // Explicitly enforce the custom prefix mapping pattern
    protected $table = 'sc_appointments';

    protected $fillable = [
        'estimate_id',
        'crew_id',
        'payout_type',
        'payout_cents',
        'status',
        'crew_notes',
        'scheduled_start_at',
        'scheduled_end_at',
    ];

    protected $casts = [
        'scheduled_start_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
    ];

    /**
     * Get the parent project umbrella holding high-level retail financial metrics.
     */
    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class, 'estimate_id');
    }

    /**
     * Get the specific field crew assigned to execute this work order.
     */
    public function crew(): BelongsTo
    {
        return $this->belongsTo(Crew::class, 'crew_id');
    }

    /**
     * Get the quality control checks required to fulfill this appointment.
     */
    public function checkpoints(): HasMany
    {
        return $this->hasMany(Checkpoint::class, 'appointment_id');
    }

    /**
     * Helper accessor to format the clean financial firewall crew payout.
     */
    public function getFormattedPayoutAttribute(): string
    {
        return '$' . number_format($this->payout_cents / 100, 2);
    }
}