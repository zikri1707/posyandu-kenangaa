### Struktur Folder:

```
/posyandu
├── .github                        # GitHub Actions configuration
│   └── workflows                  # GitHub Actions workflow files
├── app
│   ├── Console                     # Custom Artisan commands
│   ├── Exceptions                  # Error handling
│   ├── Http
│   │   ├── Controllers             # Controllers handling requests and responses
│   │   │   ├── Admin
│   │   │   │   ├── UserController.php       # Manages user actions for 'superadmin', 'admin', etc.
│   │   │   │   ├── PatientController.php     # Manages patient data (CRUD)
│   │   │   │   ├── ScheduleController.php    # Manages schedules (CRUD)
│   │   │   │   ├── GalleryController.php     # Manages gallery images (CRUD)
│   │   │   │   ├── ArticleController.php     # Manages articles (CRUD)
│   │   │   │   ├── MedicalRecordController.php # Manages medical records (CRUD)
│   │   │   │   ├── PosyanduController.php    # Manages Posyandu data (CRUD)
│   │   │   │   └── PedukuhanController.php   # Manages Pedukuhan data
│   │   │   ├──Auth
│   │   │   │   ├──
│   │   │       └── VerifyEmailController.php
│   │   │   ├── Controller.php
│   │   │   └── Dashboard.php
│   │   ├── Livewire                   # Livewire components
│   │   │   ├── Action
│   │   │   │   └── Logout.php
│   │   │   ├── UserDashboard.php         # User Dashboard for management
│   │   │   ├── PatientManagement.php     # Manages patient data dynamically
│   │   │   ├── ScheduleManagement.php    # Manages schedules dynamically
│   │   │   ├── GalleryManagement.php     # Manages gallery dynamically
│   │   │   ├── ArticleManagement.php     # Manages articles dynamically
│   │   │   ├── MedicalRecordManagement.php # Manages medical records dynamically
│   │   │   ├── PedukuhanManagement.php   # Manages Pedukuhan dynamically
│   │   │   └── SearchComponent.php       # Dynamic search component
│   │   ├── Middleware                 # Middleware for request filtering and authentication
│   │   │   ├── UserMiddleware.php       # User access control based on roles
│   │   │   ├── PatientMiddleware.php     # Patient access control
│   │   │   ├── Authenticate.php         # Authentication middleware
│   │   │   ├── CheckUserStatus.php      # Checks user status (active/inactive)
│   │   │   ├── VerifyEmailMiddleware.php # Verifies email middleware
│   │   │   └── PedukuhanMiddleware.php   # Pedukuhan data access control
│   │   └── Requests                    # Request validation
│   │       ├── UserRequest.php          # User data validation
│   │       ├── PatientRequest.php        # Patient data validation
│   │       ├── ScheduleRequest.php       # Schedule data validation
│   │       ├── GalleryRequest.php        # Gallery data validation
│   │       ├── ArticleRequest.php        # Article data validation
│   │       ├── MedicalRecordRequest.php  # Medical record data validation
│   │       └── PedukuhanRequest.php      # Pedukuhan data validation
│   ├── Models                          # Eloquent models for database interaction
│   │   ├── User.php                      # User model
│   │   ├── Posyandu.php                 # Posyandu model
│   │   ├── Pedukuhan.php                # Pedukuhan model
│   │   ├── Schedule.php                 # Schedule model
│   │   ├── Gallery.php                  # Gallery model
│   │   ├── Patient.php                  # Patient model
│   │   ├── Article.php                  # Article model
│   │   ├── MedicalRecord.php            # Medical record model
│   ├── Providers                       # Service providers
│   ├── Services                        # Business logic services
│   └── Policies                        # Access control policies
├── bootstrap                         # Application bootstrap files
├── config
│   ├── app.php                         # General app configuration
│   ├── auth.php                         # Authentication configuration
│   ├── database.php                     # Database configuration
│   ├── livewire.php                     # Livewire configuration
│   ├── volt.php                         # Frontend Volt integration configuration
│   └── filesystems.php                  # Cloud storage configuration (Backblaze B2)
├── database
│   ├── migrations                     # Database migration files
│   │   ├── create_users_table.php       # Users table migration
│   │   ├── create_posyandus_table.php   # Posyandu table migration
│   │   ├── create_schedules_table.php   # Schedules table migration
│   │   ├── create_galleries_table.php   # Galleries table migration
│   │   ├── create_patients_table.php    # Patients table migration
│   │   ├── create_articles_table.php    # Articles table migration
│   │   ├── create_medical_records_table.php # Medical records table migration
│   │   ├── create_pedukuhans_table.php  # Pedukuhan table migration
│   ├── factories                       # Dummy data factory files
│   └── seeders                         # Seeder files for initial data
│   │   ├── UserSeeder.php               # UserSeeder for filling user data
│   │   ├── PosyanduSeeder.php           # PosyanduSeeder for filling Posyandu data
│   │   ├── PedukuhanSeeder.php          # PedukuhanSeeder for filling Pedukuhan data
│   │   ├── ScheduleSeeder.php           # ScheduleSeeder for filling schedule data
│   │   ├── GallerySeeder.php            # GallerySeeder for filling gallery data
│   │   ├── ArticleSeeder.php            # ArticleSeeder for filling article data
│   │   ├── MedicalRecordSeeder.php      # MedicalRecordSeeder for filling medical records data
│   │   └── DatabaseSeeder.php           # Main seeder to call individual seeders
├── public
│   ├── css
│   │   └── app.css                      # Global CSS
│   ├── js                               # JavaScript files
│   └── storage                           # Static storage for images, icons
│   │   ├── icons                        # Static icons
│   │   └── images                       # Static images
├── resources
│   ├── views
│   │   ├── auth
│   │   │   ├── login.blade.php          # Login page view
│   │   │   ├── register.blade.php       # Register page view
│   │   │   ├── reset-password.blade.php # Reset password page view
│   │   │   ├── verify-email.blade.php   # Email verification page view
│   │   │   └── confirm-password.blade.php   # Password confirmation page view
│   │   ├── livewire
│   │   │   ├── admin-dashboard.blade.php # Admin Dashboard
│   │   │   ├── patient-management.blade.php # Patient management view
│   │   │   ├── schedule-management.blade.php # Schedule management view
│   │   │   ├── gallery-management.blade.php # Gallery management view
│   │   │   ├── article-management.blade.php # Article management view
│   │   │   ├── medical-record-management.blade.php # Medical record management view
│   │   │   ├── pedukuhan-management.blade.php # Pedukuhan management view
│   │   │   └── search.blade.php           # Search view
│   │   ├── admin
│   │   │   ├── dashboard.blade.php       # Admin Dashboard main page
│   │   │   ├── patient-management
│   │   │   │   ├── index.blade.php      # Daftar pasien
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah pasien
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui data pasien
│   │   │   │   ├── details.blade.php    # Tampilan detail pasien
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan pasien
│   │   │   │   ├── search.blade.php     # Hapus halaman pencarian
│   │   │   ├── pedukuhan-management
│   │   │   │   ├── index.blade.php      # Daftar pedukuhan
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah pedukuhan
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui pedukuhan
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan pedukuhan
│   │   │   ├── schedule-management
│   │   │   │   ├── index.blade.php      # Daftar jadwal
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah jadwal
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui jadwal
│   │   │   │   ├── details.blade.php    # Tampilan detail jadwal
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan jadwal
│   │   │   ├── gallery-management
│   │   │   │   ├── index.blade.php      # Daftar galeri
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah gambar ke galeri
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui galeri
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan gambar dari galeri
│   │   │   ├── article-management
│   │   │   │   ├── index.blade.php      # Daftar artikel
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah artikel
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui artikel
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan artikel
│   │   │   ├── medical-record-management
│   │   │   │   ├── index.blade.php      # Daftar catatan medis
│   │   │   │   ├── details.blade.php    # Tampilan detail catatan medis
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah catatan medis
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui catatan medis
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan catatan medis
│   │   │   ├── user-management
│   │   │   │   ├── index.blade.php      # Daftar pengguna
│   │   │   │   ├── create.blade.php     # Formulir untuk menambah pengguna
│   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui data pengguna
│   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan pengguna
│   │   ├── components
│   │   │   ├── sidebar.blade.php        # Sidebar untuk dashboard admin
│   │   │   ├── navbar.blade.php         # Navbar untuk navigasi
│   │   │   ├── footer.blade.php         # Komponen footer
│   │   │   ├── pagination.blade.php     # Komponen pagination
│   │   │   ├── breadcrumbs.blade.php    # Komponen breadcrumbs
│   │   │   ├── datacard.blade.php       # Komponen untuk menampilkan data statistik
│   │   │   ├── modal.blade.php          # Komponen modal untuk formulir tambah/ubah
│   │   │   ├── alert.blade.php          # Komponen alert untuk pesan error/sukses
│   │   │   ├── search-bar.blade.php     # Komponen pencarian
│   │   │   ├── notifications.blade.php  # Komponen untuk menampilkan notifikasi
│   │   │   ├── button.blade.php         # Komponen button reusable (primary, secondary, outline, disabled)
│   │   │   ├── tooltip.blade.php        # Komponen tooltip
│   │   │   ├── card.blade.php           # Komponen card untuk menampilkan informasi
│   │   │   ├── table.blade.php          # Komponen table untuk data dengan sorting dan pagination
│   │   │   ├── progress-bar.blade.php   # Komponen progress bar
│   │   │   ├── rating.blade.php         # Komponen rating (star rating)
│   │   │   ├── switch.blade.php         # Komponen switch (toggle)
│   │   │   ├── tabs.blade.php           # Komponen tabbed navigation
│   │   │   ├── avatar.blade.php         # Komponen avatar untuk profil
│   │   │   └── dropdown.blade.php       # Komponen dropdown menu
│   │   ├── layouts
│   │   │   ├── app.blade.php            # Layout utama (memuat sidebar, navbar, footer)
│   │   │   ├── guest.blade.php          # Layout untuk pengguna tamu (login, register)
│   │   │   ├── modal-layout.blade.php   # Layout untuk tampilan modal
│   │   │   └── admin-layout.blade.php   # Layout khusus untuk halaman admin
│   │   ├── vendor
│   │   │   ├── volt.blade.php           # Layout untuk komponen eksternal dari Volt
│   │   │   └── livewire-scripts.blade.php # Skrip khusus untuk Livewire
│   │   ├── welcome.blade.php            # Halaman pertama setelah login
│   ├── lang
│   ├── notifications                   # Folder untuk tampilan notifikasi
│   │   ├── info.blade.php              # Tampilan untuk notifikasi informasi
│   │   ├── success.blade.php           # Tampilan untuk notifikasi sukses
│   │   ├── warning.blade.php           # Tampilan untuk notifikasi warning
│   │   └── error.blade.php             # Tampilan untuk notifikasi error
│   ├── errors
│   │   ├── 404.blade.php               # Custom 404 error page
│   │   └── 500.blade.php               # Custom 500 error page
├── routes
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   ├── web.php
│   └── auth.php                # Rute untuk autentikasi (login, register, logout)
├── storage
│   ├── app
│   ├── framework
│   └── logs
├── tests
│   ├── Feature
│   │   ├── AdminTest.php
│   │   ├── PatientTest.php
│   │   ├── ScheduleTest.php
│   │   ├── GalleryTest.php
│   │   ├── ArticleTest.php
│   │   ├── MedicalRecordTest.php
│   │   └── PosyanduTest.php
│   └── Pest
│       └── AdminManagementTest.php
├── .env
├── artisan
├── composer.json
├── docker-compose.yml
└── Dockerfile
```

