# Dashboard Analitik & Wawasan Terintegrasi Database Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Mengimplementasikan dashboard analitik berbasis tab interaktif yang menyajikan statistik mendalam untuk kategori Balita, Ibu Hamil, dan Lansia, terhubung langsung ke database.

**Architecture:** Memperbarui pengolahan snapshot data analitik di `ComputeAnalyticsSnapshot` untuk menghitung indikator klinis baru, memperbarui Livewire component `Analytics.php` untuk mendukung tab interaktif dan mengambil data baru, serta memperbarui blade view `analytics.blade.php` dengan desain UI premium dan grafik Chart.js terpisah untuk setiap tab.

**Tech Stack:** Laravel, Blade, Livewire, Alpine.js, Tailwind CSS, Chart.js.

---

### Task 1: Update Job `ComputeAnalyticsSnapshot.php`

**Files:**
- Modify: `app/Jobs/ComputeAnalyticsSnapshot.php`

**Step 1: Update `computeAnalyticsData()` to calculate all detailed indicators**
Sunting `app/Jobs/ComputeAnalyticsSnapshot.php` untuk menambahkan perhitungan analitik bulanan dan kumulatif berikut:
- **Kunjungan Bulanan Gabungan**: hitung jumlah rekam medis per bulan untuk masing-masing kategori (`balita`, `ibu_hamil`, `lansia`).
- **Analitik Ibu Hamil**: 
  - Kepatuhan Pil Fe (persentase `pill_fe = 1` dari total rekam medis kunjungan terbaru per pasien).
  - Prevalensi Hipertensi Kehamilan (persentase pasien dengan sistolik $\ge 140$ ATAU diastolik $\ge 90$ dari rekam medis kunjungan terbaru).
  - Tren bulanan kepatuhan Fe dan hipertensi.
- **Analitik Lansia**:
  - Prevalensi penyakit metabolik dari kunjungan terbaru lansia: Hipertensi, Hiperglikemia (gula darah $\ge 200$), Hiperkolesterolemia (kolesterol $\ge 200$), Hiperurisemia (asam urat $\ge 7.0$).
  - Tren bulanan persentase risiko metabolik lansia.
- **Capaian Imunisasi**: Hitung detail imunisasi per jenis vaksin ( HB-0, BCG, Polio 1-4, DPT-HB-Hib 1-3, PCV 1-3, RV 1-3, IPV 1-2, MR) yang sudah diimplementasikan di model, dan pastikan datanya dimasukkan ke snapshot.

**Step 2: Commit**
Commit changes.

---

### Task 2: Update Livewire Component `Analytics.php`

**Files:**
- Modify: `app/Livewire/Admin/Analytics.php`

**Step 1: Add tab properties and update data binding**
Sunting `app/Livewire/Admin/Analytics.php`:
- Tambahkan properti:
  - `public string $activeTab = 'overview';`
  - Properti data baru (misal `totalIbuHamil`, `totalLansia`, `totalKunjungan`, `hypertensionRiskRate`, `feComplianceRate`, dll.).
  - Properti tren bulanan baru untuk grafik Chart.js.
  - Properti data tabel terbaru: `public $recentPregnancyRecords;`, `public $recentLansiaRecords;`.
- Perbarui `loadData()` untuk memuat data baru dari database/snapshot dan memancarkan event `charts-updated` dengan data lengkap dari tab aktif.
- Perbarui `fetchAnalyticsData()` agar memiliki logika perhitungan data live yang persis sama dengan yang ada di `ComputeAnalyticsSnapshot.php` (untuk fallback saat snapshot kosong).
- Batasi kueri data rekam medis terbaru (`recentRecords`, `recentPregnancyRecords`, `recentLansiaRecords`) berdasarkan cakupan posyandu pengguna.

**Step 2: Commit**
Commit changes.

---

### Task 3: Refactor Livewire Blade View `analytics.blade.php`

**Files:**
- Modify: `resources/views/livewire/admin/analytics.blade.php`

**Step 1: Add tab menu and conditionally display tab contents**
Sunting `resources/views/livewire/admin/analytics.blade.php`:
- Tambahkan navigasi tab kapsul premium di bawah header:
  - Tab 1: Ringkasan (Overview)
  - Tab 2: Balita & Anak
  - Tab 3: Ibu Hamil
  - Tab 4: Lansia
- Gunakan `@if($activeTab === 'overview')` dll. untuk membungkus konten masing-masing tab secara dinamis.
- Rancang UI premium untuk tab Ibu Hamil dan Lansia:
  - Tambahkan kartu statistik dengan warna aksen yang sesuai (rose untuk hamil, indigo/purple untuk lansia).
  - Tampilkan tabel rekam medis terbaru dengan status ber-badge dinamis.
- Sempurnakan kode inisialisasi Chart.js di bagian script:
  - Buat fungsi `initCharts()` yang mendeteksi canvas mana saja yang ada di DOM saat ini.
  - Tambahkan grafik baru: *Line Chart Kunjungan Gabungan* (Tab Overview), *Line Chart Kepatuhan & Risiko* (Tab Ibu Hamil), dan *Grouped Bar Chart Risiko Metabolisme* (Tab Lansia).
  - Pastikan memanggil `chartInstance.destroy()` sebelum menggambar ulang grafik untuk menghindari error canvas tumpang-tindih.

**Step 2: Commit**
Commit changes.

---

### Task 4: Run Verification & Automated Tests

**Files:**
- Test: `tests/Feature/Public/PublicPageTest.php`
- Run test: `php artisan test`

**Step 1: Run Test Suite**
Jalankan test suite default:
Run: `php artisan test`
Expected: PASS

**Step 2: Manual Verification**
1. Jalankan server lokal posyandu.
2. Buka halaman analitik admin.
3. Klik antar-tab navigasi dan pastikan grafik termuat dengan mulus.
4. Input data rekam medis baru untuk lansia (dengan gula darah tinggi) and pastikan angkanya langsung tercermin di tab Lansia setelah menekan tombol "Update".
