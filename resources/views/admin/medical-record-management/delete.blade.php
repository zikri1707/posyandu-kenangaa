@extends('layouts.modal-layout')

@section('title')
    Konfirmasi Penghapusan Rekam Medis
@endsection

@section('content')
<div class="p-6">
    <div class="text-center">
        <x-icon name="exclamation-triangle" class="mx-auto w-12 h-12 text-red-500" />
        <h3 class="mt-2 text-lg font-medium text-gray-900">Konfirmasi Penghapusan Rekam Medis</h3>
        <div class="mt-2 text-sm text-gray-500">
            <p>Apakah Anda yakin ingin menghapus rekam medis untuk pasien <span class="font-semibold">{{ $medicalRecord->patient->name }}</span>?</p>
            <p class="mt-1">Data yang dihapus tidak dapat dikembalikan.</p>
        </div>
    </div>
    
    <div class="mt-5 flex justify-center space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <form method="POST" action="{{ route('medical-records.destroy', $medicalRecord->id) }}">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger" icon="trash">Hapus Rekam Medis</x-button>
        </form>
    </div>
</div>
@endsection