### **Penjelasan Komentar:**

* **Controllers**: Menangani permintaan HTTP (CRUD) untuk entitas seperti **Admin**, **Pasien**, **Jadwal**, **Galeri**, **Artikel**, dan **Catatan Medis**.
* **Livewire**: Komponen untuk memperbarui tampilan secara dinamis menggunakan **Livewire** tanpa memuat ulang halaman.
* **Middleware**: Menyaring permintaan dan memverifikasi hak akses pengguna (misalnya **Admin**, **SuperAdmin**, **Pasien**).
* **Models**: Model Eloquent untuk berinteraksi dengan database. Mewakili entitas dan relasinya.
* **Requests**: Memvalidasi data yang dikirimkan oleh pengguna untuk memastikan data yang diterima sah.
* **Migrations**: Struktur tabel database yang diperlukan untuk aplikasi.
* **Seeders**: Mengisi database dengan data dummy atau data awal.
* **Views**: Menyimpan template tampilan aplikasi, dengan folder berbeda untuk halaman **admin**, **auth**, **livewire**, dan **components**.
* **Routes**: Menyimpan definisi rute aplikasi untuk **API**, **web**, dan **auth**.
* **Notifications**: Menyediakan notifikasi untuk pengguna aplikasi, seperti notifikasi sukses atau error.

Struktur ini telah diatur agar setiap bagian aplikasi dapat dikembangkan dan dipelihara dengan mudah, memungkinkan fleksibilitas dalam penambahan fitur atau pembaruan.
