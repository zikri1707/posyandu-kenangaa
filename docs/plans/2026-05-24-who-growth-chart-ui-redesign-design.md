# WHO Growth Chart UI Redesign Design Doc

**Date:** 2026-05-24
**Topic:** WHO Growth Chart UI Redesign
**Status:** Approved

## Overview
Improve the visual presentation of "Grafik Analisis Pertumbuhan WHO" (WHO Growth Analysis Chart) and "Visualisasi Tren Antropometri Anak" (Child Anthropometry Trend Visualization) inside the patient profile detail view. The goal is to transition from a harsh dark-gradient chart to a clean, minimalist light-themed dashboard view with soft, colored WHO standard deviation reference bands.

## Approved Design Details

### 1. Visual Container & Grid Setup
* **Card Style:** Light, elegant card interface with a clean white/soft slate background (`bg-slate-50/30`), a very thin border, and rounded corners (`rounded-[2.5rem]`).
* **Height Adjustment:** Scale down the canvas height from the current `900px` to a more ergonomic `550px` or `600px` to maintain high readability without requiring excessive page scrolling.
* **Gridlines:** Soft gray grid lines (`rgba(15, 23, 42, 0.05)`) that do not interfere with the data path.
* **Axes Typography:** High-contrast Slate (`#475569`) font for labels, and bold uppercase text for axis titles (`UMUR (BULAN)` / `BERAT (KG)` / `TINGGI (CM)`).

### 2. WHO Reference Datasets and Color Zones (Dataset Filling)
We configure native dataset fills inside Chart.js by ordering the curves from highest (Dataset 0) to lowest (Dataset 4):
* **`+3 SD`:** Line style: Rose/Red (`#f43f5e`). No fill.
* **`+2 SD`:** Line style: Amber (`#f59e0b`).
  * *Fill:* Filled up to `+3 SD` (index 0) with a soft warning color (`rgba(245, 158, 11, 0.08)`), representing the **Overweight/Warning** zone.
* **`Median`:** Line style: Slightly thicker green/emerald (`#10b981`, width: 2).
* **`-2 SD`:** Line style: Amber (`#f59e0b`).
  * *Fill:* Filled up to `+2 SD` (index 1) with a soft green shade (`rgba(16, 185, 129, 0.12)`), representing the **Normal/Healthy Growth** zone.
* **`-3 SD`:** Line style: Rose/Red (`#ef4444`).
  * *Fill:* Filled up to `-2 SD` (index 3) with a soft warning color (`rgba(245, 158, 11, 0.08)`), representing the **Underweight/Short Warning** zone.
  * *Secondary Fill:* Filled down to the baseline (`fill: 'origin'`) with a soft rose shade (`rgba(239, 68, 68, 0.08)`), representing the **Severely Underweight/Stunted** zone.
* **Child's Measurement Line:** Bold, high-contrast line (`width: 4`) colored according to the patient's gender theme (Teal `#0d9488` for boys, Rose `#be185d` for girls). The data points are styled with a white border and custom hover animations (`pointRadius: 6`, `pointHoverRadius: 9`).

### 3. Interaction and Tooltips
* **Tooltip Style:** Custom glassmorphic tooltip using a dark slate background (`rgba(15, 23, 42, 0.95)`), generous padding (`20px`), rounded corners (`20px`), and custom font styles.
* **Data presentation:** Clean display of child's age in months and corresponding measurement. Uncluttered layout by hiding reference standard values from the tooltip context or formatting them cleanly.
* **Transitions:** Smooth easing transitions (`easeOutQuart`, `1500ms`) when switching between Weight-for-Age (BB/U) and Height-for-Age (TB/U) tabs.
