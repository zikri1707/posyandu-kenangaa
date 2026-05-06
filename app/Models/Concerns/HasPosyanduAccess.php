<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait untuk mengelola akses berbasis Posyandu
 */
trait HasPosyanduAccess
{
    /**
     * Scope untuk memfilter berdasarkan akses user
     */
    public function scopeAccessibleBy(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isCoordinator()) {
            return $this->scopeByCoordinator($query, $user);
        }

        return $this->scopeByPosyandu($query, $user);
    }

    /**
     * Scope untuk coordinator (akses berdasarkan pedukuhan)
     */
    protected function scopeByCoordinator(Builder $query, User $user): Builder
    {
        $pedukuhanId = $user->getPedukuhanId();

        if (! $pedukuhanId) {
            return $query->whereNull('id');
        }

        return $query->whereHas('posyandu', function (Builder $q) use ($pedukuhanId) {
            $q->where('pedukuhan_id', $pedukuhanId);
        });
    }

    /**
     * Scope untuk user dengan akses posyandu tunggal
     */
    protected function scopeByPosyandu(Builder $query, User $user): Builder
    {
        if (! $user->posyandu_id) {
            return $query->whereNull('id');
        }

        return $query->where('posyandu_id', $user->posyandu_id);
    }
}
