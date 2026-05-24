# Menghubungkan Manajemen User dengan Halaman About (Tim Pelaksana) Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Menghubungkan Manajemen User di dashboard admin dengan daftar kader di halaman public About agar dinamis berbasis database, serta meningkatkan UI/UX form user.

**Architecture:** Menambahkan kolom-kolom profil kader ke dalam tabel `users` (nullable), memigrasikan 10 data kader awal ke database, merefaktorkan `AboutPageService` untuk membaca dari database, dan meningkatkan UI/UX form menggunakan Alpine.js untuk menampilkan bagian profil kader secara kondisional dengan Grid Selectable Cards dan Live Image Preview.

**Tech Stack:** Laravel, Blade, Alpine.js, Tailwind CSS, Livewire.

---

### Task 1: Database Migration & Seeding Kader Default

**Files:**
- Create: `database/migrations/2026_05_24_080000_add_cadre_fields_to_users_table.php`

**Step 1: Write migration code**
Buat file migrasi baru yang menambahkan kolom-kolom baru:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cadre_role')->nullable();
            $table->string('ttl')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('pendidikan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('hp')->nullable();
            $table->string('image')->nullable();
        });

        // Seed 10 default cadres
        $kaders = [
            [
                'name' => 'Sri Hartati',
                'role' => 'kader',
                'cadre_role' => 'Ketua Kader',
                'ttl' => 'Lampung, 12 April 1962',
                'nik' => '3275015204620012',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Sumba 8 No. 232 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081380473365',
                'email' => 'denyyoga2212@gmail.com',
                'image' => 'assets/img/kaders/sri_hartati.png',
            ],
            [
                'name' => 'Widayanti Christiani',
                'role' => 'kader',
                'cadre_role' => 'Sekretaris',
                'ttl' => 'Jakarta, 05 April 1982',
                'nik' => '3275014504820054',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Bali 1 No. 330 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '08139914428',
                'email' => 'widayantichristiani@yahoo.co.id',
                'image' => 'assets/img/kaders/widayanti.jpg',
            ],
            [
                'name' => 'Parniyati',
                'role' => 'kader',
                'cadre_role' => 'Bendahara',
                'ttl' => 'Karanganyar, 15 Juli 1971',
                'nik' => '3275015507710014',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 3 No 37 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '085770153741',
                'email' => 'parniyati15.71@gmail.com',
                'image' => 'assets/img/kaders/parniyati.png',
            ],
            [
                'name' => 'Arimbi Kurniasari',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Klaten, 28 November 1976',
                'nik' => '3275016811760020',
                'pendidikan' => 'Magister',
                'alamat' => 'Jl P. Madura 4 No. 15 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081297963177',
                'email' => 'arimbi28sari@ggmail.com',
                'image' => 'assets/img/kaders/arimbi.png',
            ],
            [
                'name' => 'Dewi Pastrinah',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 29 Desember 1981',
                'nik' => '3275016912810022',
                'pendidikan' => 'SMK',
                'alamat' => 'Jl P. Madura 4 No. 22 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081517001791',
                'email' => 'dewigedhe81@gmail.com',
                'image' => 'assets/img/kaders/dewi_pastrinah.png',
            ],
            [
                'name' => 'Tionar Maulina Purba',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Dolok Sanggul, 25 Januari 1959',
                'nik' => '3275016501590013',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 3 No 38 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081218385669',
                'email' => 'tionar.mp@gmail.com',
                'image' => 'assets/img/kaders/tionar.png',
            ],
            [
                'name' => 'Maita Indriati',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 19 Mei 1963',
                'nik' => '3275015905630012',
                'pendidikan' => 'Sarjana',
                'alamat' => 'Jl Sumba Raya No 03 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081295743714',
                'email' => 'Maitaindriati1905@gmail.com',
                'image' => 'assets/img/kaders/maita.png',
            ],
            [
                'name' => 'Arfah',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 15 Mei 1967',
                'nik' => '3275015505670018',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl Sumba Raya No 27 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '087856068033',
                'email' => 'arfah.6715@gmail.com',
                'image' => 'assets/img/kaders/arfah.png',
            ],
            [
                'name' => 'Mustikasari',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Subang, 09 September 1956',
                'nik' => '3275014909560018',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Sumba 7 No. 254 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081293290635',
                'email' => 'Mustikasari@gmail.com',
                'image' => 'assets/img/kaders/mustikasari.png',
            ],
            [
                'name' => 'Ika Rakhmawati',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 15 Agustus 1978',
                'nik' => '3275015508780053',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 2 No. 58 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081315662377',
                'email' => 'ika@posyandu.com', // replace "-" with valid unique email
                'image' => 'assets/img/kaders/ika.jpeg',
            ],
        ];

        foreach ($kaders as $k) {
            $username = strtolower(explode(' ', $k['name'])[0]) . '_' . rand(100, 999);
            // Check if email already exists
            if (!DB::table('users')->where('email', $k['email'])->exists()) {
                DB::table('users')->insert([
                    'name' => $k['name'],
                    'email' => $k['email'],
                    'username' => $username,
                    'password' => Hash::make('password123'),
                    'role' => $k['role'],
                    'cadre_role' => $k['cadre_role'],
                    'ttl' => $k['ttl'],
                    'nik' => $k['nik'],
                    'pendidikan' => $k['pendidikan'],
                    'alamat' => $k['alamat'],
                    'hp' => $k['hp'],
                    'image' => $k['image'],
                    'posyandu_id' => 3, // Default to Kenanga 1
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cadre_role', 'ttl', 'nik', 'pendidikan', 'alamat', 'hp', 'image']);
        });
    }
};
```

**Step 2: Run migration**
Run: `php artisan migrate`
Expected: Migrasi berjalan sukses dan menambahkan kolom serta menyisipkan data kader ke dalam database.

**Step 3: Commit**
Commit changes.

---

### Task 2: Update Model `User.php`

**Files:**
- Modify: `app/Models/User.php:21-34`

**Step 1: Add new attributes to `$fillable`**
Tambahkan kolom baru ke properti `$fillable`:
```php
        'cadre_role',
        'ttl',
        'nik',
        'pendidikan',
        'alamat',
        'hp',
        'image',
