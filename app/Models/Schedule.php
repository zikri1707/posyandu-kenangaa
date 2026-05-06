<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'posyandu_id', 'user_id', 'title', 'description', 'start_time', 'end_time', 'location', 'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationship with Posyandu
    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk pencarian berdasarkan judul atau lokasi.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter schedules based on User role access
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAccessibleBy($query, $user)
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isCoordinator()) {
            $pedukuhanId = Posyandu::find($user->posyandu_id)?->pedukuhan_id;
            if ($pedukuhanId) {
                // Get all posyandu IDs in this pedukuhan
                $ids = Posyandu::where('pedukuhan_id', $pedukuhanId)->pluck('id');

                return $query->whereIn('posyandu_id', $ids);
            }
        }

        // Default or other roles just see their own posyandu
        return $query->where('posyandu_id', $user->posyandu_id);
    }
}
