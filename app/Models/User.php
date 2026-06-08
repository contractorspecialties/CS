<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Including structural SaaS metrics & registration identifiers.
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
        ];
    }

    /**
     * RELATIONSHIP: Polymorphic / Internal Clients.
     * Tracks consumer roster records assigned to this contractor node.
     */
    public function clients(): HasMany
    {
        // Eloquent dynamically hooks 'user_id' resolving custom 'sc_' table contexts automatically
        return $this->hasMany(User::class, 'parent_id'); 
    }

    /**
     * RELATIONSHIP: Project Estimates & Proposals (CPP Suite Asset).
     */
    public function quotes(): HasMany
    {
        // Placeholder linking layout referencing your core SaaS estimating tool metrics table
        return $this->hasMany(Quote::class);
    }

    /**
     * RELATIONSHIP: Calendar Bookings & Dispatches.
     */
    public function appointments(): HasMany
    {
        // Placeholder linking layout referencing your task tracking table
        return $this->hasMany(Appointment::class);
    }
}