```

**Step 2: Verify code syntaxes**
Jalankan lint check / test sederhana untuk memastikan file User.php valid.
Expected: OK.

**Step 3: Commit**
Commit changes.

---

### Task 3: Request & Service Updates (Validation, Upload, Delete)

**Files:**
- Modify: `app/Http/Requests/UserRequest.php:19-29`
- Modify: `app/Services/UserService.php`

**Step 1: Update validation in `UserRequest.php`**
Tambahkan aturan berikut ke rules():
```php
            'cadre_role' => 'nullable|string|max:255',
            'ttl' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:16',
            'pendidikan' => 'nullable|string|in:SD,SMP,SLTA,Diploma,Sarjana,Magister,Doktor',
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
```

**Step 2: Update `UserService.php` with image upload handling & `deleteUser` method**
- Di method `createUser()` dan `updateUser()`, proses file gambar jika diunggah:
  - Simpan gambar ke disk `public` di subfolder `kaders` (`$path = $data['image']->store('kaders', 'public')`). Simpan hanya nama filenya atau path relatifnya (`basename($path)`).
  - Pada pembaruan, jika ada gambar baru, hapus gambar lama dari disk public (`Storage::disk('public')->delete('kaders/' . $user->image)`).
- Implementasikan method `deleteUser(User $user)`:
  - Hapus gambar user kader dari disk public jika ada.
  - Hapus model user tersebut.
  ```php
    public function deleteUser(User $user): void
    {
        if ($user->image && !str_starts_with($user->image, 'assets/')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('kaders/' . $user->image);
        }
        
        $userName = $user->name;
        $userId = $user->id;

        $user->delete();

        $this->activityLogService->log(
            'delete_user',
            "Menghapus pengguna: {$userName}",
            $userId,
            'User'
        );
    }
  ```

**Step 3: Verify execution**
Expected: Service compile sukses tanpa lint errors.

**Step 4: Commit**
Commit changes.

---

### Task 4: Dynamic About Page Kader Loading (`AboutPageService.php`)

**Files:**
- Modify: `app/Services/AboutPageService.php:92-208`

**Step 1: Fetch cadres from DB**
Ubah `getCadres()` agar mengambil data dari database:
```php
    public function getCadres(): array
    {
        $users = \App\Models\User::whereIn('role', ['admin', 'kader'])
            ->orderBy('id')
            ->get();

        $kaders = $users->map(function ($user) {
            $imagePath = $user->image;
            if (empty($imagePath)) {
                $imagePath = asset('assets/img/kaders/placeholder.png'); // placeholder default jika kosong
            } elseif (str_starts_with($imagePath, 'assets/')) {
                $imagePath = asset($imagePath);
            } else {
                $imagePath = \Illuminate\Support\Facades\Storage::url('kaders/' . $imagePath);
            }

            return [
                'name' => $user->name,
                'role' => $user->cadre_role ?? 'Kader',
                'ttl' => $user->ttl ?? '-',
                'nik' => $user->nik ?? '-',
                'pendidikan' => $user->pendidikan ?? '-',
                'alamat' => $user->alamat ?? '-',
                'hp' => $user->hp ?? '-',
                'email' => $user->email,
                'image' => $imagePath,
            ];
        })->toArray();

        return array_map(fn($k) => CadreData::fromArray($k), $kaders);
    }
