<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'user_id',
        'attachable_type',
        'attachable_id',
        'file_path',
        'file_type',
        'is_public', // Registered for fillable configuration mass-assignment
        'canvas_metadata',
    ];

    protected $casts = [
        'is_public' => 'boolean', // Forced to explicit boolean values
        'canvas_metadata' => 'array',
    ];

    /**
     * Resolve the parent polymorphic data model instance.
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Programmatically generate secure, authenticated absolute CDN addresses for front-end rendering engines.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}