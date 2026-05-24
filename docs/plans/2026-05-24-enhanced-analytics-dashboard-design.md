# Design Document - Dashboard Analitik & Wawasan Terintegrasi Database

Meningkatkan kualitas visual dan menambahkan wawasan analitik terperinci untuk kategori Balita, Ibu Hamil, dan Lansia pada dashboard analitik admin, yang terhubung langsung ke database.

## Latar Belakang & Tujuan
Dashboard analitik saat ini didominasi oleh statistik Balita dan belum menyajikan analitik mendalam untuk sasaran posyandu lainnya seperti Ibu Hamil dan Lansia. Kami akan merombak halaman `admin/analytics` menjadi dashboard interaktif berbasis tab yang menyajikan analitik klinis lengkap (hipertensi kehamilan, risiko metabolik lansia, dll.) bersumber langsung dari database melalui kueri rekam medis.

## Keputusan Desain & Batasan
1. **Pendekatan Arsitektur**: Single Livewire component dengan tab dinamis (`overview`, `balita`, `ibu_hamil`, `lansia`) yang dikontrol oleh state `$activeTab`.
2. **Indikator Klinis (Standard Kemenkes ILP)**:
   - **Ibu Hamil**: Kepatuhan Konsumsi Pil Fe (`pill_fe = true`), Risiko Hipertensi Kehamilan (Sistolik $\ge 140$ atau Diastolik $\ge 90$).
   - **Lansia**: Hipertensi (Sistolik $\ge 140$ atau Diastolik $\ge 90$), Hiperglikemia (Gula Darah $\ge 200$), Hiperkolesterolemia (Kolesterol $\ge 200$), Hiperurisemia (Asam Urat $\ge 7.0$).
3. **Penyimpanan Snapshot (Cache)**: Data analitik untuk semua tab akan di-compile secara efisien di job `ComputeAnalyticsSnapshot` untuk disimpan dalam tabel `analytics_snapshots` agar performa kueri dashboard tetap cepat.

---

## Rincian Perubahan

### 1. Model & Logika Pengolahan Data
- **`ComputeAnalyticsSnapshot.php`**:
  - Perbarui fungsi `computeAnalyticsData()` untuk menghitung statistik tambahan:
    - **Overview**: Kunjungan gabungan bulanan (tren rekam medis untuk balita, ibu hamil, dan lansia).
    - **Ibu Hamil**: Prevalensi hipertensi kehamilan, kepatuhan pil Fe, dan tren kepatuhan zat besi bulanan.
    - **Lansia**: Prevalensi metabolisme abnormal (Hipertensi, Kolesterol tinggi, Gula tinggi, Asam urat tinggi) secara persentase dan tren bulanan.
- **`Analytics.php` (Livewire Component)**:
  - Tambahkan properti `$activeTab` (default: `'overview'`).
  - Impor data analitik terbaru dari snapshot atau hitung secara live menggunakan logika baru.
  - Tambahkan event dispatcher `charts-updated` untuk mendistribusikan data tren kunjungan, tren balita, tren ibu hamil, dan risiko lansia ke frontend Chart.js.

### 2. Antarmuka Pengguna (Views)
- **`analytics.blade.php`**:
  - **Header & Filter**: Sempurnakan desain header dengan gradient mesh dan penyeleksi Tahun/Bulan yang premium.
  - **Menu Tab**: Tambahkan bar tab navigasi dinamis dengan transisi membulat yang mewah.
  - **Konten Tab**:
    - **Ringkasan (Overview)**: Tampilkan grafik tren kunjungan gabungan 12 bulan (Chart.js Line) dan diagram demografi warga.
    - **Balita**: Kelompokkan grafik gizi, imunisasi, dan segmentasi usia balita yang sudah ada.
    - **Ibu Hamil**: Tampilkan metrik kepatuhan Pil Fe, persentase hipertensi, grafik tren kepatuhan & risiko bulanan, serta daftar ibu hamil dengan risiko tinggi.
    - **Lansia**: Tampilkan metrik metabolik lansia, grafik batang berkelompok (Grouped Bar Chart) risiko kesehatan lansia bulanan, dan tabel rekam medis lansia terbaru.
  - **Inisialisasi Chart.js**: Perbarui script inisialisasi grafik agar mendeteksi canvas aktif pada tab yang dipilih, melakukan destroy grafik lama, dan membuat grafik baru dengan skema warna yang sesuai tema tab.

---

## Rencana Verifikasi
1. **Verifikasi Kueri**:
   - Jalankan `php artisan tinker` untuk memverifikasi perhitungan rate hipertensi kehamilan dan risiko metabolik lansia dari database.
2. **Pengujian Visual (Manual)**:
   - Akses halaman `admin/analytics`.
   - Pastikan tab navigasi berfungsi dengan transisi responsif tanpa lag.
   - Periksa apakah Chart.js ter-render dengan benar saat berpindah tab.
3. **Pengujian Otomatis**:
   - Pastikan suite test default tetap lulus.
