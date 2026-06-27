<div class="space-y-6">
    {{-- Header Section (Standardized with other components) --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
        <div class="relative pl-6">
            {{-- Vertical Bar --}}
            <div
                class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full">
            </div>

            <div class="flex flex-col gap-4">
                <div>
                    <h1
                        class="text-3xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">
                        Manajemen Akses & Pengguna</h1>
                    <p class="text-sm font-bold text-slate-900 mt-2">Kelola hak akses dan akun kader posyandu.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            <x-button href="{{ route('admin.users.create') }}" variant="secondary" icon="person_add">
                Tambah Pengguna
            </x-button>
        </div>
    </div>

    @if (session()->has('success'))
        <div
            class="p-4 mb-4 text-sm text-teal-700 bg-teal-50 rounded-2xl border border-teal-100 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="p-4 mb-4 text-sm text-red-700 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <span class="material-symbols-outlined text-[20px]">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Stats Row ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pengguna</div>
            <div class="text-2xl font-black text-slate-900">{{ $totalUsers }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kader Aktif</div>
            <div class="text-2xl font-black text-slate-900">
                {{ App\Models\User::where('role', 'kader')->where('is_active', true)->count() }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Terdaftar</div>
            <div class="text-2xl font-black text-slate-900">{{ $totalPosyandu }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Akun Nonaktif</div>
            <div class="text-2xl font-black text-red-600">{{ $inactiveUsers }}</div>
        </div>
    </div>

    {{-- ── Search & Filter Bar ── --}}
    <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Unified Search --}}
            <div class="flex-1 min-w-[280px] relative group">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none">search</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                    class="search-input-premium w-full">
            </div>

            {{-- Role Filter --}}
            <div class="w-full sm:w-auto min-w-[180px]">
                <x-forms.select-input wire:model.live="role" placeholder="Semua Role" :placeholderDisabled="false"
                    value="{{ $role }}">
                    @foreach (App\Models\User::getRoles() as $r)
                        <option value="{{ $r }}">{{ $r === 'superadmin' ? 'Admin RW' : ucfirst($r) }}
                        </option>
                    @endforeach
                </x-forms.select-input>
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-auto min-w-[150px]">
                <x-forms.select-input wire:model.live="status" placeholder="Semua Status" :placeholderDisabled="false"
                    value="{{ $status }}">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </x-forms.select-input>
            </div>

            @if ($search || $role || $status)
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
                    <th class="px-5 py-3 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                        Detail User</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                        Role</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                        Unit Penugasan</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                        Status</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                    <tr class="group hover:bg-slate-50/50 transition-colors" wire:key="user-{{ $user->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xs border border-teal-100">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $user->name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->isSuperAdmin())
                                <span
                                    class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-slate-900 text-white border border-slate-900">
                                    Admin RW
                                </span>
                            @else
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                        <input type="checkbox" wire:click="toggleRole({{ $user->id }})"
                                            class="sr-only peer" {{ $user->isAdmin() ? 'checked' : '' }}>
                                        <div
                                            class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600">
                                        </div>
                                    </label>
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest {{ $user->isAdmin() ? 'text-teal-600' : 'text-slate-400' }}">
                                        {{ $user->isAdmin() ? 'Administrator' : 'Kader' }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-semibold text-slate-600">
                                {{ $user->posyandu->name ?? 'Semua Unit' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 text-green-600 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-500 hover:bg-teal-600 hover:text-white transition-all shadow-sm hover:shadow-teal-500/20 group/btn"
                                    title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[22px]">visibility</span>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-500 hover:bg-indigo-600 hover:text-white transition-all shadow-sm hover:shadow-indigo-500/20 group/btn"
                                    title="Edit User">
                                    <span class="material-symbols-outlined text-[22px]">edit</span>
                                </a>
                                <button wire:click="delete({{ $user->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan."
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-500/20 group/btn"
                                    title="Hapus User">
                                    <span class="material-symbols-outlined text-[22px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center gap-4 text-slate-300">
                                <span class="material-symbols-outlined text-[64px]">person_off</span>
                                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada user
                                    ditemukan</p>
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
