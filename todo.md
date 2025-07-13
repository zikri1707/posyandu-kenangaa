# **To-Do List - Posyandu**

## **1. Persiapan Proyek**

### 1.1 **Setup Docker**

- [X]  Buat **Dockerfile** untuk aplikasi:

  - Gunakan image dasar PHP, Nginx, Composer, dan Node.js.
  - Instal PHP, Composer, Node.js, dan dependensi lainnya.
  - Salin aplikasi ke dalam container dan jalankan `composer install` serta `npm install`.
  - Tentukan port dan expose di Dockerfile untuk menjalankan aplikasi di port 8080.
- [X]  Buat **docker-compose.yml** untuk mengelola container:

  - Tentukan service untuk aplikasi, database (PostgreSQL), Redis, dan Nginx.
  - Tentukan volume untuk menyimpan data aplikasi dan database.
- [X]  Sesuaikan **Nginx configuration** di **nginx/default.conf** untuk aplikasi Laravel.
- [X]  Jalankan perintah `docker-compose up --build` untuk membangun dan menjalankan container.
- [X]  Pastikan aplikasi berjalan di `http://localhost:8080`.

### 1.2 **Setup Versi Kontrol dan CI/CD**

- [X]  Inisialisasi Git (jika belum).
- [X]  Buat repositori di GitHub dan push proyek ke repositori tersebut.
- [X]  Sesuaikan konfigurasi GitHub Actions di folder `.github/workflows` untuk CI/CD.

---

## **2. Pengaturan Database (di dalam Docker)**

### 2.1 **Konfigurasi Database di Docker**

- [X]  Tentukan service database di **docker-compose.yml** untuk PostgreSQL.
- [X]  Sesuaikan pengaturan koneksi database di file `.env` (gunakan `DB_HOST=db` atau nama service dari Docker).
- [X]  Jalankan perintah `docker-compose up` untuk menjalankan database container.

### 2.2 **Buat Migration**

- [X]  Buat migration untuk tabel-tabel yang diperlukan:

  - Users
  - Posyandu
  - Schedule
  - Gallery
  - Patient
  - Article
  - MedicalRecord
  - Pedukuhan
- [X]  Jalankan `docker-compose exec app php artisan migrate` untuk membuat tabel di database yang berjalan di container.

### 2.3 **Buat Seeder**

- [X]  Buat seeder untuk mengisi data dummy ke dalam tabel:

  - UserSeeder
  - PosyanduSeeder
  - ScheduleSeeder
  - GallerySeeder
  - ArticleSeeder
  - MedicalRecordSeeder
  - PedukuhanSeeder
- [X]  Jalankan `docker-compose exec app php artisan db:seed` untuk mengisi data awal.

### 2.4 **Buat Factory (Opsional)**

- [X]  Jika menggunakan data dummy secara otomatis, buat factory untuk model (misalnya `docker-compose exec app php artisan make:factory`).

---

## **3. Backend Development**

### 3.1 **Buat Models**

- [X]  Buat model untuk setiap entitas yang terhubung ke tabel database:

  - **User**
  - **Posyandu**
  - **Schedule**
  - **Gallery**
  - **Patient**
  - **Article**
  - **MedicalRecord**
  - **Pedukuhan**

### 3.2 **Buat Controller**

- [ ]  Buat controller untuk setiap entitas yang menangani operasi CRUD:

  - **AdminController**
  - **PatientController**
  - **ScheduleController**
  - **GalleryController**
  - **ArticleController**
  - **MedicalRecordController**
  - **PosyanduController**
  - **PedukuhanController**

### 3.3 **Buat Request Validation**

- [ ]  Buat request validation untuk setiap form yang mengirim data:

  - **AdminRequest**
  - **PatientRequest**
  - **ScheduleRequest**
  - **GalleryRequest**
  - **ArticleRequest**
  - **MedicalRecordRequest**
  - **PedukuhanRequest**

