<?php

namespace App\Services;

use App\Models\Posyandu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PosyanduService
{
    /**
     * Create a new posyandu.
     */
    public function createPosyandu(array $data): Posyandu
    {
        if (isset($data['logo_photo']) && $data['logo_photo'] instanceof UploadedFile) {
            $data['logo_photo'] = $data['logo_photo']->store('posyandu', 'public');
        }

        return Posyandu::create($data);
    }

    /**
     * Update an existing posyandu.
     */
    public function updatePosyandu(Posyandu $posyandu, array $data): Posyandu
    {
        if (isset($data['logo_photo']) && $data['logo_photo'] instanceof UploadedFile) {
            if ($posyandu->logo_photo) {
                Storage::disk('public')->delete($posyandu->logo_photo);
            }
            $data['logo_photo'] = $data['logo_photo']->store('posyandu', 'public');
        }

        $posyandu->update($data);

        return $posyandu;
    }

    /**
     * Delete a posyandu.
     */
    public function deletePosyandu(Posyandu $posyandu): void
    {
        if ($posyandu->logo_photo) {
            Storage::disk('public')->delete($posyandu->logo_photo);
        }
        $posyandu->delete();
    }
}
