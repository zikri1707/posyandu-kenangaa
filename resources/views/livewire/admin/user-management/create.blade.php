@extends('layouts.admin-layout')

@section('admin-title', 'Tambah Pengguna Baru')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Tambah Pengguna Baru</h2>
            <p class="text-slate-500 font-medium">Lengkapi formulir di bawah untuk mendaftarkan akun baru ke sistem.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
            <i class="fas fa-times text-lg"></i>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[2rem] flex items-start space-x-4">
            <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-exclamation-circle text-lg"></i>
            </div>
            <div>
                <h4 class="text-red-800 font-black text-sm uppercase tracking-widest mb-2">Terjadi Kesalahan</h4>
                <ul class="list-disc list-inside text-red-600 text-sm font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ role: '{{ old('role', '') }}' }">
        @csrf
        
        <!-- Main Form Card -->
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-slate-200/60 border border-slate-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -mr-32 -mt-32"></div>
            
            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                <!-- Name -->
                <x-forms.form-group label="Nama Lengkap" for="name" required>
                    <x-forms.text-input name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required />
                </x-forms.form-group>

                <!-- Username -->
                <x-forms.form-group label="Username" for="username" required>
                    <x-forms.text-input name="username" placeholder="Contoh: bidansari" value="{{ old('username') }}" required />
                </x-forms.form-group>

                <!-- Email -->
                <x-forms.form-group label="Alamat Email" for="email" required>
                    <x-forms.text-input type="email" name="email" placeholder="email@posyandu.com" value="{{ old('email') }}" required />
                </x-forms.form-group>

                <!-- Role -->
                <x-forms.form-group label="Peran / Role" for="role" required>
                    <x-forms.select-input name="role" placeholder="Pilih Peran" required @change="role = $event.target.value">
                        <option value="admin1" {{ old('role') == 'admin1' ? 'selected' : '' }}>Admin 1 (Kenanga 1)</option>
                        <option value="admin2" {{ old('role') == 'admin2' ? 'selected' : '' }}>Admin 2 (Kenanga 2)</option>
                        <option value="kader1" {{ old('role') == 'kader1' ? 'selected' : '' }}>Kader 1 (Kenanga 1)</option>
                        <option value="kader2" {{ old('role') == 'kader2' ? 'selected' : '' }}>Kader 2 (Kenanga 2)</option>
                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Admin RW</option>
                    </x-forms.select-input>
                </x-forms.form-group>

                <!-- Cadre Profile Section (Conditionally Visible) -->
                <div x-show="role.includes('admin') || role.includes('kader')" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="md:col-span-2 mt-2 p-8 bg-gradient-to-br from-emerald-50/40 to-teal-50/20 dark:from-slate-800/40 dark:to-slate-900/30 rounded-[2rem] border border-emerald-100/50 dark:border-slate-800 space-y-6">
                    
                    <h3 class="text-lg font-black text-emerald-800 dark:text-emerald-400 flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined">badge</span>
                        Informasi Profil Kader
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIK -->
                        <x-forms.form-group label="NIK (Nomor Induk Kependudukan)" for="nik">
                            <x-forms.text-input name="nik" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" maxlength="16" />
                        </x-forms.form-group>

                        <!-- TTL -->
                        <x-forms.form-group label="Tempat, Tanggal Lahir" for="ttl">
                            <x-forms.text-input name="ttl" placeholder="Contoh: Bekasi, 12 April 1990" value="{{ old('ttl') }}" />
                        </x-forms.form-group>

                        <!-- Jabatan / Peran Spesifik Kader -->
                        <x-forms.form-group label="Jabatan di Posyandu" for="cadre_role">
                            <x-forms.text-input name="cadre_role" placeholder="Contoh: Ketua Kader, Bendahara, Anggota" value="{{ old('cadre_role') }}" />
                        </x-forms.form-group>

                        <!-- Pendidikan (Selectable Cards) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-gray-300 uppercase tracking-widest mb-3">Pendidikan Terakhir</label>
                            <input type="hidden" name="pendidikan" id="pendidikan" value="{{ old('pendidikan') }}">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" x-data="{ selected: '{{ old('pendidikan') }}' }">
                                @foreach(['SD', 'SMP', 'SLTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $edu)
                                    <button type="button" 
                                            @click="selected = '{{ $edu }}'; document.getElementById('pendidikan').value = '{{ $edu }}'"
                                            :class="selected === '{{ $edu }}' ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-350 border-slate-200 dark:border-slate-700 hover:border-slate-300'"
                                            class="px-4 py-3 rounded-2xl border text-center font-bold text-sm transition-all focus:outline-none">
                                        {{ $edu }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-gray-300 uppercase tracking-widest mb-3">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap..." class="w-full px-5 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-100 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Foto Profil / Image Upload with Instant Live Preview -->
                        <div class="md:col-span-2 flex flex-col md:flex-row items-center gap-6 p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 mt-4">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-600 bg-slate-50 flex-shrink-0 relative">
                                <img id="image-preview" 
                                     src="{{ asset('assets/img/kaders/placeholder.svg') }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow text-center md:text-left">
                                <h4 class="text-sm font-bold text-slate-850 dark:text-gray-200">Foto Profil Kader</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-400 mb-3">Gunakan foto wajah yang jelas dengan format JPG/PNG (Maks. 2MB)</p>
                                <input type="file" name="image" id="image-upload" class="hidden" accept="image/*" 
                                       onchange="const file = this.files[0]; if(file){ const reader = new FileReader(); reader.onload = e => document.getElementById('image-preview').src = e.target.result; reader.readAsDataURL(file); }">
                                <button type="button" onclick="document.getElementById('image-upload').click()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition-all uppercase tracking-wider">
                                    Pilih Foto Kader
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <div class="md:col-span-2 py-4">
                    <div class="h-px bg-slate-100 w-full"></div>
                </div>

                <!-- Password -->
                <x-forms.form-group label="Password" for="password" required>
                    <x-forms.text-input type="password" name="password" placeholder="Minimal 8 karakter" required />
                </x-forms.form-group>

                <!-- Password Confirmation -->
                <x-forms.form-group label="Konfirmasi Password" for="password_confirmation" required>
                    <x-forms.text-input type="password" name="password_confirmation" placeholder="Ulangi password" required />
                </x-forms.form-group>

                <!-- Active Status -->
                <div class="md:col-span-2 flex items-center justify-between p-6 bg-slate-50 dark:bg-slate-900 rounded-3xl mt-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="fas fa-user-shield text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-gray-200">Status Akun</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-400">Aktifkan untuk memberikan akses masuk secepatnya.</p>
                        </div>
                    </div>
                    <x-forms.switch name="is_active" checked />
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-4">
            <x-button href="{{ route('admin.users.index') }}" variant="outline" class="px-10 py-4 rounded-2xl border-slate-200 text-slate-500 hover:bg-slate-50">Batalkan</x-button>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center">
                <i class="fas fa-check mr-3"></i> SIMPAN PENGGUNA
            </button>
        </div>
    </form>
</div>
@endsection