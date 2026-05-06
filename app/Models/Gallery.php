<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'posyandu_id', 'user_id', 'title', 'description', 'photo', 'type',
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
     * Scope to filter galleries based on User role access
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
                $ids = Posyandu::where('pedukuhan_id', $pedukuhanId)->pluck('id');

                return $query->whereIn('posyandu_id', $ids);
            }
        }

        return $query->where('posyandu_id', $user->posyandu_id);
    }
}
