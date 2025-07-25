<nav class="bg-white border-b border-gray-200 px-4 py-2.5">
    <div class="flex flex-wrap justify-between items-center">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
            <x-icon name="bars-3" class="w-6 h-6" />
        </button>
        
        <!-- Search -->
        <div class="flex items-center space-x-4">
            @include('components.search-bar')
        </div>
        
        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 relative">
                <x-icon name="bell" class="w-5 h-5" />
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- User dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <x-icon name="user" class="w-5 h-5 text-blue-600" />
                    </div>
                    <span class="hidden md:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                    <x-icon name="chevron-down" class="w-4 h-4" />
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>