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
│   │   │   ├── Web
│   │   │   │   ├── ArticleController.php         # Manages articles (CRUD)
│   │   │   │   ├── GalleryController.php         # Manages gallery images (CRUD)
│   │   │   │   ├── MedicalRecordController.php   # Manages medical records (CRUD)
│   │   │   │   ├── PatientController.php         # Manages patient data (CRUD)
│   │   │   │   ├── PedukuhanController.php       # Manages Pedukuhan data (CRUD)
│   │   │   │   ├── PosyanduController.php        # Manages Posyandu data (CRUD)
│   │   │   │   ├── ScheduleController.php        # Manages schedules (CRUD)
│   │   │   │   └── UserController.php            # Manages user actions for 'superadmin', 'admin', etc.
│   │   │   ├── API
│   │   │   │   ├── ArticleApiController.php         # Manages articles (CRUD)
│   │   │   │   ├── GalleryApiController.php         # Manages gallery images (CRUD)
│   │   │   │   ├── MedicalApiRecordController.php   # Manages medical records (CRUD)
│   │   │   │   ├── PatientApiController.php         # Manages patient data (CRUD)
│   │   │   │   ├── PedukuhanApiController.php       # Manages Pedukuhan data (CRUD)
│   │   │   │   ├── PosyanduApiController.php        # Manages Posyandu data (CRUD)
│   │   │   │   ├── ScheduleApiController.php        # Manages schedules (CRUD)
│   │   │   │   └── UserApiController.php            # Manages user actions for 'superadmin', 'admin', etc.
│   │   │   ├── Auth
│   │   │   │   ├── ConfirmPasswordController.php # Manages password confirmation actions
│   │   │   │   ├── LoginController.php           # Manages login actions
│   │   │   │   ├── PasswordResetController.php   # Handles password reset actions
│   │   │   │   ├── RegisterController.php        # Manages user registration
│   │   │   │   ├── VerifyEmailController.php     # Handles email verification
│   │   │   ├── Controller.php                    # Base controller class (if any)
│   │   │   └── DashboardController.php           # Dashboard controller for managing main dashboard logic
│   │   ├── Livewire/
│   │   │   ├── Auth/               # Komponen terkait autentikasi (login, register, etc.)
│   │   │   │   ├── Login.php
│   │   │   │   ├── Register.php
│   │   │   │   ├── ForgotPassword.php
│   │   │   │   ├── ResetPassword.php
│   │   │   │   ├── ConfirmPassword.php
│   │   │   │   └── VerifyEmail.php
│   │   │   ├── Admin/                         # Komponen khusus untuk dashboard admin
│   │   │   │   ├── AdminDashboard.php
│   │   │   │   ├── Management/
│   │   │   │   │   ├── PatientManagement.php
│   │   │   │   │   ├── ScheduleManagement.php
│   │   │   │   │   ├── GalleryManagement.php
│   │   │   │   │   ├── ArticleManagement.php
│   │   │   │   │   ├── MedicalRecordManagement.php
│   │   │   │   │   ├── PedukuhanManagement.php
│   │   │   │   │   ├──PosyanduManagement.php
│   │   │   │   │   └── UserManagement.php
│   │   │   ├── User/                          # Komponen untuk dashboard pengguna umum
│   │   │   │   ├── UserDashboard.php
│   │   │   │   ├── Profile/
│   │   │   │   │   ├── UserProfile.php
│   │   │   │   ├── Settings/                      # Pengaturan lainnya
│   │   │   │   │   ├── Appearance.php
│   │   │   │   │   ├── Password.php
│   │   │   │   │   ├── Profile.php
│   │   │   │   │   └── DeleteUserForm.php
│   │   │   ├── Action/                        # Komponen terkait aksi pengguna (logout, etc.)
│   │   │   │   │   ResetPassword.php
│   │   │   │   │   DeleteAccount.php
│   │   │   │   │   UpdateProfile.php
│   │   │   │   └── Logout.php
│   │   │   ├── Shared/                        # Komponen bersama yang digunakan di banyak tempat
│   │   │   │   ├── SearchComponent.php        # Fitur pencarian dinamis
│   │   │   │   └── Notification.php
│   │   │   └── Misc/                          # Komponen lain yang tidak terklasifikasi
│   │   │   │   └── ErrorPage.php
│   │   ├── Middleware                 # Middleware for request filtering and authentication
│   │   │   ├── UserMiddleware.php       # User access control based on roles
│   │   │   ├── Authenticate.php         # Authentication middleware
│   │   │   ├── CheckUserStatus.php      # Checks user status (active/inactive)
│   │   │   ├── VerifyEmailMiddleware.php # Verifies email middleware
│   │   └── Requests                    # Request validation
│   │   │   ├── UserRequest.php          # User data validation
│   │   │   ├── PatientRequest.php        # Patient data validation
│   │   │   ├── ScheduleRequest.php       # Schedule data validation
│   │   │   ├── GalleryRequest.php        # Gallery data validation
│   │   │   ├── ArticleRequest.php        # Article data validation
│   │   │   ├── MedicalRecordRequest.php  # Medical record data validation
│   │   │   ├── PosyanduRequest.php
│   │   │   └── PedukuhanRequest.php      # Pedukuhan data validation
│   │   └── Kernel.php
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
│   └── filesystems.php                  # File storage configuration
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
│   │   ├── icons                        # Static and dynamic regex icons
│   │   │   ├── static
│   │   │   ├── dynamic
│   │   └── images                       # Static and dynamic regex images
│   │   │   ├── static
│   │   │   ├── dynamic
├── resources
│   ├── views
│   │   ├── auth
│   │   │   ├── login.blade.php          # Login page view
│   │   │   ├── register.blade.php       # Register page view
│   │   │   ├── reset-password.blade.php # Reset password page view
│   │   │   ├── verify-email.blade.php   # Email verification page view
│   │   │   ├── forgot-password.blade.php # Forgot Password page view
│   │   │   └── confirm-password.blade.php   # Password confirmation page view
│   │   ├── livewire/                      # View untuk Livewire Components
│   │   │   ├── auth/                      # View untuk komponen autentikasi
│   │   │   │   ├── login.blade.php        # Komponen login
│   │   │   │   ├── register.blade.php     # Komponen register
│   │   │   │   ├── forgot-password.blade.php  # Komponen lupa password
│   │   │   │   ├── confirm-password.blade.php  # Komponen konfirmasi password
│   │   │   │   ├── verify-email.blade.php    # Komponen verifikasi email
│   │   │   │   └── reset-password.blade.php  # Komponen reset password
│   │   │   ├── settings/                  # View untuk pengaturan pengguna
│   │   │   │   ├── appearance.blade.php       # Pengaturan tampilan (appearance)
│   │   │   │   ├── delete-user-form.blade.php # Pengaturan untuk menghapus akun
│   │   │   │   ├── password.blade.php        # Pengaturan password
│   │   │   │   └── profile.blade.php         # Pengaturan profil pengguna
│   │   │   ├── admin/                     # View untuk admin dashboard dan manajemen
│   │   │   │   ├── admin-dashboard.blade.php  # Halaman utama admin dashboard
│   │   │   │   ├── patient-management/       # Manajemen Pasien
│   │   │   │   │   ├── index.blade.php      # Daftar pasien
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah pasien
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui data pasien
│   │   │   │   │   ├── details.blade.php    # Halaman detail pasien
│   │   │   │   │   ├── delete.blade.php     # Konfirmasi penghapusan pasien
│   │   │   │   │   └── search.blade.php     # Halaman pencarian pasien
│   │   │   │   ├── pedukuhan-management/    # Manajemen Pedukuhan
│   │   │   │   │   ├── index.blade.php      # Daftar pedukuhan
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah pedukuhan
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui pedukuhan
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan pedukuhan
│   │   │   │   ├── schedule-management/     # Manajemen Jadwal
│   │   │   │   │   ├── index.blade.php      # Daftar jadwal
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah jadwal
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui jadwal
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan jadwal
│   │   │   │   ├── gallery-management/      # Manajemen Galeri
│   │   │   │   │   ├── index.blade.php      # Daftar galeri
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah gambar ke galeri
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui galeri
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan gambar dari galeri
│   │   │   │   ├── article-management/      # Manajemen Artikel
│   │   │   │   │   ├── index.blade.php      # Daftar artikel
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah artikel
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui artikel
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan artikel
│   │   │   │   ├── medical-record-management/ # Manajemen Rekam Medis
│   │   │   │   │   ├── index.blade.php      # Daftar catatan medis
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah catatan medis
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui catatan medis
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan catatan medis
│   │   │   │   ├── user-management/         # Manajemen Pengguna
│   │   │   │   │   ├── index.blade.php      # Daftar pengguna
│   │   │   │   │   ├── create.blade.php     # Formulir untuk menambah pengguna
│   │   │   │   │   ├── update.blade.php     # Formulir untuk memperbarui data pengguna
│   │   │   │   │   └── delete.blade.php     # Konfirmasi penghapusan pengguna
│   │   │   ├── shared/                     # Komponen bersama untuk tampilan umum
│   │   │   │   ├── search.blade.php        # Komponen pencarian
│   │   │   │   ├── notification.blade.php  # Komponen notifikasi
│   │   │   │   └── error-page.blade.php    # Halaman error
│   │   ├── admin
│   │   │   ├── dashboard.blade.php       # Admin Dashboard main page
│   │   ├── components
│   │   │   ├── layouts                    # Layouts utama dan elemen UI umum
│   │   │   │   ├── app
│   │   │   │   │   ├── header.blade.php            # Header untuk Admin Dashboard
│   │   │   │   │   ├── sidebar.blade.php           # Sidebar untuk Admin Dashboard
│   │   │   │   │   ├── navbar.blade.php            # Navbar untuk Admin Dashboard
│   │   │   │   │   ├── footer.blade.php            # Footer untuk Admin Dashboard
│   │   │   │   │   ├── pagination.blade.php        # Pagination untuk Admin Dashboard
│   │   │   │   │   ├── breadcrumbs.blade.php       # Breadcrumbs untuk Admin Dashboard
│   │   │   │   │   ├── datacard.blade.php          # Komponen untuk menampilkan statistik
│   │   │   │   │   ├── modal.blade.php             # Modal untuk Admin Dashboard
│   │   │   │   │   ├── alert.blade.php             # Alert untuk Admin Dashboard
│   │   │   │   │   ├── button.blade.php            # Button untuk Admin Dashboard
│   │   │   │   │   ├── card.blade.php              # Card untuk menampilkan informasi statistik
│   │   │   │   │   └── table.blade.php             # Tabel untuk data (sorting, pagination)
│   │   │   │   ├── auth                         # Komponen untuk layout autentikasi
│   │   │   │   │   ├── card.blade.php            # Komponen untuk form kartu (login, register)
│   │   │   │   │   ├── simple.blade.php          # Layout autentikasi sederhana
│   │   │   │   │   ├── split.blade.php           # Layout autentikasi dengan pembagian (form & gambar)
│   │   │   │   │   ├── app.blade.php             # Layout utama untuk autentikasi
│   │   │   │   │   └── auth.blade.php            # Layout khusus untuk halaman autentikasi (login, register)
│   │   │   │   ├── settings                     # Komponen untuk pengaturan Admin
│   │   │   │   │   ├── layout.blade.php         # Layout pengaturan admin
│   │   │   │   │   └── sidebar.blade.php            # Sidebar untuk pengaturan admin
│   │   │   ├── ui                              # Komponen UI umum dan digunakan di berbagai bagian
│   │   │   │   ├── navbar.blade.php             # Navbar untuk Admin Dashboard
│   │   │   │   ├── footer.blade.php             # Footer untuk Admin Dashboard
│   │   │   │   ├── pagination.blade.php         # Pagination (umum untuk aplikasi)
│   │   │   │   ├── breadcrumbs.blade.php        # Breadcrumbs (umum untuk aplikasi)
│   │   │   │   ├── button.blade.php             # Button untuk aplikasi umum
│   │   │   │   ├── card.blade.php               # Card untuk aplikasi umum
│   │   │   │   ├── table.blade.php              # Table untuk aplikasi umum
│   │   │   │   └── progress-bar.blade.php       # Progress bar untuk aplikasi umum
│   │   │   ├── forms
│   │   │   │   ├── text-input.blade.php         # Komponen input text
│   │   │   │   ├── select-input.blade.php       # Komponen input select
│   │   │   │   ├── checkbox.blade.php           # Komponen checkbox
│   │   │   │   ├── radio-button.blade.php       # Komponen radio button
│   │   │   │   ├── file-upload.blade.php        # Komponen input file
│   │   │   │   ├── form-group.blade.php         # Form group untuk input
│   │   │   │   ├── error-message.blade.php      # Pesan error untuk input
│   │   │   │   └── validation-summary.blade.php # Ringkasan error form
│   │   │   ├── notifications
│   │   │   │   ├── success-alert.blade.php      # Success alert
│   │   │   │   ├── error-alert.blade.php        # Error alert
│   │   │   │   ├── warning-alert.blade.php      # Warning alert
│   │   │   │   └── info-alert.blade.php         # Info alert
│   │   │   ├── modals
│   │   │   │   ├── confirm-modal.blade.php      # Modal untuk konfirmasi
│   │   │   │   ├── info-modal.blade.php         # Modal informasi
│   │   │   │   └── form-modal.blade.php         # Modal untuk formulir
│   │   │   ├── widget                           # Komponen Widget dan Statistik untuk Dashboard
│   │   │   │   ├── stats-card.blade.php         # Widget untuk statistik (dashboard)
│   │   │   │   ├── chart-widget.blade.php       # Widget untuk grafik (chart.js)
│   │   │   │   └── dashboard-card.blade.php     # Widget untuk menampilkan data dalam bentuk kartu
│   │   │   ├── tables
│   │   │   │   ├── sortable-table.blade.php     # Tabel dengan fitur sorting
│   │   │   │   ├── filterable-table.blade.php   # Tabel dengan filter
│   │   │   │   └── data-table.blade.php        # Tabel dengan pencarian dan pagination
│   │   │   ├── date-time
│   │   │   │   ├── datepicker.blade.php         # Pemilih tanggal
│   │   │   │   ├── datetime-picker.blade.php    # Pemilih tanggal dan waktu
│   │   │   ├── media
│   │   │   │   ├── image-gallery.blade.php      # Galeri gambar
│   │   │   │   ├── video-player.blade.php       # Pemutar video
│   │   │   │   └── image-uploader.blade.php     # Pengunggah gambar
│   │   │   ├── settings
│   │   │   │   ├── theme-selector.blade.php     # Selector tema
│   │   │   │   └── language-selector.blade.php  # Selector bahasa
│   │   ├── layouts
│   │   │   ├── app.blade.php            # Layout utama (memuat sidebar, navbar, footer)
│   │   │   ├── guest.blade.php          # Layout untuk pengguna tamu (login, register)
│   │   │   ├── modal-layout.blade.php   # Layout untuk tampilan modal
│   │   │   └── admin-layout.blade.php   # Layout khusus untuk halaman admin
│   │   ├── vendor
│   │   │   ├── volt.blade.php           # Layout untuk komponen eksternal dari Volt
│   │   │   └── livewire-scripts.blade.php # Skrip khusus untuk Livewire
│   │   ├── partials
│   │   │   ├── head.blade.php
│   │   │   └── settings-heading.blade.php
│   │   ├── lang
│   │   ├── notifications                   # Folder untuk tampilan notifikasi
│   │   │   ├── info.blade.php              # Tampilan untuk notifikasi informasi
│   │   │   ├── success.blade.php           # Tampilan untuk notifikasi sukses
│   │   │   ├── warning.blade.php           # Tampilan untuk notifikasi warning
│   │   │   └── error.blade.php             # Tampilan untuk notifikasi error
│   │   ├── errors
│   │   │   ├── 404.blade.php               # Custom 404 error page
│   │   │   └── 500.blade.php               # Custom 500 error page
│   │   └── welcome.blade.php            # Halaman pertama setelah login
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

