<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_gc',
        'is_restricted',
        'business_name',
        'slogan',
        'company_website',
        'logo_path',
        'theme_color',
        'magic_link_token',
        'magic_link_expires_at',
        
        // SEO & Location Fields
        'specialty_id',
        'slug',
        'city',
        'state',
        'bio',
        'phone',

        // Trust & Credibility Fields
        'license_number',
        'established_year',
        'is_insured',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'magic_link_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'magic_link_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_gc' => 'boolean',
            'is_restricted' => 'boolean',
            'is_insured' => 'boolean', // Ensures this always returns a clear true/false
        ];
    }

    /**
     * RELATIONSHIP: Trade Categorization
     * Maps this contractor to their primary public directory trade sector.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    /**
     * RELATIONSHIP: Internal Clients
     * Tracks consumer roster records assigned to this contractor node.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id'); 
    }

    // CPP Tool Suite relationship hooks commented out until database tables are migrated
    // public function quotes() { return $this->hasMany(Quote::class); }
    // public function appointments() { return $this->hasMany(Appointment::class); }
}