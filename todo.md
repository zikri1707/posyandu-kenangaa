# To-Do List - Posyandu

## 1. Persiapan Proyek

### 1.1 Setup Docker

-   [x] Buat Dockerfile:
    -   Gunakan image dasar PHP, Nginx, Composer, dan Node.js.
    -   Instal PHP, Composer, Node.js, dan dependensi lainnya.
    -   Salin aplikasi ke dalam container dan jalankan `composer install` serta `npm install`.
    -   Tentukan port dan expose di Dockerfile untuk menjalankan aplikasi di port 8080.
-   [x] Buat docker-compose.yml:
    -   Tentukan service untuk aplikasi, database (PostgreSQL), Redis, dan Nginx.
    -   Tentukan volume untuk menyimpan data aplikasi dan database.
-   [x] Sesuaikan Nginx configuration di nginx/default.conf untuk aplikasi Laravel.
-   [x] Jalankan perintah `docker-compose up --build` untuk membangun dan menjalankan container.
-   [x] Pastikan aplikasi berjalan di http://localhost:8080.

### 1.2 Setup Versi Kontrol dan CI/CD

-   [x] Inisialisasi Git (jika belum).
-   [x] Buat repositori di GitHub dan push proyek ke repositori tersebut.
-   [x] Sesuaikan konfigurasi GitHub Actions di folder .github/workflows untuk CI/CD.

## 2. Pengaturan Database (di dalam Docker)

### 2.1 Konfigurasi Database di Docker

-   [x] Tentukan service database di docker-compose.yml untuk PostgreSQL.
-   [x] Sesuaikan pengaturan koneksi database di file .env (gunakan DB_HOST=db atau nama service dari Docker).
-   [x] Jalankan perintah docker-compose up untuk menjalankan database container.

### 2.2 Buat Migration

-   [x] Buat migration untuk tabel-tabel yang diperlukan:
    -   Users
    -   Posyandu
    -   Schedule
    -   Gallery
    -   Patient
    -   Article
    -   MedicalRecord
    -   Pedukuhan
-   [x] Jalankan docker-compose exec app php artisan migrate untuk membuat tabel di database yang berjalan di container.

### 2.3 Buat Seeder

-   [x] Buat seeder untuk mengisi data dummy ke dalam tabel:
    -   UserSeeder
    -   PosyanduSeeder
    -   ScheduleSeeder
    -   GallerySeeder
    -   ArticleSeeder
    -   MedicalRecordSeeder
    -   PedukuhanSeeder
-   [x] Jalankan docker-compose exec app php artisan db:seed untuk mengisi data awal.

### 2.4 Buat Factory (Opsional)

-   [x] Jika menggunakan data dummy secara otomatis, buat factory untuk model (misalnya docker-compose exec app php artisan make:factory).

## 3. Frontend Design dan SCSS Styling

### 3.1 Setup SCSS untuk Global Styles

-   [ ] Buat file SCSS utama di app.scss:
    -   Definisikan font dan warna global untuk aplikasi.
    -   Atur gaya umum untuk elemen-elemen dasar (seperti body, heading, button, dll).
-   [ ] Definisikan variabel warna dan font di \_variables.scss:
    -   Tentukan variabel warna seperti primary-color, secondary-color, dll.
    -   Tentukan variabel font seperti primary-font, secondary-font, dll.

## 4. Backend Development

### 4.1 Buat Models

-   [x] Buat model untuk setiap entitas yang terhubung ke tabel database:
    -   User
    -   Posyandu
    -   Schedule
    -   Gallery
    -   Patient
    -   Article
    -   MedicalRecord
    -   Pedukuhan

### 4.2 Buat Controller

-   [x] Buat controller untuk setiap entitas yang menangani operasi CRUD:
    -   AdminController
    -   PatientController
    -   ScheduleController
    -   GalleryController
    -   ArticleController
    -   MedicalRecordController
    -   PosyanduController
    -   PedukuhanController

### 4.3 Buat Request Validation

-   [x] Buat request validation untuk setiap form yang mengirim data:
    -   AdminRequest
    -   PatientRequest
    -   ScheduleRequest
    -   GalleryRequest
    -   ArticleRequest
    -   MedicalRecordRequest
    -   PedukuhanRequest

## 5. Middleware dan Autentikasi

### 5.1 Buat Middleware

-   [x] Buat middleware untuk akses kontrol (misalnya Admin, SuperAdmin, Patient):
    -   AdminMiddleware
    -   SuperAdminMiddleware
    -   PatientMiddleware
    -   AuthenticateMiddleware
    -   CheckUserStatusMiddleware
    -   VerifyEmailMiddleware
    -   PedukuhanMiddleware

### 5.2 Set up Autentikasi

-   [ ] Gunakan php artisan make:auth untuk membuat sistem login dan registrasi.
-   [ ] Sesuaikan pengaturan autentikasi di routes/auth.php.
-   [ ] Sesuaikan pengaturan otentikasi di config/auth.php.

