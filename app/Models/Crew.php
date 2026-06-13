<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crew extends Model
{
    // Explicitly enforce the custom prefix mapping pattern
    protected $table = 'sc_crews';

    protected $fillable = [
        'user_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the business administrator that owns this crew resource.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all appointments or work orders assigned to this specific crew track.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'crew_id');
    }
}