<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
    /**
     * Create a new gallery entry.
     */
    public function createGallery(array $data, User $user): Gallery
    {
        if (! $user->isSuperAdmin()) {
            $data['posyandu_id'] = $user->posyandu_id;
        }

        $data['user_id'] = $user->id;

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $data['photo']->store('galleries', 'public');
        }

        return Gallery::create($data);
    }

    /**
     * Update an existing gallery entry.
     */
    public function updateGallery(Gallery $gallery, array $data): Gallery
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if ($gallery->photo) {
                Storage::disk('public')->delete($gallery->photo);
            }
            $data['photo'] = $data['photo']->store('galleries', 'public');
        }

        $gallery->update($data);

        return $gallery;
    }

    /**
     * Delete a gallery entry.
     */
    public function deleteGallery(Gallery $gallery): void
    {
        if ($gallery->photo) {
            Storage::disk('public')->delete($gallery->photo);
        }
        $gallery->delete();
    }
}
