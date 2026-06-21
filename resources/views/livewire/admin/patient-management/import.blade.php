@extends('layouts.app')

@section('title', 'Tambah Banyak Warga')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 py-6">

    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tambah Banyak Warga</h1>
            <p class="text-base text-slate-500 mt-1">Gunakan fitur ini untuk memasukkan data laporan bulanan sekaligus.</p>
        </div>
        <a href="{{ route('admin.patients.index') }}"
           class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-black text-slate-600 hover:bg-slate-50 transition-all w-fit shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali ke Daftar
        </a>
    </div>

    {{-- ── Progress Steps (Visual Guide for Kader) ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-black">1</div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Langkah Pertama</p>
                <p class="text-base font-black text-slate-800">Unduh Contoh File</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-amber-200 bg-amber-50/30 flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-black">2</div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Langkah Kedua</p>
                <p class="text-base font-black text-slate-800">Isi Data Laporan</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center text-xl font-black">3</div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Langkah Terakhir</p>
                <p class="text-base font-black text-slate-800">Unggah & Selesai</p>
            </div>
        </div>
    </div>

    {{-- ── Main Content Area ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- Left: Upload Form --}}
        <div class="lg:col-span-7 space-y-8">
            <div class="bg-white border border-slate-200 rounded-[3rem] shadow-2xl overflow-hidden border-t-[12px] border-t-teal-600">
                <div class="p-10 md:p-14">
                    <form action="{{ route('admin.patients.import.store') }}" method="POST"
                          enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        @if($errors->any())
                        <div class="p-6 bg-red-50 border border-red-100 text-red-800 rounded-[2rem] text-sm flex gap-4 animate-in slide-in-from-top-4 duration-300">
                            <span class="material-symbols-outlined text-red-500 text-[24px]">error</span>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="font-bold">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Posyandu Selection (Only for Admin RW) --}}
                        @if(auth()->user()->isSuperAdmin())
                        <div class="space-y-4">
                            <label class="block text-base font-black text-slate-800 ml-2">
                                1. Pilih Lokasi Posyandu <span class="text-red-500">*</span>
                            </label>
                            <x-forms.select-input name="posyandu_id" placeholder="-- Pilih Posyandu --" :placeholderDisabled="true" value="{{ old('posyandu_id') }}" :error="$errors->has('posyandu_id')">
                                @foreach($posyandus as $pos)
                                    <option value="{{ $pos->id }}" {{ old('posyandu_id') == $pos->id ? 'selected' : '' }}>
                                        {{ $pos->name }}
                                    </option>
                                @endforeach
                            </x-forms.select-input>
                        </div>
                        @else
                        <input type="hidden" name="posyandu_id" value="{{ auth()->user()->posyandu_id }}">
                        @endif

                        {{-- File Upload --}}
                        <div class="space-y-4">
                            <label class="block text-base font-black text-slate-800 ml-2">
                                2. Pilih File Laporan (Excel) <span class="text-red-500">*</span>
                            </label>
                            
                            <div id="dropzone"
                                 class="relative border-4 border-dashed border-slate-100 rounded-[2.5rem] p-12 text-center cursor-pointer
                                        hover:border-teal-400 hover:bg-teal-50/50 transition-all group shadow-inner
                                        @error('file') border-red-200 bg-red-50 @enderror"
                                 onclick="document.getElementById('fileInput').click()">
                                <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls"
                                       class="hidden" onchange="handleFileSelect(this)">
                                
                                <div id="dropzoneContent" class="space-y-4">
                                    <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-2 shadow-sm group-hover:scale-110 group-hover:bg-teal-100 transition-all duration-500">
                                        <span class="material-symbols-outlined text-[48px] text-slate-300 group-hover:text-teal-600 transition-colors">cloud_upload</span>
                                    </div>
                                    <div>
                                        <p class="text-xl font-black text-slate-800">Klik untuk Mencari File</p>
                                        <p class="text-sm text-slate-400 mt-2">Atau geser file laporan Anda ke kotak ini</p>
                                    </div>
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Mendukung .xlsx, .xls, .csv
                                    </div>
                                </div>

                                <div id="fileSelected" class="hidden animate-in fade-in zoom-in duration-500">
                                    <div class="w-24 h-24 bg-teal-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <span class="material-symbols-outlined text-[48px] text-teal-600" style="font-variation-settings:'FILL' 1;">description</span>
                                    </div>
                                    <p id="fileName" class="text-xl font-black text-teal-900 break-all px-6"></p>
                                    <p id="fileSize" class="text-sm text-teal-600/60 mt-2"></p>
                                    <button type="button" onclick="clearFile(event)"
                                            class="mt-6 px-6 py-2 bg-red-50 text-red-600 rounded-2xl text-xs font-black hover:bg-red-100 transition-all">
                                        Hapus & Ganti File
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" id="submitBtn"
                                    class="w-full h-20 bg-teal-600 text-white rounded-[2rem] text-xl font-black hover:bg-teal-700 active:scale-[0.97]
                                           transition-all flex items-center justify-center gap-4 shadow-2xl shadow-teal-600/30 disabled:opacity-50 group">
                                <span class="material-symbols-outlined text-[32px] group-hover:translate-y-[-2px] transition-transform">cloud_done</span>
                                Simpan Data Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: Instructions & Help --}}
        <div class="lg:col-span-5 space-y-8">
            
            {{-- Step 1 Download --}}
            <div class="bg-blue-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-blue-600/30 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black mb-3">Langkah 1: Unduh Contoh</h3>
                    <p class="text-blue-100 text-base mb-8 leading-relaxed">Pilih kategori warga untuk mengunduh template contoh yang sesuai agar format data cocok.</p>
                    <div class="space-y-4">
                        <a href="{{ route('admin.patients.template', ['category' => 'balita']) }}"
                           class="inline-flex items-center gap-4 px-8 py-4 bg-white text-blue-700 rounded-[1.5rem] font-black hover:bg-blue-50 transition-all active:scale-95 shadow-xl w-full justify-center text-sm">
                            <span class="material-symbols-outlined text-[20px]">child_care</span>
                            Download Template Balita
                        </a>
                        <a href="{{ route('admin.patients.template', ['category' => 'ibu_hamil']) }}"
                           class="inline-flex items-center gap-4 px-8 py-4 bg-white text-blue-700 rounded-[1.5rem] font-black hover:bg-blue-50 transition-all active:scale-95 shadow-xl w-full justify-center text-sm">
                            <span class="material-symbols-outlined text-[20px]">pregnant_woman</span>
                            Download Template Ibu Hamil
                        </a>
                        <a href="{{ route('admin.patients.template', ['category' => 'lansia']) }}"
                           class="inline-flex items-center gap-4 px-8 py-4 bg-white text-blue-700 rounded-[1.5rem] font-black hover:bg-blue-50 transition-all active:scale-95 shadow-xl w-full justify-center text-sm">
                            <span class="material-symbols-outlined text-[20px]">elderly</span>
                            Download Template Lansia
                        </a>
                    </div>
                </div>
            </div>

            {{-- Column Guide --}}
            <div class="bg-white border border-slate-200 rounded-[3rem] p-10 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Panduan Isi Kolom</h3>
                
                <div class="space-y-8">
                    {{-- Balita --}}
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">child_care</span>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-800">Kategori Balita</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 leading-relaxed">
                                Wajib mengisi **Nama Anak**, **Tanggal Lahir**, **Jenis Kelamin** (L/P), dan **Nama Orang Tua** (nm_ortu/ayah/ibu). Bisa mengimpor hasil timbangan langsung: **Berat**, **Tinggi**, **LILA**, **Lingkar Kepala**, dan **Imunisasi**.
                            </p>
                        </div>
                    </div>

                    {{-- Ibu Hamil --}}
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">pregnant_woman</span>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-800">Kategori Ibu Hamil</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 leading-relaxed">
                                Wajib mengisi **Nama**, **Tanggal Lahir**, **Jenis Kelamin** (P), **Nama Suami**, dan **Apakah Hamil** (Ya/Tidak). Bisa mencatat pemeriksaan klinis: **Berat**, **Tinggi**, dan **LILA**.
                            </p>
                        </div>
                    </div>

                    {{-- Lansia --}}
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">elderly</span>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-800">Kategori Lansia</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 leading-relaxed">
                                Wajib mengisi **Nama**, **Tanggal Lahir**, dan **Jenis Kelamin** (L/P). Silakan isi kolom **Riwayat Penyakit** (misal: Hipertensi, Diabetes) jika ada, serta hasil pemeriksaan fisik: **Berat** dan **Tinggi**.
                            </p>
                        </div>
                    </div>

                    {{-- Format Tanggal --}}
                    <div class="flex gap-5 border-t border-slate-100 pt-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">info</span>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-800">Format Tanggal & NIK</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 leading-relaxed">
                                - **Tanggal Lahir/Ukur**: Gunakan format `YYYY-MM-DD` (misal: 2022-08-06). Jika format tanggal tidak sesuai, baris data akan dilewati dengan peringatan.<br>
                                - **NIK**: Harus berupa 16 digit angka. Jika tidak sesuai atau kosong, akan muncul peringatan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>

@push('scripts')
<script>
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    document.getElementById('dropzoneContent').classList.add('hidden');
    document.getElementById('fileSelected').classList.remove('hidden');
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
}

function clearFile(e) {
    e.stopPropagation();
    document.getElementById('fileInput').value = '';
    document.getElementById('dropzoneContent').classList.remove('hidden');
    document.getElementById('fileSelected').classList.add('hidden');
}

// Drag & drop logic
const dropzone = document.getElementById('dropzone');
['dragenter', 'dragover'].forEach(eventName => {
    dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.add('border-teal-500', 'bg-teal-50/80');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-teal-500', 'bg-teal-50/80');
    }, false);
});

dropzone.addEventListener('drop', (e) => {
    const file = e.dataTransfer.files[0];
    if (file) {
        const input = document.getElementById('fileInput');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        handleFileSelect(input);
    }
});

// Loading state on submit
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="font-black">Sedang Menyimpan...</span>
    `;
});
</script>
@endpush
@endsection