- **Controllers**: Menangani permintaan HTTP (CRUD) untuk entitas seperti **Admin**, **Pasien**, **Jadwal**, **Galeri**, **Artikel**, dan **Catatan Medis**.
- **Livewire**: Komponen untuk memperbarui tampilan secara dinamis menggunakan **Livewire** tanpa memuat ulang halaman.
- **Middleware**: Menyaring permintaan dan memverifikasi hak akses pengguna (misalnya **Admin**, **SuperAdmin**, **Pasien**).
- **Models**: Model Eloquent untuk berinteraksi dengan database. Mewakili entitas dan relasinya.
- **Requests**: Memvalidasi data yang dikirimkan oleh pengguna untuk memastikan data yang diterima sah.
- **Migrations**: Struktur tabel database yang diperlukan untuk aplikasi.
- **Seeders**: Mengisi database dengan data dummy atau data awal.
- **Views**: Menyimpan template tampilan aplikasi, dengan folder berbeda untuk halaman **admin**, **auth**, **livewire**, dan **components**.
- **Routes**: Menyimpan definisi rute aplikasi untuk **API**, **web**, dan **auth**.
- **Notifications**: Menyediakan notifikasi untuk pengguna aplikasi, seperti notifikasi sukses atau error.

Struktur ini telah diatur agar setiap bagian aplikasi dapat dikembangkan dan dipelihara dengan mudah, memungkinkan fleksibilitas dalam penambahan fitur atau pembaruan.