```

**Step 2: Commit**
Commit changes.

---

### Task 5: Enhance UI in Admin User Management (Create & Edit Views)

**Files:**
- Modify: `resources/views/livewire/admin/user-management/create.blade.php`
- Modify: `resources/views/livewire/admin/user-management/update.blade.php`

**Step 1: Add enctype and Alpine state**
- Tambahkan `enctype="multipart/form-data"` ke formulir pendaftaran.
- Tambahkan Alpine `x-data="{ role: '{{ old('role', $user->role ?? '') }}' }"` di pembungkus formulir.
- Ubah dropdown `role` agar memiliki `@change="role = $event.target.value"`.

**Step 2: Insert conditionally visible "Informasi Profil Kader" section**
Gunakan `x-show="role.includes('admin') || role.includes('kader')"` dengan transisi yang halus:
```html
<div x-show="role.includes('admin') || role.includes('kader')" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     class="md:col-span-2 mt-6 p-8 bg-gradient-to-br from-emerald-50/40 to-teal-50/20 dark:from-slate-800/40 dark:to-slate-900/30 rounded-[2rem] border border-emerald-100/50 dark:border-slate-800 space-y-6">
    
    <h3 class="text-lg font-black text-emerald-800 dark:text-emerald-400 flex items-center gap-2 mb-4">
        <span class="material-symbols-outlined">badge</span>
        Informasi Profil Kader
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- NIK -->
        <x-forms.form-group label="NIK (Nomor Induk Kependudukan)" for="nik">
            <x-forms.text-input name="nik" placeholder="Masukkan 16 digit NIK" value="{{ old('nik', $user->nik ?? '') }}" maxlength="16" />
        </x-forms.form-group>

        <!-- TTL -->
        <x-forms.form-group label="Tempat, Tanggal Lahir" for="ttl">
            <x-forms.text-input name="ttl" placeholder="Contoh: Bekasi, 12 April 1990" value="{{ old('ttl', $user->ttl ?? '') }}" />
        </x-forms.form-group>

        <!-- Pendidikan (Selectable Cards) -->
        <div class="md:col-span-2">
            <label class="block text-xs font-black text-slate-400 dark:text-gray-300 uppercase tracking-widest mb-3">Pendidikan Terakhir</label>
            <input type="hidden" name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $user->pendidikan ?? '') }}">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" x-data="{ selected: '{{ old('pendidikan', $user->pendidikan ?? '') }}' }">
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
            <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap..." class="w-full px-5 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-750 rounded-2xl text-slate-800 dark:text-slate-100 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">{{ old('alamat', $user->alamat ?? '') }}</textarea>
        </div>

        <!-- Foto Profil / Image Upload with Instant Live Preview -->
        <div class="md:col-span-2 flex flex-col md:flex-row items-center gap-6 p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 mt-4">
            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-600 bg-slate-50 flex-shrink-0 relative">
                <img id="image-preview" 
                     src="{{ isset($user) && $user->image ? (str_starts_with($user->image, 'assets/') ? asset($user->image) : \Illuminate\Support\Facades\Storage::url('kaders/' . $user->image)) : asset('assets/img/kaders/placeholder.png') }}" 
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
```
*(Catatan: pastikan default placeholder image `placeholder.png` disalin atau dibuat di public/assets/img/kaders/placeholder.png).*

**Step 3: Commit**
Commit changes.

---

### Task 6: About Page Views Integration & WhatsApp Button Removal

**Files:**
- Modify: `resources/views/public/about/tim.blade.php:16`
- Modify: `resources/views/public/about/modal.blade.php`

**Step 1: Modify `tim.blade.php`**
Hapus parameter `hp` dari parameter pemanggilan fungsi `onclick="openKaderModal(...)` di baris ke-16:
```diff
- onclick="openKaderModal('{{ addslashes($k->name) }}', '{{ addslashes($k->role) }}', '{{ addslashes($k->pendidikan) }}', '{{ addslashes($k->alamat) }}', '{{ addslashes($k->hp) }}', '{{ addslashes($k->email) }}', '{{ addslashes($k->image) }}')"
+ onclick="openKaderModal('{{ addslashes($k->name) }}', '{{ addslashes($k->role) }}', '{{ addslashes($k->pendidikan) }}', '{{ addslashes($k->alamat) }}', '{{ addslashes($k->email) }}', '{{ addslashes($k->image) }}')"
```

**Step 2: Modify `modal.blade.php`**
- Hapus div/tombol WhatsApp (`modalWaBtn`) secara keseluruhan dari modal (sekitar baris ke-55-63).
- Sesuaikan javascript `openKaderModal`:
```javascript
function openKaderModal(name, role, pendidikan, alamat, email, image) {
    document.getElementById('modalImage').src = image;
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalRole').innerText = role;
    document.getElementById('modalPendidikan').innerText = pendidikan;
    document.getElementById('modalAlamat').innerText = alamat;
    document.getElementById('modalEmail').innerText = email && email !== '-' ? email : '-';

    const modal = document.getElementById('kaderModal');
    const content = document.getElementById('kaderModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}
```

**Step 3: Commit**
Commit changes.

---

### Task 7: Verification & Testing

**Files:**
- Test: `tests/Feature/Admin/PatientManagementTest.php`

**Step 1: Run Existing Test Suite**
Pastikan test suite default tetap pass:
Run: `php artisan test`
Expected: PASS

**Step 2: Manual Verification**
1. Run server `php artisan serve` or access locally.
2. Login as admin, go to User Management.
3. Verify adding a cadre updates public page.
4. Verify deleting/editing kader works as expected.
