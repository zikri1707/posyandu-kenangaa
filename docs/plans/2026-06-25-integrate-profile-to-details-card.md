# Integrate Profile Into Details Card Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Integrate the patient profile photo, name, and NIK directly into the "Identitas Pribadi" detail card at the top, remove the duplicate rows from the table, and ensure all patient category views (balita, lansia, ibu_hamil, umum) handle this layout cleanly.

**Architecture:** 
1. In `details/balita.blade.php`, `details/lansia.blade.php`, and `details/ibu_hamil.blade.php`, add a new header container at the top of the "Identitas Pribadi" card with a centered layout for the profile photo, patient's full name, NIK, and category badge.
2. Remove the redundant "Nama Lengkap" and "NIK" table rows from the "Identitas Pribadi" card tables.
3. In `details/umum.blade.php`, restructure the layout to include a dedicated "Identitas Pribadi" card featuring the profile photo, name, NIK, category badge, and basic fields (such as Gender, Birth Date, Tempat Lahir, Usia, Phone Number, Alamat) so general category patients do not lose their primary profile information.
4. Run feature tests to verify that there are no regressions.

**Tech Stack:** Laravel, Blade, Tailwind CSS, Alpine.js / Livewire.

---

## User Review Required

> [!IMPORTANT]
> The top horizontal banner has already been removed in [details.blade.php](file:///c:/Users/HP/kenanga-posyandu/resources/views/livewire/admin/patient-management/details.blade.php). This plan will modify the patient category partials (`balita`, `lansia`, `ibu_hamil`, `umum`) to display the photo, name, NIK, and category badge at the top of the first details card, and clean up the duplicate rows from their tables.

## Proposed Changes

### Patient Detail Category Partials

#### [MODIFY] [balita.blade.php](file:///c:/Users/HP/kenanga-posyandu/resources/views/livewire/admin/patient-management/details/balita.blade.php)
- Add the centered profile image, name, NIK, and category badge to the top of the "Identitas Pribadi" card.
- Remove duplicate table rows for `Nama Lengkap` and `NIK / No. Identitas`.

#### [MODIFY] [lansia.blade.php](file:///c:/Users/HP/kenanga-posyandu/resources/views/livewire/admin/patient-management/details/lansia.blade.php)
- Add the centered profile image, name, NIK, and category badge to the top of the "Identitas Pribadi" card.
- Remove duplicate table rows for `Nama Lengkap` and `NIK / No. Identitas`.

#### [MODIFY] [ibu_hamil.blade.php](file:///c:/Users/HP/kenanga-posyandu/resources/views/livewire/admin/patient-management/details/ibu_hamil.blade.php)
- Add the centered profile image, name, NIK, and category badge to the top of the "Identitas Pribadi" card.
- Remove duplicate table rows for `Nama Lengkap` and `NIK / No. Identitas`.

#### [MODIFY] [umum.blade.php](file:///c:/Users/HP/kenanga-posyandu/resources/views/livewire/admin/patient-management/details/umum.blade.php)
- Restructure the page layout to introduce a new "Identitas Pribadi" card that houses the profile photo, name, NIK, category badge, and basic fields (Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Usia, No. HP, Alamat).
- Move the existing "Pendidikan & Pekerjaan" and "Sosial Ekonomi" cards alongside it (or adjust columns accordingly).

---

## Verification Plan

### Automated Tests
- Run Pest test suite for Patient Management:
  `vendor\bin\pest tests\Feature\Admin\PatientManagementTest.php`
- Run Pest test suite for Growth Chart:
  `vendor\bin\pest tests\Feature\GrowthChartTest.php`

### Manual Verification
- View the patient details page in the browser for Balita, Lansia, Ibu Hamil, and General (Umum) patients to verify the profile photo, name, NIK, and badge render correctly inside the card without visual clutter.
