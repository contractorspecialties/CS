<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'project_title',
        'scheduled_date',
        'start_time',
        'crew_notes',
        'status',
    ];

    /**
     * Get the contractor managing this schedule assignment block.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}