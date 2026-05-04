# Pokja IV Report Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Implement the formal Pokja IV Posyandu report layout in PDF.

**Architecture:** Update `ReportService` to gather granular age-based data and implement a complex table-based layout in `monthly-report-pdf.blade.php`.

**Tech Stack:** Laravel, DomPDF, Blade, Carbon

---

### Task 1: Update ReportService for Pokja IV Data

**Files:**
- Modify: `app/Services/ReportService.php`

**Step 1: Implement granular age breakdown**

Update `generateMonthlyReport` to calculate counts for age groups: 0-5, 6-11, 12-23, 24-59 months.

**Step 2: Add Kader and Personnel counts**

Add logic to count active Kader and other personnel if available in the DB.

**Step 3: Run tests**
Run: `php artisan test --filter ReportTest`
Expected: PASS

**Step 4: Commit**
`git commit -m "feat: add Pokja IV data gathering to ReportService"`

---

### Task 2: Redesign PDF View to Pokja IV Layout

**Files:**
- Modify: `resources/views/reports/monthly-report-pdf.blade.php`

**Step 1: Implement the complex header and grid structure**

Create the 4-section grid exactly as seen in the Pokja IV image using HTML tables.

**Step 2: Map data to the grid**

Fill the grid with data from `$reportData`, using placeholders for untracked metrics.

**Step 3: Add CSS for formal print look**

Use a formal serif font and solid black borders for the grid.

---

### Task 3: Verification

**Step 1: Generate sample PDF**
Run: `php artisan tinker --execute="app(App\Services\ReportService::class)->exportToPdf(app(App\Services\ReportService::class)->generateMonthlyReport(1, 5, 2026), 'Posyandu A')"`

**Step 2: Inspect output**
Verify the PDF in `storage/app/public/exports/`.
