@props([
    'headers' => [],
    'sortable' => [],
    'sortField' => null,
    'sortDirection' => null,
    'noResultsMessage' => 'Tidak ada data yang ditemukan',
    'striped' => true,
    'hover' => true,
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $key => $header)
                    <th scope="col" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider 
                               {{ in_array($key, $sortable) ? 'cursor-pointer hover:bg-gray-100' : '' }}"
                        @if(in_array($key, $sortable))
                            wire:click="sortBy('{{ $key }}')"
                        @endif>
                        <div class="flex items-center">
                            {{ $header }}
                            @if(in_array($key, $sortable))
                                @if($sortField === $key)
                                    @if($sortDirection === 'asc')
                                        <span class="ml-1">↑</span>
                                    @else
                                        <span class="ml-1">↓</span>
                                    @endif
                                @else
                                    <span class="ml-1 text-gray-400">↕</span>
                                @endif
                            @endif
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if($slot->isNotEmpty())
                {{ $slot }}
            @else
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        {{ $noResultsMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>