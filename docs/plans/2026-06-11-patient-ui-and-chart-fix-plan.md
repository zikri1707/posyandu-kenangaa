# Patient UI and Chart Fix Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Redesign patient details layout to horizontal and fix the WHO growth chart reload/refresh errors.

**Architecture:** Use a full-width container layout for demographics and medical tables, and clean up Chart.js lifecycle management with Alpine initialization.

**Tech Stack:** Laravel, Blade, Livewire, Alpine.js, Chart.js

---

### Task 1: Redesign Patient Profile Layout to Horizontal

**Files:**
- Modify: `resources/views/livewire/admin/patient-management/details.blade.php`

**Step 1: Write the visual changes**

Change the vertical two-column layout (`grid-cols-1 lg:grid-cols-12`) in `details.blade.php` to a stacked horizontal layout.

Replace the bento grid section from lines 103-210 with:
```html
    {{-- ── Horizontal Profile Banner (Premium) ── --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] relative overflow-hidden group">
        {{-- Decorative Background --}}
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
            {{-- Profile Photo --}}
            <div class="relative shrink-0">
                <div class="w-36 h-36 rounded-[2.5rem] border-4 border-white bg-slate-50 shadow-xl overflow-hidden relative z-10">
                    @if($patient->profile_photo)
                        <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-linear-to-br from-slate-50 to-slate-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-300 text-[80px]" style="font-variation-settings: 'wght' 100;">account_circle</span>
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-slate-900 text-white text-[9px] font-black rounded-xl uppercase tracking-[0.2em] shadow-xl z-20 whitespace-nowrap">
                    {{ str_replace('_', ' ', $theme['name']) }}
                </div>
            </div>
            
            {{-- Info Details (Horizontal Layout) --}}
            <div class="flex-1 w-full space-y-6">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black text-slate-900 leading-tight mb-2 tracking-tight">{{ $patient->full_name }}</h2>
                    <span @class([
                        'inline-block text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-widest border',
                        'text-sky-600 bg-sky-50 border-sky-100' => $patient->gender == 'L' || $patient->gender == 'M',
                        'text-pink-600 bg-pink-50 border-pink-100' => $patient->gender == 'F' || $patient->gender == 'P',
                    ])>NIK: {{ $patient->id_number }}</span>
                </div>
                
                {{-- Quick Fields Row --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-6 border-t border-slate-100">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                        <div class="flex items-center gap-2">
                            <span @class([
                                'w-2 h-2 rounded-full',
                                'bg-sky-500' => $patient->gender == 'L' || $patient->gender == 'M',
                                'bg-pink-500' => $patient->gender == 'F' || $patient->gender == 'P',
                            ])></span>
                            <span class="text-sm font-black text-slate-800">{{ ($patient->gender == 'L' || $patient->gender == 'M') ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Usia</p>
                        <p class="text-sm font-black text-slate-800">{{ $patient->age }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor Telepon</p>
                        <p class="text-sm font-black text-slate-800">{{ $patient->phone_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Layanan</p>
                        <p class="text-sm font-black text-slate-800">{{ str_contains($patient->posyandu->name, 'Posyandu') ? $patient->posyandu->name : 'Posyandu ' . $patient->posyandu->name }}</p>
                    </div>
                </div>
                
                {{-- Address Box --}}
                <div class="bg-slate-50 rounded-2xl p-4 flex items-start gap-3 border border-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 shrink-0">location_on</span>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Alamat Domisili</p>
                        <p class="text-xs font-bold text-slate-600 leading-relaxed">{{ $patient->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- ── Section 2: Data Medis & Sosial (Full Width) ── --}}
    <div class="w-full">
        @include('livewire.admin.patient-management.details.' . $theme['partial'])
    </div>
```

**Step 2: Commit changes**

```bash
git add resources/views/livewire/admin/patient-management/details.blade.php
git commit -m "style(admin-patient): convert details view to horizontal layout"
```

---

### Task 2: Fix WHO Growth Chart Refresh & Reuse Errors

**Files:**
- Modify: `resources/views/livewire/admin/patient-management/growth-chart.blade.php`

**Step 1: Rewrite Chart Initialization and Destructuring in `growth-chart.blade.php`**

