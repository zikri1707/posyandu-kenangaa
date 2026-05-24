# WHO Growth Chart UI Redesign Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Redesign the child growth chart visual style to use a clean minimalist light mode layout with soft shaded WHO standard deviation reference bands (green for normal, amber for warning, rose for danger).

**Architecture:** We will update the frontend view file `growth-chart.blade.php`. In the client-side Javascript code, we will intercept the incoming `chartData`, re-order the datasets, configure specific Chart.js `fill` target options to draw the colored background zones, customize gridlines/ticks/axes for light mode, and style the child's data points to stand out with high contrast.

**Tech Stack:** Laravel Blade, Livewire, Alpine.js, Chart.js, Tailwind CSS

---

### Task 1: Update growth-chart.blade.php UI Elements & Card Layout
**Files:**
- Modify: `resources/views/livewire/admin/patient-management/growth-chart.blade.php`

**Step 1: Write minimal implementation**
We will change the dark gradient chart card to a premium light slate/white card layout with a reduced height of 550px.
In `resources/views/livewire/admin/patient-management/growth-chart.blade.php` from line 480 to 554:
Replace the style, classes, and background configurations:
- Remove: `style="height: 900px; background: ..."`
- Add: `class="relative rounded-[2.5rem] p-8 border border-slate-100 bg-white shadow-sm overflow-hidden h-[550px] transition-all duration-700"`
- Remove the dark-themed absolute radial-gradient backdrop div.
- Update the empty state elements to match the light theme styling (dark slate text for titles/description, slate-100 backdrop for icon).

**Step 2: Commit**
```bash
git add resources/views/livewire/admin/patient-management/growth-chart.blade.php
git commit -m "style: change growth chart card to minimalist light layout and adjust height"
```

---

### Task 2: Configure Chart.js Light Theme Scales & Gridlines
**Files:**
- Modify: `resources/views/livewire/admin/patient-management/growth-chart.blade.php`

**Step 1: Write minimal implementation**
Modify the Chart.js configuration options inside `initChart(chartData)` in `growth-chart.blade.php`:
- Scales X & Y grid colors: change from `rgba(255,255,255,0.1)` to `rgba(15, 23, 42, 0.05)`.
- Scales X & Y ticks colors: change from `#ffffff` to `#475569` (slate-600).
- Scales X & Y title font/color: change from `#ffffff` to `#1e293b` (slate-800).
- Legend text color: change from `#ffffff` to `#475569`.
- Tooltip styles: update layout to slate background (`rgba(15, 23, 42, 0.95)`), rounded-xl (`12px` or `16px`), title/body colors to white, add custom formats.

**Step 2: Commit**
```bash
git add resources/views/livewire/admin/patient-management/growth-chart.blade.php
git commit -m "style: update Chart.js gridlines, axes, and typography to light mode styling"
```

---

### Task 3: Map and Shading WHO Reference Zones (Chart.js Fills)
**Files:**
- Modify: `resources/views/livewire/admin/patient-management/growth-chart.blade.php`

**Step 1: Write minimal implementation**
Inside `initChart(chartData)`, extract, re-order, and style the standard deviation curves to implement the filled zones:
- Find datasets by label (`Median`, `+2 SD`, `-2 SD`, `+3 SD`, `-3 SD`, and Child).
- Arrange them in a strict order:
  1. `+3 SD` (index 0) -> `borderColor: '#f43f5e'`, `fill: 1` (fills to `+2 SD`), `backgroundColor: 'rgba(245, 158, 11, 0.08)'` (Warning High).
  2. `+2 SD` (index 1) -> `borderColor: '#f59e0b'`, `fill: 3` (fills to `-2 SD`), `backgroundColor: 'rgba(16, 185, 129, 0.12)'` (Normal).
  3. `Median` (index 2) -> `borderColor: '#10b981'`, `borderWidth: 2`, `fill: false`.
  4. `-2 SD` (index 3) -> `borderColor: '#f59e0b'`, `fill: 4` (fills to `-3 SD`), `backgroundColor: 'rgba(245, 158, 11, 0.08)'` (Warning Low).
  5. `-3 SD` (index 4) -> `borderColor: '#ef4444'`, `fill: 'origin'`, `backgroundColor: 'rgba(239, 68, 68, 0.08)'` (Danger Low).
  6. Child dataset (index 5) -> `borderColor: isMale ? '#0d9488' : '#be185d'`, `borderWidth: 4`, `pointRadius: 6`, `pointHoverRadius: 9`, `pointBackgroundColor: isMale ? '#0d9488' : '#be185d'`, `pointBorderColor: '#ffffff'`, `pointBorderWidth: 2`, `fill: false`.
- Set standard properties for all reference lines: `pointRadius: 0`, `pointHoverRadius: 0`, `borderDash: []`, `tension: 0.4`.

**Step 2: Commit**
```bash
git add resources/views/livewire/admin/patient-management/growth-chart.blade.php
git commit -m "feat: implement native Chart.js dataset ordering and zone filling for growth standards"
```

---

### Task 4: Verify Implementation and Run Tests
**Files:**
- Test: `tests/Feature/GrowthChartTest.php`

**Step 1: Run automated tests to verify stability**
Run: `php artisan test tests/Feature/GrowthChartTest.php`
Expected: All 5 tests in `GrowthChartTest.php` pass successfully.

**Step 2: Commit**
```bash
git commit --allow-empty -m "test: verify all growth chart feature tests are passing"
```
