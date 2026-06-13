<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentException extends Model
{
    // Explicitly map to our custom prefix table
    protected $table = 'sc_appointment_exceptions';

    protected $fillable = [
        'recurrence_template_id',
        'exception_type', // 'skip' or 'reschedule'
        'original_date',
        'rescheduled_start_at',
        'rescheduled_end_at',
        'reason_notes',
    ];

    protected $casts = [
        'original_date' => 'date',
        'rescheduled_start_at' => 'datetime',
        'rescheduled_end_at' => 'datetime',
    ];

    /**
     * Get the parent repeating schedule rule this change applies to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(RecurrenceTemplate::class, 'recurrence_template_id');
    }
}