Replace lines 118-289 in `growth-chart.blade.php` with:
```html
    <div class="bg-white rounded-[3rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden relative group"
         x-data="{ 
            chart: null,
            hasData: true,
            initChart(chartData) {
                const ctx = document.getElementById('growthChartBottom');
                if (!ctx) return;
                
                if (!window.Chart) {
                    setTimeout(() => this.initChart(chartData), 50);
                    return;
                }
                
                // Destroy existing chart on canvas using Chart.getChart to prevent canvas reuse error
                const existingChart = window.Chart.getChart(ctx);
                if (existingChart) {
                    existingChart.destroy();
                }
                
                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }
                
                const data = chartData || null;
                if (!data || !data.datasets || data.datasets.length === 0) {
                    this.hasData = false;
                    return;
                }

                this.hasData = true;

                // Intercept and style datasets for soft-colored reference bands
                const rawDatasets = data.datasets;
                const medianDataset = rawDatasets.find(d => d.label === 'Median');
                const plus2Dataset = rawDatasets.find(d => d.label === '+2 SD');
                const minus2Dataset = rawDatasets.find(d => d.label === '-2 SD');
                const plus3Dataset = rawDatasets.find(d => d.label === '+3 SD');
                const minus3Dataset = rawDatasets.find(d => d.label === '-3 SD');
                const childDataset = rawDatasets.find(d => d.label.includes('Anak') || d.label.includes('Badan'));

                const styledDatasets = [];

                // 1. +3 SD (index 0) - Fills to +2 SD (index 1) with amber warning
                if (plus3Dataset) {
                    plus3Dataset.borderColor = '#f43f5e';
                    plus3Dataset.borderWidth = 1.5;
                    plus3Dataset.pointRadius = 0;
                    plus3Dataset.pointHoverRadius = 0;
                    plus3Dataset.fill = 1;
                    plus3Dataset.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                    styledDatasets.push(plus3Dataset);
                }

                // 2. +2 SD (index 1) - Fills to -2 SD (index 3) with healthy emerald green
                if (plus2Dataset) {
                    plus2Dataset.borderColor = '#f59e0b';
                    plus2Dataset.borderWidth = 1.5;
                    plus2Dataset.pointRadius = 0;
                    plus2Dataset.pointHoverRadius = 0;
                    plus2Dataset.fill = 3;
                    plus2Dataset.backgroundColor = 'rgba(16, 185, 129, 0.12)';
                    styledDatasets.push(plus2Dataset);
                }

                // 3. Median (index 2) - No fill, drawn clearly in the center
                if (medianDataset) {
                    medianDataset.borderColor = '#10b981';
                    medianDataset.borderWidth = 2.5;
                    medianDataset.pointRadius = 0;
                    medianDataset.pointHoverRadius = 0;
                    medianDataset.fill = false;
                    styledDatasets.push(medianDataset);
                }

                // 4. -2 SD (index 3) - Fills to -3 SD (index 4) with amber warning
                if (minus2Dataset) {
                    minus2Dataset.borderColor = '#f59e0b';
                    minus2Dataset.borderWidth = 1.5;
                    minus2Dataset.pointRadius = 0;
                    minus2Dataset.pointHoverRadius = 0;
                    minus2Dataset.fill = 4;
                    minus2Dataset.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                    styledDatasets.push(minus2Dataset);
                }

                // 5. -3 SD (index 4) - Fills to origin (bottom) with rose danger
                if (minus3Dataset) {
                    minus3Dataset.borderColor = '#ef4444';
                    minus3Dataset.borderWidth = 1.5;
                    minus3Dataset.pointRadius = 0;
                    minus3Dataset.pointHoverRadius = 0;
                    minus3Dataset.fill = 'origin';
                    minus3Dataset.backgroundColor = 'rgba(239, 68, 68, 0.08)';
                    styledDatasets.push(minus3Dataset);
                }

                // 6. Child dataset (index 5) - High contrast, styled by gender theme
                if (childDataset) {
                    const isMaleGender = {{ $isMale ? 'true' : 'false' }};
                    const lineCol = isMaleGender ? '#0d9488' : '#be185d';
                    childDataset.borderColor = lineCol;
                    childDataset.borderWidth = 4;
                    childDataset.pointRadius = 6;
                    childDataset.pointHoverRadius = 9;
                    childDataset.pointBackgroundColor = lineCol;
                    childDataset.pointBorderColor = '#ffffff';
                    childDataset.pointBorderWidth = 2;
                    childDataset.fill = false;
                    childDataset.tension = 0.25;
                    styledDatasets.push(childDataset);
                }

                data.datasets = styledDatasets;

                this.chart = new window.Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        interaction: { intersect: false, mode: 'index' },
                        scales: {
                            x: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#475569', font: { size: 10, weight: '700' } },
                                title: { display: true, text: 'UMUR (BULAN)', color: '#1e293b', font: { weight: '800', size: 11, family: 'Inter' } }
                            },
                            y: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#475569', font: { size: 10, weight: '700' } },
                                suggestedMin: data.datasets.some(d => d.label.includes('Tinggi')) ? 40 : 0,
                                title: { display: true, text: data.datasets.some(d => d.label.includes('Tinggi')) ? 'TINGGI (CM)' : 'BERAT (KG)', color: '#1e293b', font: { weight: '800', size: 11, family: 'Inter' } }
                            }
                        },
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    boxWidth: 8, 
                                    boxHeight: 8, 
                                    usePointStyle: true, 
                                    pointStyle: 'circle', 
                                    color: '#475569', 
                                    padding: 25, 
                                    font: { weight: '700', size: 10 } 
                                } 
                            },
                            tooltip: { 
                                backgroundColor: 'rgba(15, 23, 42, 0.95)', 
                                padding: 16, 
                                cornerRadius: 16, 
                                usePointStyle: true, 
                                titleFont: { size: 13, weight: '800' }, 
                                bodyFont: { size: 12, weight: '600' },
                                filter: function(tooltipItem) {
                                    const label = tooltipItem.dataset.label || '';
                                    return label.includes('Anak') || label.includes('Badan') || label === 'Median';
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            const isHeight = context.chart.options.scales.y.title.text.includes('TINGGI') || context.dataset.label.includes('Tinggi');
                                            label += context.parsed.y + (isHeight ? ' cm' : ' kg');
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
         }"
         x-init="
            const init = () => {
                if (window.Chart) {
                    initChart($wire.chartData);
                } else {
                    setTimeout(init, 50);
                }
            };
            setTimeout(init, 100);
            
            $wire.on('chart-updated', (data) => {
                const rawData = Array.isArray(data) ? data[0] : data;
                initChart(rawData);
            });
         "
```

Also, remove the script block (line 335) that defines `var initialGrowthData`:
```html
        <script>var initialGrowthData = @json($chartData);</script>
```

**Step 2: Commit changes**

```bash
git add resources/views/livewire/admin/patient-management/growth-chart.blade.php
git commit -m "fix(growth-chart): resolve Chart.js reuse errors and race conditions on page refresh"
```