## 6. Livewire Components (Pengelolaan Dinamis)

### 6.1 Buat Komponen Livewire

-   [ ] Buat komponen Livewire untuk pengelolaan entitas secara dinamis:
    -   AdminDashboard
    -   PatientManagement
    -   ScheduleManagement
    -   GalleryManagement
    -   ArticleManagement
    -   MedicalRecordManagement
    -   UserManagement
    -   PosyanduManagement
    -   PedukuhanManagement
    -   SearchComponent (untuk pencarian dinamis)

### 6.2 Integrasi dengan Controller

-   [ ] Pastikan setiap komponen Livewire terhubung dengan controller untuk operasi CRUD.
-   [ ] Sesuaikan tampilan dan interaksi menggunakan Livewire.

## 7. Views dan Layouts

### 7.1 Buat Layout Global

-   [ ] Buat layout utama di folder resources/views/layouts:
    -   app.blade.php (untuk pengguna admin).
    -   guest.blade.php (untuk pengguna tamu).
    -   admin-layout.blade.php (untuk halaman admin).

### 7.2 Halaman CRUD

-   [ ] Buat tampilan untuk setiap entitas dengan fitur CRUD:
    -   Pasien: index, create, update, delete, details.
    -   Jadwal: index, create, update, delete, details.
    -   Galeri: index, create, update, delete.
    -   Artikel: index, create, update, delete.
    -   Catatan Medis: index, create, update, delete.
    -   Posyandu: index, create, update, delete.
    -   Pedukuhan: index, create, update, delete.

### 7.3 Komponen Livewire Views

-   [ ] Buat tampilan untuk setiap komponen Livewire:
    -   admin-dashboard.blade.php
    -   patient-management.blade.php
    -   schedule-management.blade.php
    -   gallery-management.blade.php
    -   article-management.blade.php
    -   medical-record-management.blade.php
    -   pedukuhan-management.blade.php
    -   search.blade.php (untuk pencarian dinamis)

### 7.4 Notifikasi dan Alert

-   [ ] Buat tampilan untuk notifikasi sukses/error (misalnya di folder notifications).

## 8. Routes dan API

### 8.1 Konfigurasi Routes

-   [ ] Tentukan semua routes di routes/web.php dan API di routes/api.php.
-   [ ] Gunakan middleware yang sesuai pada setiap rute untuk autentikasi dan kontrol akses.

## 9. Testing dan Debugging

### 9.1 Unit Testing

-   [ ] Tulis tes unit untuk setiap model dan service menggunakan docker-compose exec app php artisan make:test.

### 9.2 Feature Testing

-   [ ] Tulis tes fitur untuk menguji login, registrasi, dan pengelolaan data.
-   [ ] Verifikasi setiap alur aplikasi yang diinginkan.

### 9.3 Pengujian Middleware dan Autentikasi

-   [ ] Uji middleware untuk memastikan kontrol akses berfungsi dengan baik.
-   [ ] Pastikan sistem autentikasi (login, verifikasi email) bekerja dengan baik.

## 10. Deployment dan Konfigurasi Produksi

### 10.1 Optimasi untuk Produksi

-   [x] Jalankan docker-compose exec app php artisan optimize untuk optimasi aplikasi.
-   [x] Sesuaikan pengaturan di .env untuk lingkungan produksi.

### 10.2 Deploy ke Server atau Cloud

-   [ ] Deploy aplikasi ke server atau layanan cloud (misalnya DigitalOcean, AWS, Laravel Forge).
-   [ ] Pastikan aplikasi berjalan dengan baik di server produksi.

### 10.3 Pengujian Pasca-Deployment

-   [ ] Lakukan pengujian di server produksi untuk memastikan aplikasi berjalan dengan baik.

## 11. Setup Object Storage (Cloud Storage)

### 11.1 Konfigurasi Cloud Storage

-   [ ] Install package league/flysystem-aws-s3-v3 atau backblaze/b2.

### 11.2 Pengaturan di .env

-   [ ] Tambahkan pengaturan untuk AWS S3 atau Backblaze B2 di file .env.

### 11.3 Konfigurasi Disk di config/filesystems.php

-   [ ] Tentukan konfigurasi disk untuk cloud storage di config/filesystems.php.

### 11.4 Upload File ke Cloud Storage

-   [ ] Gunakan Laravel Storage facade untuk mengunggah file ke cloud.

## 12. Maintenance dan Pembaruan

### 12.1 Pemantauan Aplikasi

-   [ ] Gunakan Laravel Telescope atau Sentry untuk pemantauan aplikasi.

### 12.2 Pembaharuan Berkala

-   [ ] Lakukan pembaruan berkala untuk memperbaiki bug dan menambah fitur baru.
-   [ ] Perbarui dependensi menggunakan composer update dan npm update jika diperlukan.

### 12.3 Backup Rutin

-   [ ] Atur backup rutin untuk database dan file penting.

---
