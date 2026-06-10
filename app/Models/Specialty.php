<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    protected $fillable = ['name', 'slug', 'icon'];

    /**
     * Fetch all verified user profiles categorized under this programmatic trade sector.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'specialty_id')
                    ->where('is_restricted', false)
                    ->whereNotNull('slug');
    }
}