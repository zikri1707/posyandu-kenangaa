# Desain Peningkatan UI Sambutan & Profil Posyandu ILP Kenanga

Dokumen ini mendokumentasikan rencana peningkatan UI untuk bagian teks sambutan/profil pada halaman "Tentang Kami" (`resources/views/public/about.blade.php`).

## 1. Latar Belakang & Tujuan
Paragraf sambutan dan profil pada halaman "Tentang Kami" saat ini disajikan dalam bentuk teks panjang di dalam satu blok kolom (`lg:col-span-8`), sehingga kurang menarik secara visual dan sulit dipindai (*scannable*) oleh warga. Tujuan perubahan ini adalah menata ulang teks tersebut menjadi format **Alur Layanan & Tiga Pilar Informasi (Pillars Flow)** yang modern, interaktif, dan responsif.

## 2. Struktur Desain Baru

### A. Pengantar Utama (Intro Callout)
Paragraf pertama akan ditampilkan penuh sebagai pembuka yang menonjol:
* **Teks**: *"Posyandu ILP Kenanga RW 011 hadir sebagai wujud komitmen masyarakat dalam meningkatkan kualitas kesehatan warga melalui pelayanan kesehatan primer yang terpadu, mudah diakses, dan berkelanjutan."*
* **Visual**: Tipografi besar (`text-lg md:text-xl font-bold`), warna teks kontras (`text-slate-900 dark:text-white`), border kiri yang tebal dengan gradasi warna primer ke teal (`border-l-4 border-gradient`), serta padding longgar untuk memberikan kesan sambutan hangat.

### B. Tiga Pilar Alur Layanan (3-Column Pillars Grid)
Sisa paragraf dipecah menjadi tiga pilar/kartu informasi utama yang disusun sejajar pada layar besar (`grid grid-cols-1 lg:grid-cols-3 gap-8`):

#### 1. Pilar 01: Sasaran Layanan & Lokasi
* **Nomor Transparan**: `01` besar di pojok kanan atas kartu dengan opasitas rendah.
* **Ikon**: `location_on` (Material Icon) berwarna gradasi emas/teal.
* **Konten**: *"Berlokasi di Kelurahan Aren Jaya, kami siap melayani seluruh lapisan masyarakat mulai dari ibu hamil, bayi, balita, remaja, dewasa, hingga lansia."*
* **Aksen Visual**: Daftar horizontal berisi badge/chips kecil yang indah:
  * 🤰 Ibu Hamil
  * 👶 Bayi & Balita
  * 🧒 Remaja
  * 🧑 Dewasa
  * 🧓 Lansia

#### 2. Pilar 02: Pendekatan ILP & Kader
* **Nomor Transparan**: `02` besar di pojok kanan atas.
* **Ikon**: `volunteer_activism` (Material Icon) berwarna primer.
* **Konten**: Menggabungkan penjelasan mengenai integrasi layanan primer (fokus pencegahan, promosi kesehatan, dll) dan dedikasi kader yang aktif serta peduli.
* **Aksen Visual**: Penekanan tebal (bold) pada frasa penting seperti **Pencegahan Penyakit**, **Promosi Kesehatan**, dan **Pemantauan Tumbuh Kembang**.

#### 3. Pilar 03: Pusat Informasi Digital
* **Nomor Transparan**: `03` besar di pojok kanan atas.
* **Ikon**: `devices` atau `campaign` (Material Icon) berwarna teal/biru.
* **Konten**: *"Website ini kami hadirkan sebagai media informasi dan komunikasi bagi warga RW 011 Aren Jaya..."*
* **Aksen Visual**: Pintasan visual/chips interaktif yang merepresentasikan fitur web:
  * 📅 Jadwal Kegiatan
  * 📋 Program Pelayanan
  * 📖 Edukasi Kesehatan
  * 📸 Dokumentasi

## 3. Tipografi & Gaya
* **Font**: Menyesuaikan dengan font keluarga `font-jakarta` (Plus Jakarta Sans) yang telah terpasang.
* **Interaktivitas**: Kartu memiliki efek hover halus: naik ke atas (`hover:-translate-y-1.5`) dan bayangan yang membesar (`hover:shadow-xl`), memberikan kesan dinamis dan hidup.
* **Kompatibilitas Tema**: Mendukung penuh transisi Light Mode & Dark Mode.
