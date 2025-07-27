@extends('layouts.modal-layout')

@section('title')
    Konfirmasi Penghapusan Pasien
@endsection

@section('content')
<div class="p-6">
    <div class="text-center">
        <x-icon name="exclamation-triangle" class="mx-auto w-12 h-12 text-red-500" />
        <h3 class="mt-2 text-lg font-medium text-gray-900">Konfirmasi Penghapusan Pasien</h3>
        <div class="mt-2 text-sm text-gray-500">
            <p>Apakah Anda yakin ingin menghapus pasien <span class="font-semibold">{{ $patient->name }}</span>?</p>
            <p class="mt-1">Semua data terkait termasuk catatan medis akan dihapus secara permanen.</p>
        </div>
    </div>
    
    <div class="mt-5 flex justify-center space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <form method="POST" action="{{ route('patients.destroy', $patient->id) }}">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger" icon="trash">Hapus Pasien</x-button>
        </form>
    </div>
</div>
@endsection