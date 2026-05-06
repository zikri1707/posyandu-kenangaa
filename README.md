# Website ini akan dibuat saat pengerjaan tugas mata kuliah pemrograman website

Berikut adalah pembaruan file **`README.md`** tanpa bagian **Lisensi**:

# Posyandu - Website Management for Rekap Data Rekam Medis

## Deskripsi

**Posyandu** adalah aplikasi web berbasis Laravel yang dirancang untuk mempermudah pengelolaan data rekam medis di posyandu. Sistem ini memberikan kemudahan bagi petugas kesehatan dan administrator dalam mengelola dan merekap data pasien, jadwal layanan, artikel edukasi, galeri, serta informasi lainnya yang berkaitan dengan layanan posyandu.

Dengan menggunakan aplikasi ini, para petugas dan pengelola posyandu dapat dengan cepat mengakses dan memanipulasi data pasien, jadwal pemeriksaan, serta berbagai informasi medis lainnya. Selain itu, sistem ini juga menyediakan fitur otentikasi yang memungkinkan akses terbatas berdasarkan peran pengguna seperti **superadmin**, **admin**, **coordinator**, **staff**, **medical**, **patient**, dan **partner**.

Aplikasi ini dibangun dengan menggunakan **Laravel** untuk backend dan **Tailwind CSS** untuk styling frontend.

## Fitur Utama

1. **Manajemen Data Pasien**:
   
   - Menambahkan, memperbarui, dan menghapus data pasien.
   - Melihat informasi lengkap mengenai pasien termasuk rekam medis.
2. **Manajemen Jadwal**:
   
   - Pengaturan jadwal layanan posyandu.
   - Menambah, memperbarui, dan menghapus jadwal layanan.
3. **Manajemen Rekam Medis**:
   
   - Pengelolaan catatan medis pasien.
   - Menambah, memperbarui, dan menghapus catatan medis.
4. **Manajemen Artikel**:
   
   - Pengelolaan artikel edukasi tentang kesehatan posyandu.
   - Menambahkan artikel untuk meningkatkan pemahaman masyarakat.
5. **Manajemen Galeri**:
   
   - Pengelolaan gambar untuk galeri posyandu.
   - Mengupload dan mengelola gambar yang berkaitan dengan kegiatan posyandu.
6. **Autentikasi Pengguna**:
   
   - Sistem login dan registrasi untuk petugas kesehatan dan administrator.
   - Verifikasi email dan reset password untuk keamanan pengguna.
7. **Role-Based Access Control (RBAC)**:
   
   - Pengelolaan akses berdasarkan peran pengguna (misal: superadmin, admin, coordinator, staff, medical, patient, dan partner).
8. **Livewire Components**:
   
   - Menggunakan Livewire untuk mengelola interaksi dinamis pada antarmuka pengguna.
9. **Error Handling**:
   
   - Pengelolaan error 401, 403, 404, 419, dan 500 untuk pengalaman pengguna yang lebih baik.

## Teknologi yang Digunakan

- **Backend**: Laravel 12.x
- **Frontend**: Tailwind CSS, Blade Templating Engine
- **Livewire**: Untuk komponen dinamis dan interaktif di frontend (Flux & Volt)
- **Database**: MySQL (Default) atau SQLite
- **Authentication**: Laravel Fortify/Custom Auth
- **Version Control**: Git, GitHub

## Instalasi (Plug & Play)

Perintah ini akan membantu mengotomatisasi instalasi awal:

```bash
composer install
composer setup
php artisan serve
```

> [!IMPORTANT]
> Karena menggunakan **MySQL**, teman Anda harus membuat database kosong bernama `posyandu_admin` (atau sesuai konfigurasi `.env`) terlebih dahulu sebelum menjalankan `composer setup`.

Perintah `composer setup` akan secara otomatis:
1. Membuat file `.env` dari `.env.example`.
2. Menghasilkan App Key.
3. Menjalankan migrasi dan **seeding** data awal (Admin, Data WHO, dll).
4. Menginstal dependencies frontend dan melakukan build.

Setelah itu, buka `http://127.0.0.1:8000` di browser.

### Login Default
- **Email**: `admin@posyandu.local`
- **Password**: `password`

## Kontribusi

Kami menyambut kontribusi dari komunitas untuk meningkatkan aplikasi ini. Berikut adalah langkah-langkah untuk berkontribusi:

1. Fork repository ini.
2. Buat branch baru untuk fitur atau perbaikan yang Anda kerjakan.
3. Lakukan perubahan dan pastikan semua pengujian berfungsi dengan benar.
4. Kirim pull request ke branch `develop`.

## Repository GitHub

[https://github.com/Alarave/posyandu-admin.git](https://github.com/Alarave/posyandu-admin.git)

### Penjelasan Perubahan:

- Bagian **Lisensi** dihapus dari file `README.md`.
- Sisanya tetap sama, memberikan panduan lengkap untuk pengguna dan developer yang ingin menginstal, mengonfigurasi, dan berkontribusi pada proyek ini.

### Deadline pengerjaan pada minggu ke-3 bulan Juli 2025 dikarenakan untuk memenuhi Ujian Akhir Semester (UAS) mata kuliah pemrograman website