---

## **4. Middleware dan Autentikasi**

### 4.1 **Buat Middleware**

- [ ]  Buat middleware untuk akses kontrol (misalnya Admin, SuperAdmin, Patient):

  - **AdminMiddleware**
  - **SuperAdminMiddleware**
  - **PatientMiddleware**
  - **AuthenticateMiddleware**
  - **CheckUserStatusMiddleware**
  - **VerifyEmailMiddleware**
  - **PedukuhanMiddleware**

### 4.2 **Set up Autentikasi**

- [ ]  Gunakan `php artisan make:auth` untuk membuat sistem login dan registrasi.
- [ ]  Sesuaikan pengaturan autentikasi di `routes/auth.php`.
- [ ]  Sesuaikan pengaturan otentikasi di `config/auth.php`.

---

## **5. Livewire Components (Pengelolaan Dinamis)**

### 5.1 **Buat Komponen Livewire**

- [ ]  Buat komponen Livewire untuk pengelolaan entitas secara dinamis:

  - **AdminDashboard**
  - **PatientManagement**
  - **ScheduleManagement**
  - **GalleryManagement**
  - **ArticleManagement**
  - **MedicalRecordManagement**
  - **UserManagement**
  - **PosyanduManagement**
  - **PedukuhanManagement**
  - **SearchComponent** (untuk pencarian dinamis)

### 5.2 **Integrasi dengan Controller**

- [ ]  Pastikan setiap komponen Livewire terhubung dengan controller untuk operasi CRUD.
- [ ]  Sesuaikan tampilan dan interaksi menggunakan Livewire.

---

## **6. Views dan Layouts**

### 6.1 **Buat Layout Global**

- [ ]  Buat layout utama di folder `resources/views/layouts`:

  - **app.blade.php** (untuk pengguna admin).
  - **guest.blade.php** (untuk pengguna tamu).
  - **admin-layout.blade.php** (untuk halaman admin).

### 6.2 **Halaman CRUD**

- [ ]  Buat tampilan untuk setiap entitas dengan fitur CRUD:

  - **Pasien**: index, create, update, delete, details.
  - **Jadwal**: index, create, update, delete, details.
  - **Galeri**: index, create, update, delete.
  - **Artikel**: index, create, update, delete.
  - **Catatan Medis**: index, create, update, delete.
  - **Posyandu**: index, create, update, delete.
  - **Pedukuhan**: index, create, update, delete.

### 6.3 **Komponen Livewire Views**

- [ ]  Buat tampilan untuk setiap komponen Livewire:

  - **admin-dashboard.blade.php**
  - **patient-management.blade.php**
  - **schedule-management.blade.php**
  - **gallery-management.blade.php**
  - **article-management.blade.php**
  - **medical-record-management.blade.php**
  - **pedukuhan-management.blade.php**
  - **search.blade.php** (untuk pencarian dinamis)

### 6.4 **Notifikasi dan Alert**

- [ ]  Buat tampilan untuk notifikasi sukses/error (misalnya di folder `notifications`).

---

## **7. Frontend Design dan SCSS Styling**

### 7.1 **Setup SCSS**

- [ ]  Buat file SCSS utama di `app.scss` untuk mendesain tampilan umum.
- [ ]  Definisikan variabel warna dan font di `_variables.scss`.

### 7.2 **Integrasi Volt UI**

- [ ]  Pastikan komponen-komponen Volt terintegrasi dengan benar di `config/volt.php`.
- [ ]  Gunakan Volt untuk desain frontend (jika diterapkan).

---

## **8. Routes dan API**

### 8.1 **Konfigurasi Routes**

- [ ]  Tentukan semua **routes** di `routes/web.php` dan API di `routes/api.php`.
- [ ]  Gunakan middleware yang sesuai pada setiap rute untuk autentikasi dan kontrol akses.

---

## **9. Testing dan Debugging**

### 9.1 **Unit Testing**

