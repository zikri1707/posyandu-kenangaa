# System Cleanup & Rebranding Design

**Date:** 2026-05-03
**Status:** Approved

## Goal
Clean up unused files, folders, and code in the Posyandu Admin Dashboard system and rebrand the repository references to the current owner (`Alarave`).

## 1. GitHub Rebranding & CI/CD Optimization
- **README.md**: Replace all occurrences of `ahmadrizal1st/posyandu-admin-dashboard` with `Alarave/posyandu-admin`.
- **composer.json**: Update package name to `alarave/posyandu-admin`.
- **Workflows**: Remove steps in `.github/workflows/lint.yml` and `tests.yml` that configure Flux credentials (`FLUX_USERNAME`, `FLUX_LICENSE_KEY`) to allow CI to pass without these secrets.

## 2. File & Folder Cleanup
- **Tests**:
    - Remove `tests/Feature/ExampleTest.php`
    - Remove `tests/Unit/ExampleTest.php`
    - Remove `tests/Feature/DashboardTest.php` (redundant)
- **Livewire**:
    - Remove the empty directory `app/Livewire/Misc`.

## 3. Code Optimization
- Scan `tests/Feature/GrowthChartTest.php` and `tests/Feature/MedicalRecordNutritionCalculationTest.php` for any dead code or unused helper methods.

## Success Criteria
- No references to the previous GitHub owner in README or composer.
- GitHub Actions pass (or are ready to pass) without Flux license secrets.
- Redundant and empty test files/folders are removed.
