<?php

namespace App\Livewire\Traits;

use App\Models\Posyandu;
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
    protected function applyPosyanduScope(Builder $query, string $patientIdColumn = 'id'): Builder
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isCoordinator()) {
            return $query;
        }

        // Default: Admin/Kader hanya melihat posyandu mereka
        if ($query->getModel() instanceof \App\Models\MedicalRecord) {
            return $query->whereHas('patient', fn ($q) => $q->where('posyandu_id', $user->posyandu_id));
        }

        return $query->where('posyandu_id', $user->posyandu_id);
    }

    /**
     * Dapatkan daftar Posyandu yang diizinkan untuk dilihat user (untuk dropdown filter).
     */
    protected function getAllowedPosyandus()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isCoordinator()) {
            return Posyandu::all();
        }

        return Posyandu::where('id', $user->posyandu_id)->get();
    }
}
