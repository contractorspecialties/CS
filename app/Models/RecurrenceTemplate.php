<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurrenceTemplate extends Model
{
    protected $table = 'sc_recurrence_templates';

    protected $fillable = [
        'user_id',
        'estimate_id',
        'crew_id',
        'frequency',
        'day_of_week',
        'payout_cents',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'day_of_week' => 'integer',
    ];

    /**
     * Get the customer job or quote details linked to this repeating loop.
     */
    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class, 'estimate_id');
    }

    /**
     * Get the default crew resource assigned to this recurring route.
     */
    public function crew(): BelongsTo
    {
        return $this->belongsTo(Crew::class, 'crew_id');
    }

    /**
     * Get all active schedule exceptions, skips, or modifications logged against this blueprint.
     */
    public function exceptions(): HasMany
    {
        return $this->hasMany(AppointmentException::class, 'recurrence_template_id');
    }
}