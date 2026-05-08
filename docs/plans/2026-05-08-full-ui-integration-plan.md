# Full Integration: Formulir Pemeriksaan Balita Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Integrate the premium UI design for 'Formulir Pemeriksaan Balita' into the `admin/medical-records/create` page, including full database support for all new fields (TBC, KPSP, PMT, etc.).

**Architecture:** 
- **Database**: Add missing fields to `patients` and `medical_records` tables.
- **Models**: Update Eloquent models with new fillable attributes.
- **Frontend**: Update `tailwind.config.js` with the new color palette and design system. Refactor the Blade view to match the new high-fidelity layout.

**Tech Stack:** Laravel, Livewire, Tailwind CSS, MySQL.

---

### Task 1: Database Migrations

**Files:**
- Create: `database/migrations/2026_05_08_000000_update_patients_and_medical_records_for_new_ui.php`

**Step 1: Add missing fields to `patients`**
- `father_name` (string, nullable)
- `mother_name` (string, nullable)
- `weight_at_birth` (decimal, 5,2, nullable)
- `height_at_birth` (decimal, 5,2, nullable)

**Step 2: Add missing fields to `medical_records`**
- `weight_status` (string: N, T, 2T, nullable)
- `kpsp_status` (string: Lengkap/Tidak Lengkap, nullable)
- `tbc_screening_cough` (boolean, default: false)
- `tbc_screening_fever` (boolean, default: false)
- `tbc_screening_contact` (boolean, default: false)
- `other_symptoms` (text, nullable)
- `pmt_given` (string, nullable)
- `counseling_notes` (text, nullable)
- `referral_type` (string: None, Pustu, Puskesmas, RS, default: None)

**Step 3: Run migration**
Run: `php artisan migrate`

---

### Task 2: Model Updates

**Files:**
- Modify: `app/Models/Patient.php`
- Modify: `app/Models/MedicalRecord.php`

**Step 1: Update `Patient` fillable**
Add: `father_name`, `mother_name`, `weight_at_birth`, `height_at_birth`.

**Step 2: Update `MedicalRecord` fillable**
Add: `weight_status`, `kpsp_status`, `tbc_screening_cough`, `tbc_screening_fever`, `tbc_screening_contact`, `other_symptoms`, `pmt_given`, `counseling_notes`, `referral_type`.

---

### Task 3: Tailwind Config Integration

**Files:**
- Modify: `tailwind.config.js`

**Step 1: Add the new color palette and border radius to the theme extension**
Include colors from the design: `primary: '#006c49'`, `secondary: '#4648d4'`, `tertiary: '#a43a3a'`, and surface variants.

---

### Task 4: UI Implementation (`create.blade.php`)

**Files:**
- Modify: `resources/views/livewire/admin/medical-record-management/create.blade.php`

**Step 1: Replace the entire view content with the new layout structure**
Adapt the provided HTML into Blade, using existing variables like `$patients`.

---

### Task 5: Logic Update

**Files:**
- Modify: `app/Http/Controllers/Web/MedicalRecordController.php` (or relevant Livewire component)

**Step 1: Update the `store` method to handle the new fields**
Update validation rules and storage logic.

---
