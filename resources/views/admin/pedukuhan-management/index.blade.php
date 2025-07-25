@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Pedukuhan
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Pedukuhan</h2>
    <x-button href="{{ route('pedukuhan.create') }}" icon="plus" variant="primary">
        Tambah Pedukuhan
    </x-button>
</div>

<x-card>
    <div class="mb-6">
        <x-search-bar placeholder="Cari pedukuhan..." model="search" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Posyandu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pasien</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pedukuhans as $pedukuhan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        {{ $pedukuhan->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $pedukuhan->posyandus_count }} Posyandu
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $pedukuhan->patients_count }} Pasien
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <x-button href="{{ route('pedukuhan.edit', $pedukuhan->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                            <x-button wire:click="confirmDelete({{ $pedukuhan->id }})" variant="danger" size="sm" icon="trash">
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pedukuhans->links() }}
    </div>
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmPedukuhanDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus pedukuhan ini? Semua data terkait akan terpengaruh.</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deletePedukuhan" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection