<aside class="w-64 bg-white shadow-md hidden md:block">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">{{ config('app.name', 'Posyandu') }}</h2>
    </div>
    
    <nav class="p-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="home" class="w-5 h-5" />
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            
            <!-- Manajemen Pasien -->
            <li>
                <a href="{{ route('patients.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('patients.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="users" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Pasien</span>
                </a>
            </li>
            
            <!-- Manajemen Jadwal -->
            <li>
                <a href="{{ route('schedules.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('schedules.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="calendar" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Jadwal</span>
                </a>
            </li>
            
            <!-- Manajemen Artikel -->
            <li>
                <a href="{{ route('articles.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('articles.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="newspaper" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Artikel</span>
                </a>
            </li>
            
            <!-- Manajemen Galeri -->
            <li>
                <a href="{{ route('galleries.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('galleries.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="photo" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Galeri</span>
                </a>
            </li>
            
            <!-- Manajemen Rekam Medis -->
            <li>
                <a href="{{ route('medical-records.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('medical-records.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="document-text" class="w-5 h-5" />
                    <span class="ml-3">Rekam Medis</span>
                </a>
            </li>
            
            <!-- Manajemen Pedukuhan -->
            <li>
                <a href="{{ route('pedukuhan.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('pedukuhan.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="map" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Pedukuhan</span>
                </a>
            </li>
            
            <!-- Manajemen Pengguna -->
            @can('manage-users')
            <li>
                <a href="{{ route('users.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-gray-100 font-medium' : '' }}">
                    <x-icon name="user-group" class="w-5 h-5" />
                    <span class="ml-3">Manajemen Pengguna</span>
                </a>
            </li>
            @endcan
        </ul>
        
        <!-- Logout -->
        <div class="mt-8 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-2 text-gray-600 rounded-lg hover:bg-gray-100">
                    <x-icon name="arrow-left-on-rectangle" class="w-5 h-5" />
                    <span class="ml-3">Keluar</span>
                </button>
            </form>
        </div>
    </nav>
</aside>