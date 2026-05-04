<div class="space-y-6">
    {{-- Header Section (Replicated style from other components) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <nav class="flex text-xs text-slate-400 mb-1.5 gap-1.5 items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-600 transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-teal-600 font-semibold">Manajemen User</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Akses & Pengguna</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola hak akses dan akun kader posyandu.</p>
        </div>
        
        <div class="flex flex-wrap gap-3 items-center">
            <x-button href="{{ route('admin.users.create') }}" variant="secondary" icon="person_add">
                Tambah Pengguna
            </x-button>
        </div>
    </div>

    {{-- ── Stats Row ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pengguna</div>
            <div class="text-3xl font-black text-slate-900">{{ $totalUsers }}</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kader Aktif</div>
            <div class="text-3xl font-black text-slate-900">{{ App\Models\User::where('role', 'kader')->where('is_active', true)->count() }}</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Terdaftar</div>
            <div class="text-3xl font-black text-slate-900">{{ $totalPosyandu }}</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Akun Nonaktif</div>
            <div class="text-3xl font-black text-red-600">{{ $inactiveUsers }}</div>
        </div>
    </div>

    {{-- ── Search & Filter Bar ── --}}
    <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Unified Search --}}
            <div class="flex-1 min-w-[280px] relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama atau email..."
                       class="search-input-premium w-full">
            </div>

            {{-- Role Filter --}}
            <div class="w-full sm:w-auto min-w-[180px]">
                <select wire:model.live="role"
                        class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 transition-all appearance-none cursor-pointer">
                    <option value="">Semua Role</option>
                    @foreach(App\Models\User::getRoles() as $r)
                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-auto min-w-[150px]">
                <select wire:model.live="status"
                        class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 transition-all appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            @if($search || $role || $status)
            <button wire:click="$set('search', ''); $set('role', ''); $set('status', '');"
                    class="h-12 px-4 flex items-center gap-2 text-red-500 font-bold text-xs uppercase tracking-widest hover:bg-red-50 rounded-2xl transition-all">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                Reset
            </button>
            @endif
        </div>
    </section>

    {{-- ── Data Table ── --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <x-table>
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Detail User</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Role</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Unit Penugasan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="group hover:bg-slate-50/50 transition-colors" wire:key="user-{{ $user->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xs border border-teal-100">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm leading-tight">{{ $user->name }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                            @php
                                $roleName = $user->display_role_name;
                                if ($roleName === 'superadmin') {
                                    $label = 'Super Admin';
                                } else {
                                    // Handle cases where role name doesn't end with a digit
                                    $lastChar = substr($roleName, -1);
                                    if (is_numeric($lastChar)) {
                                        $label = ucfirst(substr($roleName, 0, -1)) . ' ' . $lastChar;
                                    } else {
                                        $label = ucfirst($roleName);
                                    }
                                }
                            @endphp
                            {{ $label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[13px] font-semibold text-slate-600">{{ $user->posyandu->name ?? 'Semua Unit' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 text-green-600 text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-button href="{{ route('admin.users.show', $user->id) }}" variant="ghost" size="sm">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </x-button>
                            <x-button href="{{ route('admin.users.edit', $user->id) }}" variant="ghost" size="sm">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </x-button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-24 text-center">
                        <div class="flex flex-col items-center gap-4 text-slate-300">
                            <span class="material-symbols-outlined text-[64px]">person_off</span>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada user ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>

    {{-- ── Pagination ── --}}
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
        {{ $users->links() }}
    </div>
</div>
