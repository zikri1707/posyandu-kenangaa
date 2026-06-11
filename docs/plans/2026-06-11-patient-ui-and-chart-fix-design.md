# Patient Details UI Horizontal Layout and Growth Chart Bugfix Design

## Goal
Improve the user interface of the patient details view by changing it to a premium horizontal layout and fix the WHO Growth Chart refresh and race condition errors.

## Proposed Design

### 1. Horizontal Patient Details UI Layout
The patient profile section in `resources/views/livewire/admin/patient-management/details.blade.php` will be redesigned from a vertical 4/12 grid-column layout to a full-width horizontal banner card.

- **Profile Image & Category Badge:** Displayed on the left side of the banner with a rounded square canvas (`rounded-[2.5rem]`).
- **Demographics & Identification:** Name and NIK badge placed alongside the profile picture.
- **Quick Info Grid:** A horizontal grid below the name containing:
  - Jenis Kelamin (Gender)
  - Usia (Age)
  - Nomor Telepon (Phone)
  - Lokasi Layanan (Posyandu name)
- **Address Card:** Full-width address box at the bottom of the banner.
- **Medical & Specific Records:** Loaded via `@include` below the banner, occupying 100% of the content width.

### 2. Growth Chart Script Stability Fix
The Chart.js initialization logic inside `resources/views/livewire/admin/patient-management/growth-chart.blade.php` will be updated to:
- Polling for `window.Chart` if not immediately loaded (resolving the Vite async loading issue on page refresh).
- Retrieve and destroy any existing Chart instance on the canvas using `window.Chart.getChart(ctx)` to prevent canvas reuse errors during Livewire dynamic updates.
- Source the chart data directly from `$wire.chartData` instead of a global script variable.
