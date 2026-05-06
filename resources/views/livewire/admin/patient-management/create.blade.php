@extends('layouts.admin-layout')

@section('admin-title') Tambah Sasaran Warga Baru @endsection

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-8 pb-20" 
     x-data="{ 
        category: '{{ old('category', 'umum') }}',
        nikCount: {{ strlen(old('id_number', '')) }},
        init() {
            this.$watch('category', value => this.toggleSections(value));
        }
     }">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pendaftaran Warga</h2>
            <p class="text-sm text-slate-400 font-medium mt-1">Sistem informasi manajemen kesehatan warga Posyandu.</p>
        </div>
        <x-button href="{{ route('admin.patients.index') }}" variant="ghost" icon="arrow_back">Kembali</x-button>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 rounded-[24px] p-6 animate-in fade-in slide-in-from-top-2 duration-300">
            <div class="flex gap-3 items-center mb-3">
                <span class="material-symbols-outlined text-red-500">error</span>
                <h4 class="text-sm font-black text-red-800 uppercase tracking-widest">Ada Masalah Pada Input Anda</h4>
            </div>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-xs font-bold text-red-600 flex items-center gap-2">
                        <div class="w-1 h-1 bg-red-400 rounded-full"></div>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ── Section 1: Kategori ── --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-teal-500 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Kelompok Sasaran</h3>
                <div id="category-suggestion" class="hidden animate-pulse text-[10px] font-black text-teal-600 bg-teal-50 px-3 py-1.5 rounded-full uppercase tracking-tighter ml-auto border border-teal-100">Saran Otomatis Diterapkan!</div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    'bayi'      => ['label'=>'Bayi','desc'=>'0–11 bln','icon'=>'baby_changing_station'],
                    'baduta'    => ['label'=>'Baduta','desc'=>'12–23 bln','icon'=>'child_friendly'],
                    'balita'    => ['label'=>'Balita','desc'=>'24–59 bln','icon'=>'child_care'],
                    'anak_sekolah' => ['label'=>'Anak Sekolah','desc'=>'5–9 thn','icon'=>'school'],
                    'ibu_hamil' => ['label'=>'Ibu Hamil','desc'=>'Kehamilan','icon'=>'pregnant_woman'],
                    'remaja'    => ['label'=>'Remaja','desc'=>'Pelajar','icon'=>'emoji_people'],
                    'lansia'    => ['label'=>'Lansia','desc'=>'Lanjut Usia','icon'=>'elderly'],
                    'umum'      => ['label'=>'Umum','desc'=>'Lainnya','icon'=>'groups'],
                ] as $val => $cat)
                <label class="relative cursor-pointer group">
                    <input type="radio" name="category" value="{{ $val }}" x-model="category" class="sr-only peer">
                    <div class="p-5 rounded-2xl border border-slate-100 bg-white transition-all duration-300 peer-checked:border-teal-500 peer-checked:bg-teal-50/30 flex flex-col items-center text-center group-hover:border-slate-200">
                        <span class="material-symbols-outlined text-slate-300 group-hover:text-teal-500 transition-colors mb-3 peer-checked:text-teal-600" style="font-size:32px; font-variation-settings: 'wght' 300;">{{ $cat['icon'] }}</span>
                        <div class="font-black text-slate-700 text-sm peer-checked:text-teal-900">{{ $cat['label'] }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $cat['desc'] }}</div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('category') <p class="mt-3 text-xs text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
        </div>

        {{-- ── Section 2: Identitas ── --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-slate-200 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Identitas Pribadi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                {{-- Upload Foto --}}
                <div class="md:col-span-3 flex flex-col items-center space-y-4">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-full border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-teal-500 group-hover:bg-teal-50/30">
                            <img id="photo-preview" src="" class="w-full h-full object-cover hidden">
                            <span id="photo-placeholder" class="material-symbols-outlined text-slate-300 text-[64px]" style="font-variation-settings: 'wght' 200;">account_circle</span>
                        </div>
                        <label for="profile_photo" class="absolute bottom-1 right-1 w-10 h-10 bg-white border border-slate-200 text-slate-600 rounded-full flex items-center justify-center cursor-pointer shadow-sm hover:text-teal-600 hover:border-teal-500 transition-all">
                            <span class="material-symbols-outlined text-[20px]">add_a_photo</span>
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center leading-tight">Foto Profil<br><span class="font-bold lowercase">JPG/PNG, Max 2MB</span></p>
                    @error('profile_photo') <p class="text-[10px] text-red-500 font-bold text-center">{{ $message }}</p> @enderror
                </div>

                {{-- Fields --}}
                <div class="md:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">NIK <span class="text-red-500">*</span></label>
                            <span class="text-[10px] font-bold text-slate-300" :class="nikCount == 16 ? 'text-teal-500' : ''" x-text="nikCount + '/16'"></span>
                        </div>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" maxlength="16" required
                               x-on:input="nikCount = $el.value.length"
                               class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                        @error('id_number') <p class="text-[11px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                        @error('full_name') <p class="text-[11px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                        <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}"
                               class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                               onchange="autoDetectCategory(this.value)"
                               class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="gender" required
                                class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer appearance-none">
                            <option value="">Pilih</option>
                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kepala Keluarga</label>
                        <input type="text" name="head_of_family_name" value="{{ old('head_of_family_name') }}"
                               class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Section 3: Dinamis (Alpine Controlled) ── --}}
        
        {{-- Seksi Anak --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8"
             x-show="['bayi', 'baduta', 'balita', 'anak_sekolah'].includes(category)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-sky-400 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Ibu & Anak</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ibu</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                           class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK Ibu</label>
                    <input type="text" name="mother_nik" value="{{ old('mother_nik') }}" maxlength="16"
                           class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Buku KIA</label>
                    <select name="kia_book_ownership" class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer">
                        <option value="0">Tidak Memiliki</option>
                        <option value="1">Ya, Memiliki</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Seksi Dewasa --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8"
             x-show="['ibu_hamil', 'remaja', 'umum'].includes(category)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-pink-400 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Dewasa</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pendidikan</label>
                    <select name="education" class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer">
                        <option value="">Pilih</option>
                        @foreach(['SD','SMP','SMA','D3','S1','S2','S3','Tidak Sekolah'] as $edu)
                            <option value="{{ $edu }}">{{ $edu }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pekerjaan</label>
                    <input type="text" name="job" value="{{ old('job') }}"
                           class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Anak</label>
                    <input type="number" name="number_of_children" value="{{ old('number_of_children', 0) }}"
                           class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kehamilan</label>
                    <select name="is_pregnant" class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer">
                        <option value="0">Tidak Hamil</option>
                        <option value="1" :selected="category == 'ibu_hamil'">Sedang Hamil</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Seksi Lansia --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8"
             x-show="category == 'lansia'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-orange-400 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Lansia</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Tinggal</label>
                    <select name="living_status" class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer">
                        <option value="Sendiri">Tinggal Sendiri</option>
                        <option value="Keluarga">Bersama Keluarga</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kemandirian</label>
                    <select name="independence_status" class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer">
                        <option value="Mandiri">Mandiri (A)</option>
                        <option value="Bantuan">Bantuan (B/C)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Section 4: Kontak ── --}}
        <div class="bg-white rounded-[32px] border border-slate-100 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1.5 h-6 bg-slate-200 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Wilayah</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">No. HP / WA <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required
                           class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Posyandu <span class="text-red-500">*</span></label>
                    @if(auth()->user()->isKader())
                        <div class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl flex items-center text-sm font-bold text-slate-400 border-2">
                            {{ auth()->user()->posyandu->name }}
                        </div>
                        <input type="hidden" name="posyandu_id" value="{{ auth()->user()->posyandu_id }}">
                    @else
                        <select name="posyandu_id" required class="w-full h-12 px-4 bg-slate-50 border-transparent rounded-2xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 cursor-pointer appearance-none">
                            @foreach($posyandus as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="3" required
                              class="w-full p-4 bg-slate-50 border-transparent rounded-3xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 resize-none">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Footer Actions ── --}}
        <div class="flex items-center justify-end gap-3 px-4">
            <x-button href="{{ route('admin.patients.index') }}" variant="ghost" size="lg">Batal</x-button>
            <x-button type="submit" variant="secondary" size="lg" icon="save">Simpan Data</x-button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('photo-preview');
        const placeholder = document.getElementById('photo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function autoDetectCategory(birthDateStr) {
        if (!birthDateStr) return;
        const birthDate = new Date(birthDateStr);
        const today = new Date();
        let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
        months += today.getMonth() - birthDate.getMonth();
        if (today.getDate() < birthDate.getDate()) months--;
        
        let cat = 'umum';
        if (months <= 11) cat = 'bayi';
        else if (months <= 23) cat = 'baduta';
        else if (months <= 59) cat = 'balita';
        else if (months <= 119) cat = 'anak_sekolah';
        else if (months <= 227) cat = 'remaja';
        else if (months >= 720) cat = 'lansia';
        
        // Use Alpine to update state
        const el = document.querySelector('[x-data]');
        if (el && el.__x) {
            el.__x.$data.category = cat;
            const feedback = document.getElementById('category-suggestion');
            feedback.classList.remove('hidden');
            setTimeout(() => feedback.classList.add('hidden'), 3000);
        }
    }
</script>
@endpush
@endsection