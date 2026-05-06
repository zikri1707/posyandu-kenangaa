<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\Posyandu;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::accessibleBy(auth()->user())->paginate(10);

        return view('livewire.admin.gallery-management.index', compact('galleries'));
    }

    public function create()
    {
        $posyandus = Posyandu::all();

        return view('livewire.admin.gallery-management.create', compact('posyandus'));
    }

    public function store(GalleryRequest $request, \App\Services\GalleryService $galleryService)
    {
        $galleryService->createGallery($request->validated(), auth()->user());

        return redirect()->route('admin.gallery.index')->with('success', 'Foto galeri berhasil diunggah.');
    }

    public function show(Gallery $gallery)
    {
        return view('livewire.admin.gallery-management.details', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $posyandus = Posyandu::all();

        return view('livewire.admin.gallery-management.update', compact('gallery', 'posyandus'));
    }

    public function update(GalleryRequest $request, Gallery $gallery, \App\Services\GalleryService $galleryService)
    {
        $galleryService->updateGallery($gallery, $request->validated());

        return redirect()->route('admin.gallery.index')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery, \App\Services\GalleryService $galleryService)
    {
        $galleryService->deleteGallery($gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
