# FORM UAT (User Acceptance Test) - Checklist

| Info Pengujian |
| :--- |
| **Kegiatan** : Pengujian Penerimaan Pengguna (UAT) - Sistem Informasi Posyandu |
| **Pekerjaan** : Verifikasi Kelayakan Fungsional Sistem Terintegrasi |
| **Tanggal** : 2026-06-30 |
| **User** : Superadmin / Admin / Kader |

---

## Form UAT (Checklist Fungsionalitas)

| No | Yang Diuji | Fungsi | Deskripsi Pengujian | Skenario Pengujian | Hasil | Checklist / Paraf | Keterangan |
|:---:|:---|:---|:---|:---|:---:|:---:|:---|
| **1** | **Autentikasi** | Login Multi-role | Memverifikasi apakah pengguna dengan berbagai role dapat masuk ke sistem dengan benar. | 1. Buka halaman `/login`<br>2. Input email: `admin@posyandu.local` dan password: `password`<br>3. Klik login. | [ ] Berhasil<br>[ ] Gagal | [ ] | Diarahkan ke Dashboard |
| **2** | **Autentikasi** | Throttle Rate Limit | Menguji apakah sistem memblokir login setelah 5 kali gagal untuk mencegah brute force. | 1. Ketik email valid dan password salah sebanyak 5 kali berturut-turut.<br>2. Lihat respon pada percobaan ke-6. | [ ] Berhasil<br>[ ] Gagal | [ ] | Muncul error lock-out 60 detik |
| **3** | **Dashboard** | Visualisasi Summary | Memeriksa apakah widget data (jumlah balita, kader, posyandu, jadwal) tampil dengan data riil. | 1. Login berhasil.<br>2. Lihat panel widget dashboard utama dan grafik data yang termuat. | [ ] Berhasil<br>[ ] Gagal | [ ] | Data ter-render dinamis |
| **4** | **Master Posyandu** | Kelola Posyandu (CRUD) | Menguji pembuatan, pengubahan, dan penghapusan data unit Posyandu di wilayah kerja. | 1. Buka menu Posyandu.<br>2. Tambahkan unit baru (Nama, Alamat).<br>3. Simpan, edit data, dan uji hapus. | [ ] Berhasil<br>[ ] Gagal | [ ] | Aksi CRUD berhasil & aman |
| **5** | **Master Pedukuhan**| Kelola Dusun (CRUD) | Menguji pengelolaan data dusun/pedukuhan yang menjadi filter wilayah balita. | 1. Buka menu Pedukuhan.<br>2. Klik tambah pedukuhan baru.<br>3. Isi nama dusun lalu simpan. | [ ] Berhasil<br>[ ] Gagal | [ ] | Pedukuhan tersimpan |
| **6** | **Manajemen Pasien**| Registrasi Balita Manual | Memverifikasi proses input manual pasien balita baru dengan validasi NIK 16 digit. | 1. Buka Pasien -> Registrasi Balita.<br>2. Isi NIK (16 digit), nama, tgl lahir, orang tua, pedukuhan.<br>3. Klik Simpan. | [ ] Berhasil<br>[ ] Gagal | [ ] | Validasi error jika NIK &ne; 16 digit |
| **7** | **Manajemen Pasien**| Import Pasien Excel | Menguji pengunggahan massal data balita via file excel template agar pendaftaran lebih cepat. | 1. Buka Pasien -> Klik Import.<br>2. Unduh template excel.<br>3. Isi data balita -> Upload file excel.<br>4. Klik Import. | [ ] Berhasil<br>[ ] Gagal | [ ] | Berhasil parsing & insert massal |
| **8** | **Bulan Penimbangan**| Muat Balita Massal | Menguji fitur pemuatan daftar balita di posyandu terpilih pada tanggal kegiatan penimbangan. | 1. Buka Rekam Medis -> Bulan Penimbangan.<br>2. Pilih Tanggal & Posyandu.<br>3. Klik "Muat Semua Balita". | [ ] Berhasil<br>[ ] Gagal | [ ] | Baris input balita termuat rapi |
| **9** | **Bulan Penimbangan**| Input Pengukuran & Simpan | Menguji pengisian data BB/TB massal, progress bar, dan validasi penyimpanan datanya. | 1. Isi BB dan TB balita di baris tabel.<br>2. Pantau progress bar keterisian.<br>3. Klik tombol "Simpan Data". | [ ] Berhasil<br>[ ] Gagal | [ ] | Tersimpan ke DB & Status Gizi terhitung |
| **10**| **Rekam Medis** | Rekam Medis Individual | Menguji pengisian riwayat medis lengkap (BB, TB, LKA, LLA, Suhu, Keluhan, Diagnosa, Terapi). | 1. Buka Rekam Medis -> Klik Tambah.<br>2. Pilih Balita -> Isi seluruh field rekam medis lengkap.<br>3. Klik Simpan. | [ ] Berhasil<br>[ ] Gagal | [ ] | Riwayat & grafik tumbuh kembang terupdate |
| **11**| **Log Aktivitas** | Audit Trail Log | Memeriksa pencatatan aktivitas CRUD sistem secara otomatis untuk transparansi & audit. | 1. Login Superadmin -> Menu Log Aktivitas.<br>2. Cek baris log terbaru atas aksi yang baru dilakukan.<br>3. Cek mask password. | [ ] Berhasil<br>[ ] Gagal | [ ] | Log tercatat rinci & password disamarkan |
| **12**| **Laporan & Ekspor**| Ekspor PDF/Excel Bulanan| Menguji ekspor rekapitulasi data bulanan posyandu ke format PDF dan Excel untuk Puskesmas. | 1. Buka Laporan.<br>2. Filter Posyandu, Bulan, Tahun.<br>3. Klik tombol Ekspor Excel / Ekspor PDF. | [ ] Berhasil<br>[ ] Gagal | [ ] | File terunduh lengkap dengan data & ttd |
| **13**| **Edukasi Artikel** | Posting Artikel (CRUD) | Menguji admin dalam memposting, mengubah, dan menghapus artikel edukasi kesehatan. | 1. Buka Artikel -> Tambah.<br>2. Isi judul, konten, kategori, upload banner.<br>3. Simpan dan cek di preview publik. | [ ] Berhasil<br>[ ] Gagal | [ ] | Artikel terbit di halaman publik |
| **14**| **Dokumentasi** | Galeri & Media (CRUD) | Menguji pengelolaan folder galeri dan pengunggahan foto kegiatan posyandu. | 1. Buka Galeri -> Tambah Folder (Album).<br>2. Buka folder -> Klik Upload Media.<br>3. Pilih foto lalu unggah. | [ ] Berhasil<br>[ ] Gagal | [ ] | Foto terunggah di album dengan benar |
| **15**| **User Management** | Atur Role & Permission | Menguji hak akses manajemen pengguna dan pengaturan role khusus superadmin. | 1. Login Superadmin -> Menu Users / Roles.<br>2. Tambah user baru (Kader) & kaitkan ke posyandu.<br>3. Uji login user baru. | [ ] Berhasil<br>[ ] Gagal | [ ] | Role terbuat dan permission bekerja |
