# Design Specification: Website Editorial (About Page)

## Goal
Implement a brand new, highly aesthetic, custom text-focused editorial layout for the **About Page** (`resources/views/public/about.blade.php`) to showcase the Welcome message, Vision, Mission, Objectives, and Slogan of **Posyandu ILP Kenanga RW 011**.

---

## 1. Design Overview: Modern Bento Editorial Grid

The editorial page will be built as an engaging, responsive bento-style layout featuring:
1.  **Top Header & Slogan Ribbon**: Eye-catching entry point showcasing the slogan.
2.  **Sambutan / Welcome Panel**: Asymmetrical grid cards displaying the welcome message cleanly.
3.  **Visi & Misi Bento Grid**: Side-by-side display of the main vision card and an engaging grid of 6 interactive mission cards with custom iconography.
4.  **Tujuan Timeline List**: A vertical checked-list layout highlighting the six primary goals.

---

## 2. Page Structure & Components

### 2.1. Header & Slogan Banner
- **Container**: Flex layout with responsive spacing.
- **Badge**: `<span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 rounded-full mb-6 border border-primary/20 text-xs font-black text-primary uppercase tracking-widest">Tentang Kami & Profil</span>`
- **Main Heading**: "Posyandu ILP Kenanga RW 011"
- **Slogan Banner**:
  - CSS: `bg-premium-gradient` (`linear-gradient(135deg, var(--color-p) 0%, var(--color-p-focus) 100%)`)
  - Content: *"Posyandu ILP Kenanga RW 011, Mitra Masyarakat Menuju Hidup Sehat"*
  - Style: White text, semi-italic, font-jakarta, centered, heavy tracking.

### 2.2. Sambutan / Welcome Panel
- **Main Card**: `.premium-card` / `.glass-surface` with clean padding (`p-10 md:p-16`).
- **Typography**:
  - Intro: Massive teal accent paragraph for high contrast.
  - Text: Multi-paragraph body in `.text-body-md` / `.text-text-secondary`.
- **Side Panel (Why ILP? / Integrasi Layanan Primer)**:
  - Small callouts for: *Pelayanan Terpadu*, *Pencegahan Dini*, and *Pusat Ramah & Profesional*.

### 2.3. Visi & Misi Bento Grid
- **Section Title**: "Visi & Misi Kami"
- **Visi (Vision) Card**:
  - Style: Full-width bento card, bold typography, decorative outline.
  - Content: *"Menjadi Posyandu ILP Kenanga 1 yang aktif, profesional, inovatif, dan terpercaya..."*
- **Misi (Mission) Grid**:
  - Style: Responsive `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6`.
  - Individual Cards: 6 `.bento-card` blocks with `.hover-lift`.
  - Icon Mapping:
    1.  *Misi 1*: `medical_services` (Pelayanan kesehatan dasar mudah diakses).
    2.  *Misi 2*: `family_history` (Pemantauan ibu hamil, bayi, balita, remaja, dewasa, lansia).
    3.  *Misi 3*: `nutrition` (Pencegahan stunting, gizi buruk).
    4.  *Misi 4*: `wash` (Perilaku hidup bersih & sehat / PHBS).
    5.  *Misi 5*: `groups` (Kapasitas kader Posyandu).
    6.  *Misi 6*: `handshake` (Kerja sama Puskesmas, kelurahan).

### 2.4. Tujuan (Objectives) Section
- **Section Title**: "Tujuan Strategis"
- **Layout**: Clean grid-list using beautiful indicator checkmarks.
- **Items**:
  1.  Meningkatkan derajat kesehatan.
  2.  Menurunkan angka stunting & gizi kurang.
  3.  Meningkatkan cakupan imunisasi & pemeriksaan.
  4.  Meningkatkan kesadaran pola hidup sehat.
  5.  Mewujudkan pelayanan Posyandu terintegrasi.
  6.  Menjadikan Posyandu sebagai pusat layanan nyaman & terpercaya.

---

## 3. Style Tokens Used
- **Fonts**: `Plus Jakarta Sans` (`font-jakarta`) & `Public Sans`.
- **Colors**:
  - `--color-primary` (`#006c49` - Deep Teal)
  - `--color-surface` (`#f5faf8` - Minty White Surface)
  - `--color-on-surface` (`#171d1c` - Very Dark Teal Slate)
- **Effects**: `.premium-card`, `.bento-card`, `.hover-lift`, `.glass-surface`.
