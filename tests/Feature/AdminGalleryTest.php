<?php

use App\Models\Gallery;
use App\Models\GalleryFolder;
use App\Models\Pedukuhan;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('superadmin can access admin gallery index', function () {
    $pedukuhan = Pedukuhan::factory()->create();
    $posyandu = Posyandu::factory()->create(['pedukuhan_id' => $pedukuhan->id]);

    $superadmin = User::factory()->create([
        'role' => 'superadmin',
        'posyandu_id' => null,
    ]);

    $this->actingAs($superadmin);
    $response = $this->get('/admin/gallery');

    $response->assertStatus(200);
});

test('can create a new gallery folder', function () {
    Storage::fake('public');

    $pedukuhan = Pedukuhan::factory()->create();
    $posyandu = Posyandu::factory()->create(['pedukuhan_id' => $pedukuhan->id]);

    $admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $posyandu->id,
    ]);

    $coverPhoto = UploadedFile::fake()->image('cover.jpg');

    $this->actingAs($admin);
    $response = $this->post('/admin/gallery', [
        'name' => 'Folder Imunisasi 2026',
        'description' => 'Dokumentasi imunisasi balita',
        'posyandu_id' => $posyandu->id,
        'cover_photo' => $coverPhoto,
    ]);

    $response->assertRedirect('/admin/gallery');

    $folder = GalleryFolder::latest()->first();
    expect($folder->name)->toBe('Folder Imunisasi 2026');
    expect($folder->description)->toBe('Dokumentasi imunisasi balita');
});

test('can upload image file to folder and it is saved as type image', function () {
    Storage::fake('public');

    $pedukuhan = Pedukuhan::factory()->create();
    $posyandu = Posyandu::factory()->create(['pedukuhan_id' => $pedukuhan->id]);

    $admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $posyandu->id,
    ]);

    $folder = GalleryFolder::create([
        'name' => 'Folder Imunisasi',
        'description' => 'Deskripsi',
        'posyandu_id' => $posyandu->id,
        'user_id' => $admin->id,
    ]);

    $file = UploadedFile::fake()->image('kegiatan.jpg');

    $this->actingAs($admin);
    $response = $this->post("/admin/gallery/{$folder->id}/media", [
        'title' => 'Kegiatan Posyandu',
        'description' => 'Edukasi kesehatan anak',
        'photos' => [$file],
    ]);

    $response->assertRedirect("/admin/gallery/{$folder->id}");

    $gallery = Gallery::latest()->first();
    expect($gallery->type)->toBe('image');
    expect($gallery->gallery_folder_id)->toBe($folder->id);
    expect($gallery->photo)->not->toBeNull();
    Storage::disk('public')->assertExists($gallery->photo);
});

test('can upload video file to folder and it is saved as type video', function () {
    Storage::fake('public');

    $pedukuhan = Pedukuhan::factory()->create();
    $posyandu = Posyandu::factory()->create(['pedukuhan_id' => $pedukuhan->id]);

    $admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $posyandu->id,
    ]);

    $folder = GalleryFolder::create([
        'name' => 'Folder Imunisasi',
        'description' => 'Deskripsi',
        'posyandu_id' => $posyandu->id,
        'user_id' => $admin->id,
    ]);

    $file = UploadedFile::fake()->create('kegiatan.mp4', 500, 'video/mp4');

    $this->actingAs($admin);
    $response = $this->post("/admin/gallery/{$folder->id}/media", [
        'title' => 'Video Kegiatan Posyandu',
        'description' => 'Rekaman imunisasi rutin',
        'photos' => [$file],
    ]);

    $response->assertRedirect("/admin/gallery/{$folder->id}");

    $gallery = Gallery::latest()->first();
    expect($gallery->type)->toBe('video');
    expect($gallery->gallery_folder_id)->toBe($folder->id);
    expect($gallery->photo)->not->toBeNull();
    Storage::disk('public')->assertExists($gallery->photo);
});
