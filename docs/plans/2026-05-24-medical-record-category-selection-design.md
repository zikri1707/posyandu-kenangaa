# Design Document - Medical Record Category Selection and Forms

This document outlines the design for implementing a category selection step and category-specific forms for creating medical records. This mirrors the existing flow in the patient registration module.

## Goal
To introduce an intermediate category selection screen (Balita, Ibu Hamil, Lansia) when creating a medical record, filter the target patient dropdown dynamically by the selected category, customize the fields rendered in the form, and handle server-side validation rules dynamically based on the category.

---

## 1. Routing & Controller Workflow

- Accessing `/admin/medical-records/create` without a `category` query parameter returns the category selection view (`livewire.admin.medical-record-management.select-category`).
- Clicking a category redirects to `/admin/medical-records/create?category={category_name}`.
- In `MedicalRecordController@create`, the list of patients is filtered according to the query parameter:
  - `balita` -> Patients with category in `['bayi', 'baduta', 'balita', 'anak_sekolah']`.
  - `ibu_hamil` -> Patients with category `ibu_hamil`.
  - `lansia` -> Patients with category `lansia`.

---

## 2. Dynamic Form Validation

In `MedicalRecordRequest.php`, validation rules are adjusted dynamically:
- If the patient is a child, `measurement_method` is **required**.
- For adult categories (pregnant mother, elderly), `measurement_method` is **nullable** (or optional).
- Specific checks for elderly metrics (systolic/diastolic blood pressure, blood sugar, cholesterol, uric acid) are validated if provided.

---

## 3. UI/UX and Form Fields

In `resources/views/livewire/admin/medical-record-management/create.blade.php`:
- Alpine.js `category` state is initialized with the query parameter `category`.
- Dynamic sections are rendered conditionally:
  - **Child Form**: Shows Weight, Height, Head Circumference, Upper Arm Circumference, Measurement Method, TBC Screening, Breastfeeding, MP-ASI, Vitamin A, Deworming Medicine, Vaccine history, PMT, KPSP status, and Referral.
  - **Pregnant Mother Form**: Shows Weight, Height, Pill FE, Diagnosis, and Complaint.
  - **Elderly Form**: Shows Weight, Height, Systolic/Diastolic Blood Pressure, Blood Sugar, Cholesterol, Uric Acid, Current Medication, Diagnosis, and Complaint.
- The back button returns to the category selection page.
