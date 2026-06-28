<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GalleryFolder;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryFolderController extends Controller
{
    /**
     * Show form to create a new gallery folder.
     */
    public function create()
    {
        $posyandus = Posyandu::all();
        return view('livewire.admin.gallery-management.create_folder', compact('posyandus'));
    }

    /**
     * Store a newly created gallery folder.
     */
    public function store(Request $request)
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'posyandu_id'  => $isSuperAdmin ? 'required|exists:posyandus,id' : 'nullable|exists:posyandus,id',
            'cover_photo'  => 'required|image|max:10240', // Maks 10MB, wajib diisi
        ], [
            'cover_photo.required' => 'Foto sampul folder wajib diunggah.',
            'posyandu_id.required' => 'Unit Posyandu wajib dipilih.',
        ]);

        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }
        $validated['user_id'] = $user->id;

        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('gallery_covers', 'public');
        }

        GalleryFolder::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Folder galeri berhasil dibuat.');
    }

    /**
     * Display the specified folder and its contents.
     */
    public function show(GalleryFolder $folder)
    {
        // Membatasi akses folder berdasarkan hak akses kader posyandu
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        $galleries = $folder->galleries()->latest()->paginate(12);

        return view('livewire.admin.gallery-management.show', compact('folder', 'galleries'));
    }

    /**
     * Show form to edit the specified folder.
     */
    public function edit(GalleryFolder $folder)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        $posyandus = Posyandu::all();
        return view('livewire.admin.gallery-management.edit_folder', compact('folder', 'posyandus'));
    }

    /**
     * Update the specified folder.
     */
    public function update(Request $request, GalleryFolder $folder)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'cover_photo' => 'nullable|image|max:10240',
        ]);

        if (!$user->isSuperAdmin()) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        if ($request->hasFile('cover_photo')) {
            if ($folder->cover_photo) {
                Storage::disk('public')->delete($folder->cover_photo);
            }
            $validated['cover_photo'] = $request->file('cover_photo')->store('gallery_covers', 'public');
        }

        $folder->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Folder galeri berhasil diperbarui.');
    }

    /**
     * Delete the specified folder and its contents.
     */
    public function destroy(GalleryFolder $folder)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $folder->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        // Hapus cover photo folder jika ada
        if ($folder->cover_photo) {
            Storage::disk('public')->delete($folder->cover_photo);
        }

        // Hapus semua media fisik yang ada di dalam folder tersebut
        foreach ($folder->galleries as $gallery) {
            if ($gallery->photo) {
                Storage::disk('public')->delete($gallery->photo);
            }
        }

        // Hapus data folder (ini akan memicu cascade delete pada data di tabel galleries)
        $folder->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Folder galeri beserta seluruh isinya berhasil dihapus.');
    }
}
