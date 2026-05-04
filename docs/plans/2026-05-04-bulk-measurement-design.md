# Design Document: Bulk Measurement Entry (Bulan Penimbangan)

## Overview
Fitur ini bertujuan untuk mendigitalisasi proses fisik "Bulan Penimbangan Balita" (BPB) di mana kader Posyandu memasukkan data berat badan (BB) dan tinggi badan (TB) untuk banyak anak sekaligus dalam satu sesi.

## Architecture
- **Component:** `App\Livewire\Admin\MedicalRecord\BulkMeasurementEntry`
- **Route:** `/admin/medical-records/bulk`
- **Model Interaction:** Menciptakan multiple entries pada tabel `medical_records`.

## UI/UX Design (Bento Style)
- **Filters:** Dropdown Posyandu dan Input Date (Bulan/Tahun).
- **Table Structure:**
    - `No`
    - `Nama Balita` (Read-only)
    - `Nama Ortu` (Read-only)
    - `Usia (Bln)` (Auto-calc)
    - `BB Terakhir` (Reference)
    - `TB Terakhir` (Reference)
    - `BB Baru (Kg)` (Input)
    - `TB Baru (cm)` (Input)
    - `Cara Ukur` (Select: Berdiri/Terlentang)
    - `Status Gizi` (Auto-indicator)
- **Navigation:** Mendukung penggunaan `Tab` dan tombol arah untuk berpindah antar input.
- **Actions:** Tombol "Simpan Semua Data" dengan konfirmasi modal.

## Data Flow
1. User memilih Posyandu dan Tanggal.
2. Sistem mengambil semua `Patient` di Posyandu tersebut.
3. User mengisi data BB/TB.
4. Saat "Simpan" diklik:
    - Validasi semua input (Numerik, range wajar).
    - Cek duplikasi data untuk pasien + tanggal yang sama.
    - Loop dan `MedicalRecord::create()`.
    - Redirect ke riwayat atau tampilkan notifikasi sukses.

## Error Handling
- Validasi baris demi baris: Jika satu baris error (misal BB 1000kg), baris tersebut akan ditandai merah dan proses simpan dibatalkan untuk mencegah data parsial yang korup.
