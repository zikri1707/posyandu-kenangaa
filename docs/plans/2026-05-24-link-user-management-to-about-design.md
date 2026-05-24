# Design Document - Menghubungkan Manajemen User dengan Halaman About (Tim Pelaksana)

Menghubungkan manajemen user di dashboard admin dengan daftar kader di halaman public "Tentang Kami" (About) secara dinamis menggunakan database, serta meningkatkan desain UI/UX form user.

## Latar Belakang & Tujuan
Saat ini, daftar kader pada halaman "Tentang Kami" (`tim.blade.php`) ditulis keras (hardcoded) di server melalui kelas `AboutPageService.php`. Hal ini menyulitkan admin untuk memperbarui daftar kader karena harus menyunting file kode PHP secara manual. Kami akan menghubungkan daftar ini langsung dengan tabel `users` di database agar setiap penambahan, pembaruan, atau penghapusan pengguna di menu Manajemen User otomatis mengubah daftar kader di halaman public.

## Keputusan Desain & Batasan
1. **Pendekatan Skema**: Kolom profil kader ditambahkan langsung pada tabel `users` (nullable columns).
2. **Kader Default**: 10 kader default yang sebelumnya hardcoded akan otomatis dimasukkan ke database via migrasi/seeder.
3. **No. HP & WhatsApp**: Sesuai permintaan pengguna, No. HP kader tidak perlu ditampilkan di form tambah/edit user, dan tombol WhatsApp di modal detail kader pada halaman public About juga akan dihapus.
4. **Visibilitas Field**: Informasi profil kader hanya muncul di form jika peran yang dipilih adalah `kader` atau `admin`.

---

## Rincian Perubahan

### 1. Database & Migrasi
Membuat berkas migrasi baru `database/migrations/2026_05_24_080000_add_cadre_fields_to_users_table.php` dengan spesifikasi:
- Menambahkan kolom nullable:
  - `cadre_role` (string) - Contoh: "Ketua Kader", "Sekretaris", "Bendahara", "Anggota"
  - `ttl` (string) - Tempat Tanggal Lahir
  - `nik` (string, 16 karakter)
  - `pendidikan` (string)
  - `alamat` (text)
  - `hp` (string) - Tetap ditambahkan di DB untuk data seeding 10 kader awal, namun disembunyikan dari UI
  - `image` (string) - Nama berkas atau path foto profil
- Fungsi `up()`: Menambahkan kolom-kolom ini dan menyisipkan data 10 kader default ke dalam tabel `users` (jika data belum ada berdasarkan email).
- Fungsi `down()`: Menghapus kolom-kolom baru.

### 2. Model & Validasi Request
- **Model `User.php`**: Tambahkan kolom baru (`cadre_role`, `ttl`, `nik`, `pendidikan`, `alamat`, `hp`, `image`) ke properti `$fillable`.
- **Request `UserRequest.php`**: Tambahkan aturan validasi untuk field baru:
  - `cadre_role` => `nullable|string|max:100`
  - `ttl` => `nullable|string|max:255`
  - `nik` => `nullable|string|size:16`
  - `pendidikan` => `nullable|string|in:SD,SMP,SLTA,Diploma,Sarjana,Magister,Doktor`
  - `alamat` => `nullable|string`
  - `image` => `nullable|image|mimes:jpeg,png,jpg,gif|max:2048`

### 3. Logika Service & Controller
- **`UserService.php`**:
  - Tangani upload file `image` di method `createUser()` dan `updateUser()`. File disimpan ke `storage/app/public/kaders/`.
  - Jika file gambar baru diunggah, hapus berkas gambar lama (jika ada) dari disk storage.
  - Implementasikan method `deleteUser(User $user)` yang sebelumnya hilang/belum diimplementasikan untuk mencegah crash saat menghapus user. Hapus file gambar kader ketika user dihapus.
- **`AboutPageService.php`**:
  - Ambil data kader dari database: `User::whereIn('role', ['admin', 'kader'])->orderBy('id')->get()`.
  - Konstruksikan `CadreData` secara dinamis.
  - Untuk field `image`, jika diawali dengan `assets/`, biarkan menggunakan path statis. Jika berupa nama berkas upload, konversikan ke URL storage menggunakan `Storage::url('kaders/' . $user->image)`. Jika kosong, berikan placeholder default.

### 4. Tampilan Antarmuka (Views)
- **`create.blade.php` & `update.blade.php` (Manajemen User)**:
  - Tambahkan atribut `enctype="multipart/form-data"` pada tag `<form>`.
  - Tambahkan Alpine.js (`x-data="{ role: '{{ old('role', $user->role ?? '') }}' }"`) untuk memantau perubahan input pilihan Role.
  - Tampilkan bagian **"Informasi Profil Kader"** secara kondisional menggunakan `x-show="role.includes('admin') || role.includes('kader')"`.
  - Desain form input profil kader dengan tampilan premium (Grid Selectable Cards untuk Pendidikan Terakhir, Live Image Preview untuk Unggah Foto, dst.).
- **`tim.blade.php` (Halaman About)**:
  - Sesuaikan panggilan fungsi JavaScript `openKaderModal` agar tidak mengirimkan parameter `hp` (No. HP).
- **`modal.blade.php` (Halaman About)**:
  - Hapus tombol "Hubungi Kader (WhatsApp)" dari tata letak modal.
  - Sesuaikan fungsi JavaScript `openKaderModal` agar tidak menerima dan memproses data `hp`.

---

## Rencana Verifikasi
1. **Verifikasi Migrasi**:
   - Jalankan `php artisan migrate` dan periksa apakah tabel `users` memiliki kolom baru serta terisi 10 data kader awal.
2. **Pengujian Fungsional (Manual)**:
   - Masuk sebagai Admin, buka menu Manajemen User.
   - Tambahkan user baru dengan role `Kader 1` dan isi biodata lengkap beserta unggah foto. Pastikan data tersebut langsung tampil di halaman public About.
   - Edit data kader tersebut dan ganti fotonya. Pastikan foto lama di storage terhapus dan foto baru terunggah dengan benar.
   - Hapus user kader tersebut. Pastikan datanya hilang dari halaman About dan file fotonya terhapus dari server.
3. **Pengujian Otomatis**:
   - Pastikan suite test yang ada tetap berjalan sukses.
