<?php

namespace App\Livewire\Admin\PatientManagement;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Patient;
use App\Services\GrowthChartService;

/**
 * Komponen Livewire untuk menampilkan grafik pertumbuhan anak (Buku KIA Digital).
 */
class GrowthChart extends BaseAdminComponent
{
    public Patient $patient;

    public string $activeChart = 'wfa'; // wfa (BB/U), hfa (TB/U)

    public array $chartData = [];

    public bool $isEmbedded = false;

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->chartData = $this->getChartData(app(GrowthChartService::class));
    }

    public function switchChart(string $type): void
    {
        \Illuminate\Support\Facades\Log::info('Switching chart to: '.$type);
        $this->activeChart = $type;
        $this->chartData = $this->getChartData(app(GrowthChartService::class));
        $this->dispatch('chart-updated', $this->chartData);
    }

    /**
     * Menyiapkan data grafik untuk dikirim ke view (Chart.js).
     */
    public function getChartData(GrowthChartService $service): array
    {
        return match ($this->activeChart) {
            'wfa' => $service->getWeightForAgeData($this->patient),
            'hfa' => $service->getHeightForAgeData($this->patient),
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.admin.patient-management.growth-chart')
            ->layout('layouts.admin-layout');
    }
}
