<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, LogsActivity, Notifiable;

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
        'posyandu_id',
        'is_active',
        'verified_email',
        'attempt_login',
        'block_expires',
        'email_verified_at',
        'last_notifications_read_at',
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
        'last_notifications_read_at' => 'datetime',
    ];

    /**
     * Role constants
     */
    const ROLE_SUPERADMIN = 'superadmin';

    const ROLE_ADMIN = 'admin';

    const ROLE_COORDINATOR = 'coordinator';

    const ROLE_KADER = 'kader';

    /**
     * Get available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_COORDINATOR,
            self::ROLE_KADER,
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCoordinator(): bool
    {
        return $this->role === self::ROLE_COORDINATOR;
    }

    /**
     * Get pedukuhan_id dari posyandu user
     */
    public function getPedukuhanId(): ?int
    {
        return $this->posyandu?->pedukuhan_id;
    }

    public function isKader(): bool
    {
        return $this->role === self::ROLE_KADER;
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
     * Get unit-specific display role name (e.g., admin1, kader2)
     */
    public function getDisplayRoleNameAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'superadmin';
        }

        $unitSuffix = '';
        if ($this->posyandu_id == 3) {
            $unitSuffix = '1';
        } elseif ($this->posyandu_id == 2) {
            $unitSuffix = '2';
        }

        return $this->role.$unitSuffix;
    }

    /**
     * Relationship with Posyandu (belongsTo - user assigned to one posyandu)
     */
    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    /**
     * Relationship with Posyandu (hasMany - for backwards compatibility)
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
     * Check if the user is active
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Check if the user is currently blocked from logging in
     */
    public function isBlocked(): bool
    {
        return $this->block_expires && now()->lessThan($this->block_expires);
    }

    /**
     * Get remaining block minutes
     */
    public function getRemainingBlockMinutes(): int
    {
        if (! $this->isBlocked()) {
            return 0;
        }

        return now()->diffInMinutes($this->block_expires);
    }

    /**
     * Clear the block if it has expired
     */
    public function unlockIfExpired(): void
    {
        if ($this->block_expires && now()->greaterThanOrEqualTo($this->block_expires)) {
            $this->update([
                'block_expires' => null,
                'attempt_login' => 0,
            ]);
        }
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

    /**
     * Scope a query to apply standard filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });

        $query->when($filters['role'] ?? false, function ($q, $role) {
            $q->where('role', $role);
        });

        $status = $filters['status'] ?? null;
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        return $query;
    }
}
