@extends('layouts.admin-layout')

@section('admin-title')
    Edit Data Pasien
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Data Pasien</h2>
    <x-breadcrumb :items="[
        ['label' => 'Pasien', 'url' => route('patients.index')],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updatePatient">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-6">
                <!-- Foto Profil -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <div class="flex items-center space-x-4">
                        <x-avatar :src="$photoPreview ?? $patient->photo_url" size="xl" class="rounded-lg" />
                        <div>
                            <input type="file" wire:model="photo" class="hidden" id="photoUpload">
                            <label for="photoUpload" class="cursor-pointer">
                                <x-button variant="outline" type="button" icon="photo">Ubah Foto</x-button>
                            </label>
                            @if($photo)
                                <x-button wire:click="removePhoto" variant="danger-outline" size="sm" icon="trash" class="mt-2">
                                    Hapus Foto
                                </x-button>
                            @endif
                            @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Data Diri -->
                <x-input label="Nama Lengkap" wire:model.defer="name" placeholder="Nama pasien" required />
                <x-input label="NIK" wire:model.defer="nik" placeholder="Nomor Induk Kependudukan" required />
                <x-input label="Tempat Lahir" wire:model.defer="birth_place" placeholder="Tempat lahir" required />
                <x-input label="Tanggal Lahir" type="date" wire:model.defer="birth_date" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-select label="Jenis Kelamin" wire:model.defer="gender" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" @selected($gender === 'L')>Laki-laki</option>
                        <option value="P" @selected($gender === 'P')>Perempuan</option>
                    </x-select>
                    
                    <x-input label="Golongan Darah" wire:model.defer="blood_type" placeholder="A/B/AB/O" />
                </div>
            </div>

            <!-- Kolom 2 -->
            <div class="space-y-6">
                <!-- Kontak -->
                <x-input label="Nomor Telepon" wire:model.defer="phone" placeholder="0812..." type="tel" required />
                <x-input label="Email" wire:model.defer="email" placeholder="email@contoh.com" type="email" />
                <x-textarea label="Alamat" wire:model.defer="address" placeholder="Alamat lengkap" rows="3" required />
                
                <!-- Data Posyandu -->
                <x-select label="Pedukuhan" wire:model.defer="pedukuhan_id" required>
                    <option value="">Pilih Pedukuhan</option>
                    @foreach($pedukuhans as $pedukuhan)
                        <option value="{{ $pedukuhan->id }}" @selected($pedukuhan_id == $pedukuhan->id)>
                            {{ $pedukuhan->name }}
                        </option>
                    @endforeach
                </x-select>
                
                <x-select label="Posyandu" wire:model.defer="posyandu_id" required>
                    <option value="">Pilih Posyandu</option>
                    @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}" @selected($posyandu_id == $posyandu->id)>
                            {{ $posyandu->name }}
                        </option>
                    @endforeach
                </x-select>
                
                <x-switch label="Status Aktif" wire:model.defer="is_active" />
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('patients.index') }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Update Pasien</x-button>
        </div>
    </form>
</x-card>
@endsection