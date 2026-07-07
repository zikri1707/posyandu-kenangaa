<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\GalleryFolder;
use App\Services\GalleryService;

class GalleryController extends Controller
{
    /**
     * Show form to upload new media into a folder.
     */
    public function create(GalleryFolder $folder)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        return view('livewire.admin.gallery-management.create', compact('folder'));
    }

    /**
     * Store uploaded media into a folder.
     */
    public function store(GalleryRequest $request, GalleryFolder $folder, GalleryService $galleryService)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        $request->validated();
        $files = $request->file('photos');
        $count = count($files);

        foreach ($files as $index => $file) {
            // Tentukan judul media
            if ($request->filled('title')) {
                $title = $count > 1 ? $request->input('title').' ('.($index + 1).')' : $request->input('title');
            } else {
                $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }

            $data = [
                'gallery_folder_id' => $folder->id,
                'posyandu_id' => $folder->posyandu_id,
                'title' => $title,
                'description' => $request->input('description'),
                'photo' => $file,
            ];

            $galleryService->createGallery($data, $user);
        }

        return redirect()->route('admin.gallery.show', $folder->id)->with('success', $count.' media berhasil ditambahkan ke folder.');
    }

    /**
     * Update media inside a folder.
     */
    public function update(Request $request, GalleryFolder $folder, Gallery $gallery)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        if ($gallery->gallery_folder_id !== $folder->id) {
            abort(404, 'Media tidak ditemukan di folder ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $gallery->update($validated);

        return redirect()->route('admin.gallery.show', $folder->id)->with('success', 'Media berhasil diperbarui.');
    }

    /**
     * Delete media inside a folder.
     */
    public function destroy(GalleryFolder $folder, Gallery $gallery, GalleryService $galleryService)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        // Pastikan media memang berada di dalam folder tersebut
        if ($gallery->gallery_folder_id !== $folder->id) {
            abort(404, 'Media tidak ditemukan di folder ini.');
        }

        $galleryService->deleteGallery($gallery);

        return redirect()->route('admin.gallery.show', $folder->id)->with('success', 'Media berhasil dihapus.');
    }
}