- [ ]  Tulis tes unit untuk setiap model dan service menggunakan `docker-compose exec app php artisan make:test`.

### 9.2 **Feature Testing**

- [ ]  Tulis tes fitur untuk menguji login, registrasi, dan pengelolaan data.
- [ ]  Verifikasi setiap alur aplikasi yang diinginkan.

### 9.3 **Pengujian Middleware dan Autentikasi**

- [ ]  Uji middleware untuk memastikan kontrol akses berfungsi dengan baik.
- [ ]  Pastikan sistem autentikasi (login, verifikasi email) bekerja dengan baik.

---

## **10. Deployment dan Konfigurasi Produksi**

### 10.1 **Optimasi untuk Produksi**

- [ ]  Jalankan `docker-compose exec app php artisan optimize` untuk optimasi aplikasi.
- [ ]  Sesuaikan pengaturan di `.env` untuk lingkungan produksi.

### 10.2 **Deploy ke Server atau Cloud**

- [ ]  Deploy aplikasi ke server atau layanan cloud (misalnya **DigitalOcean**, **AWS**, **Laravel Forge**).
- [ ]  Pastikan aplikasi berjalan dengan baik di server produksi.

### 10.3 **Pengujian Pasca-Deployment**

- [ ]  Lakukan pengujian di server produksi untuk memastikan aplikasi berjalan dengan baik.

---

## **11. Setup Docker**

### 11.1 **Buat Dockerfile**

- [ ]  Buat file **Dockerfile** dengan image dasar PHP, Nginx, Composer, dan Node.js.
- [ ]  Instal PHP, Composer, Node.js, dan dependensi lainnya.
- [ ]  Salin aplikasi ke dalam container dan jalankan `composer install` serta `npm install`.
- [ ]  Tentukan port dan expose di Dockerfile untuk menjalankan aplikasi di port 8080.

### 11.2 **Buat Docker Compose**

- [ ]  Buat file **docker-compose.yml** untuk mempermudah pengelolaan container Docker.
- [ ]  Tentukan service untuk aplikasi, Nginx, database (MySQL/PostgreSQL), dan Redis.

### 11.3 **Set Up Nginx Configuration**

- [ ]  Sesuaikan file **nginx/default.conf** untuk aplikasi Laravel.

### 11.4 **Bangun dan Jalankan Docker**

- [ ]  Jalankan perintah `docker-compose up --build` untuk membangun dan menjalankan container.
- [ ]  Akses aplikasi di `http://localhost:8080`.

---

## **12. Setup Object Storage (Cloud Storage)**

### 12.1 **Konfigurasi Cloud Storage**

- [ ]  Install package **`league/flysystem-aws-s3-v3`** atau **`backblaze/b2`**.

### 12.2 **Pengaturan di `.env`**

- [ ]  Tambahkan pengaturan untuk AWS S3 atau Backblaze B2 di file `.env`.

### 12.3 **Konfigurasi Disk di `config/filesystems.php`**

- [ ]  Tentukan konfigurasi disk untuk cloud storage di `config/filesystems.php`.

### 12.4 **Upload File ke Cloud Storage**

- [ ]  Gunakan Laravel **Storage** facade untuk mengunggah file ke cloud.

  ```php
  Storage::disk('s3')->put('file.jpg', $fileContent);
  ```

---

## **13. Maintenance dan Pembaruan**

### 13.1 **Pemantauan Aplikasi**

- [ ]  Gunakan **Laravel Telescope** atau **Sentry** untuk pemantauan aplikasi.

### 13.2 **Pembaharuan Berkala**

- [ ]  Lakukan pembaruan berkala untuk memperbaiki bug dan menambah fitur baru.
- [ ]  Perbarui dependensi menggunakan `composer update` dan `npm update` jika diperlukan.

### 13.3 **Backup Rutin**

- [ ]  Atur backup rutin untuk database dan file penting.

---
