<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    /**
     * The fields that are allowed to be filled out or updated.
     */
    protected $fillable = [
        'name', 
        'slug', 
        'icon',
        'category',
        'aliases',
        'operational_type',
        'is_regulated',
        'sort_order',
        'is_active',
    ];

    /**
     * Automatically format specific fields when saving or reading from the database.
     */
    protected function casts(): array
    {
        return [
            'aliases' => 'array',         // Converts the lists back and forth from text to a PHP array
            'is_regulated' => 'boolean', // Changes 1 and 0 back to true and false
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Fetch all verified user profiles categorized under this trade sector.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'specialty_id')
                    ->where('is_restricted', false)
                    ->whereNotNull('slug');
    }
}