<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'is_active',
        'verified_email',
        'attempt_login',
        'block_expires',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'verified_email' => 'boolean',
        'block_expires' => 'datetime',
    ];

    /**
     * Role constants
     */
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_COORDINATOR = 'coordinator';
    const ROLE_STAFF = 'staff';
    const ROLE_MEDICAL = 'medical';
    const ROLE_PATIENT = 'patient';
    const ROLE_PARTNER = 'partner';

    /**
     * Get available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_COORDINATOR,
            self::ROLE_STAFF,
            self::ROLE_MEDICAL,
            self::ROLE_PATIENT,
            self::ROLE_PARTNER,
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is SuperAdmin
     */    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is Coordinator
     */
    public function isCoordinator(): bool
    {
        return $this->role === self::ROLE_COORDINATOR;
    }

    /**
     * Check if user is Staff
     */
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Check if user is Medical
     */
    public function isMedical(): bool
    {
        return $this->role === self::ROLE_MEDICAL;
    }

    /**
     * Check if user is Patient
     */
    public function isPatient(): bool
    {
        return $this->role === self::ROLE_PATIENT;
    }

    /**
     * Check if user is Partner
     */
    public function isPartner(): bool
    {
        return $this->role === self::ROLE_PARTNER;
    }

    /**
     * Get the user's initials
     */
    public function getInitialsAttribute(): string
    {
        return Str::upper(
            Str::of($this->name)
                ->explode(' ')
                ->take(2)
                ->map(fn ($word) => Str::substr($word, 0, 1))
                ->implode('')
        );
    }

    /**
     * Relationship with Posyandu
     */
    public function posyandus()
    {
        return $this->hasMany(Posyandu::class);
    }

    /**
     * Relationship with Schedule
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Relationship with Gallery
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Relationship with Article
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Relationship with MedicalRecord
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        // Ensure default values are set when creating or retrieving a user
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = self::ROLE_ADMIN;
            }
            if (is_null($user->is_active)) {
                $user->is_active = true;
            }
            if (is_null($user->verified_email)) {
                $user->verified_email = false;
            }
            if (is_null($user->attempt_login)) {
                $user->attempt_login = 0;
            }
        });

        // Ensure default role is set when retrieving an existing user
        static::retrieved(function ($user) {
            if (empty($user->role)) {
                $user->role = self::ROLE_ADMIN;
            }
        });
    }
}
