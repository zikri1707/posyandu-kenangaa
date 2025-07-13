<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Posyandu;
use App\Http\Requests\GalleryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class GalleryController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $galleries = Gallery::with(['posyandu', 'user'])->latest()->get();
        return view('admin.gallery-management.index', compact('galleries'));
    }

    public function create()
    {
        $posyandus = Posyandu::all();
        return view('admin.gallery-management.create', compact('posyandus'));
    }

    public function store(GalleryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('galleries');
        }

        Gallery::create($data);

        return redirect()->route('galleries.index')
            ->with('success', 'Foto berhasil diupload');
    }

    public function show(Gallery $gallery)
    {
        return view('admin.gallery-management.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $posyandus = Posyandu::all();
        return view('admin.gallery-management.edit', compact('gallery', 'posyandus'));
    }

    public function update(GalleryRequest $request, Gallery $gallery)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            Storage::delete($gallery->photo);
            $data['photo'] = $request->file('photo')->store('galleries');
        }

        $gallery->update($data);

        return redirect()->route('galleries.index')
            ->with('success', 'Foto berhasil diupdate');
    }

    public function destroy(Gallery $gallery)
    {
        Storage::delete($gallery->photo);
        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus');
    }
}