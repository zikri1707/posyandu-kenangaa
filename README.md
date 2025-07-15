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

- **Backend**: Laravel 8.x
- **Frontend**: Tailwind CSS, Blade Templating Engine
- **Livewire**: Untuk komponen dinamis dan interaktif di frontend
- **Database**: MySQL atau SQLite
- **Authentication**: Laravel Breeze untuk autentikasi pengguna
- **Version Control**: Git, GitHub

## Instalasi

1. **Clone Repository**:
   Pertama, clone repository ini ke komputer lokal Anda menggunakan perintah Git:
   
   ```bash
   git clone https://github.com/ahmadrizal1st/posyandu-admin-dashboard.git
   cd posyandu-admin-dashboard
   ```

2. **Instalasi Dependencies**:
   Pastikan Anda sudah menginstal Composer dan npm di sistem Anda. Kemudian jalankan perintah berikut untuk menginstal dependencies:
   ```bash
   composer install
   npm install
`

3. **Buat File `.env`**:
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database dan konfigurasi lainnya.
   
   ```bash
   cp .env.example .env
   ```
4. **Generate Key Aplikasi**:
   Laravel membutuhkan key aplikasi yang unik. Jalankan perintah berikut untuk menggenerasi key:
   
   ```bash
   php artisan key:generate
   ```
5. **Migrasi Database**:
   Setelah konfigurasi database selesai, jalankan migrasi untuk membuat tabel yang diperlukan:
   
   ```bash
   php artisan migrate
   ```
6. **Menjalankan Aplikasi**:
   Anda dapat menjalankan aplikasi secara lokal dengan menggunakan perintah artisan berikut:
   
   ```bash
   php artisan serve
   ```
   
   Akses aplikasi melalui `http://127.0.0.1:8000` di browser.

## Kontribusi

Kami menyambut kontribusi dari komunitas untuk meningkatkan aplikasi ini. Berikut adalah langkah-langkah untuk berkontribusi:

1. Fork repository ini.
2. Buat branch baru untuk fitur atau perbaikan yang Anda kerjakan.
3. Lakukan perubahan dan pastikan semua pengujian berfungsi dengan benar.
4. Kirim pull request ke branch `develop`.

## Repository GitHub

[https://github.com/ahmadrizal1st/posyandu-admin-dashboard.git](https://github.com/ahmadrizal1st/posyandu-admin-dashboard.git)

### Penjelasan Perubahan:

- Bagian **Lisensi** dihapus dari file `README.md`.
- Sisanya tetap sama, memberikan panduan lengkap untuk pengguna dan developer yang ingin menginstal, mengonfigurasi, dan berkontribusi pada proyek ini.

### Deadline pengerjaan pada minggu ke-3 bulan Juli 2025 dikarenakan untuk memenuhi Ujian Akhir Semester (UAS) mata kuliah pemrograman website

