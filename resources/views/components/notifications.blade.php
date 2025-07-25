@props([
    'notifications' => [],
    'unreadCount' => 0
])

<div x-data="{ open: false }" class="relative">
    <!-- Notification button -->
    <button @click="open = !open" class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
        <x-icon name="bell" class="h-6 w-6" />
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        @endif
    </button>
    
    <!-- Notification dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none z-50">
        <div class="px-4 py-3">
            <p class="text-sm font-medium text-gray-900">Notifikasi</p>
            @if($unreadCount > 0)
                <p class="text-xs text-gray-500 mt-1">Anda memiliki {{ $unreadCount }} notifikasi belum dibaca</p>
            @endif
        </div>
        
        <div class="py-1 max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification['url'] }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-icon :name="$notification['icon']" class="h-5 w-5 {{ $notification['read'] ? 'text-gray-400' : 'text-blue-500' }}" />
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="{{ $notification['read'] ? 'text-gray-600' : 'font-medium text-gray-900' }}">{{ $notification['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification['time'] }}</p>
                        </div>
                        @if(!$notification['read'])
                            <div class="flex-shrink-0 ml-2">
                                <span class="inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-4 py-3 text-center text-sm text-gray-500">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>
        
        <div class="px-4 py-2 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                Lihat semua notifikasi
            </a>
        </div>
    </div>
</div>