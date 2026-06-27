<?php

namespace App\Livewire\Traits;

use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


/**
 * Trait untuk menangani pembatasan data (scoping) berdasarkan Role dan Posyandu pengguna.
 */
trait HasPosyanduScope
{
    /**
     * Terapkan scope posyandu ke query builder.
     */
    protected function applyPosyanduScope(Builder $query, ?int $selectedPosyanduId = null): Builder
    {
        /** @var User $user */
        $user = Auth::user();

        // Admin RW / Superadmin
        if ($user->isSuperAdmin()) {
            if ($selectedPosyanduId) {
                if ($query->getModel() instanceof \App\Models\MedicalRecord) {
                    return $query->whereHas('patient', fn ($q) => $q->where('posyandu_id', $selectedPosyanduId));
                }
                return $query->where('posyandu_id', $selectedPosyanduId);
            }
            return $query;
        }

        // Admin/Kader unit specific
        $posyanduId = $selectedPosyanduId ?? $user->posyandu_id;
        
        // Ensure they can only filter to their own unit
        if ($selectedPosyanduId && $selectedPosyanduId != $user->posyandu_id) {
            $posyanduId = $user->posyandu_id;
        }

        if ($query->getModel() instanceof \App\Models\MedicalRecord) {
            return $query->whereHas('patient', fn ($q) => $q->where('posyandu_id', $posyanduId));
        }

        return $query->where('posyandu_id', $posyanduId);
    }

    /**
     * Dapatkan daftar Posyandu yang diizinkan untuk dilihat user (untuk dropdown filter).
     */
    protected function getAllowedPosyandus()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return Posyandu::all();
        }

        return Posyandu::where('id', $user->posyandu_id)->get();
    }
}
