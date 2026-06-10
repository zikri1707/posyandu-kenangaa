<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
use App\Models\Patient;
use App\Services\ActivityLogService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the monthly report page (Livewire component wrapper)
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Export report to Excel and return download response
     */
    public function exportExcel(Request $request, ReportService $reportService, ActivityLogService $activityLogService)
    {
        $data = $this->prepareExport($request, $reportService);
        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        try {
            $filePath = $reportService->exportToExcel($data['reportData'], $data['posyandu']->name);
            $this->logExportActivity($activityLogService, 'Excel', $data);

            return response()->download($filePath)->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            \Log::error('Export Excel failed: '.$e->getMessage());

            return back()->with('error', 'Ekspor gagal. Silakan coba lagi.');
        }
    }

    /**
     * Export report to PDF and return download response
     */
    public function exportPdf(Request $request, ReportService $reportService, ActivityLogService $activityLogService)
    {
        $data = $this->prepareExport($request, $reportService);
        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        try {
            $filePath = $reportService->exportToPdf($data['reportData'], $data['posyandu']->name);
            $this->logExportActivity($activityLogService, 'PDF', $data);

            return response()->download($filePath)->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            \Log::error('Export PDF failed: '.$e->getMessage());

            return back()->with('error', 'Ekspor gagal. Silakan coba lagi.');
        }
    }

    /**
     * Display the individual monthly report preview
     */
    public function showIndividual(Patient $patient, Request $request, ReportService $reportService)
    {
        $this->authorize('view', $patient);

        $endMonth = (int) ($request->end_month ?? now()->month);
        $endYear = (int) ($request->end_year ?? now()->year);
        
        $startDateDefault = now()->setDate($endYear, $endMonth, 1)->subMonths(5);
        $startMonth = (int) ($request->start_month ?? $startDateDefault->month);
        $startYear = (int) ($request->start_year ?? $startDateDefault->year);

        $reportData = $reportService->generateIndividualReportData($patient, $startMonth, $startYear, $endMonth, $endYear);

        return view('admin.reports.individual', compact('patient', 'reportData', 'startMonth', 'startYear', 'endMonth', 'endYear'));
    }

    /**
     * Export individual report to PDF
     */
    public function exportIndividualPdf(Patient $patient, Request $request, ReportService $reportService, ActivityLogService $activityLogService)
    {
        $this->authorize('view', $patient);

        $request->validate([
            'start_month' => 'required|integer|between:1,12',
            'start_year' => 'required|integer|min:2020',
            'end_month' => 'required|integer|between:1,12',
            'end_year' => 'required|integer|min:2020',
        ]);

        try {
            $reportData = $reportService->generateIndividualReportData(
                $patient, 
                (int) $request->start_month, 
                (int) $request->start_year, 
                (int) $request->end_month, 
                (int) $request->end_year
            );

            $filePath = $reportService->exportIndividualToPdf($reportData);

            $activityLogService->log(
                'export_report',
                "Ekspor rapor PDF individu: {$patient->full_name} ({$reportData['period_label']})",
                $patient->posyandu_id,
                'Patient'
            );

            return response()->download($filePath)->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            \Log::error('Export individual PDF failed: '.$e->getMessage());
            return back()->with('error', 'Ekspor PDF gagal. Silakan coba lagi.');
        }
    }

    /**
     * Export individual report to Excel
     */
    public function exportIndividualExcel(Patient $patient, Request $request, ReportService $reportService, ActivityLogService $activityLogService)
    {
        $this->authorize('view', $patient);

        $request->validate([
            'start_month' => 'required|integer|between:1,12',
            'start_year' => 'required|integer|min:2020',
            'end_month' => 'required|integer|between:1,12',
            'end_year' => 'required|integer|min:2020',
        ]);

        try {
            $reportData = $reportService->generateIndividualReportData(
                $patient, 
                (int) $request->start_month, 
                (int) $request->start_year, 
                (int) $request->end_month, 
                (int) $request->end_year
            );

            $filePath = $reportService->exportIndividualToExcel($reportData);

            $activityLogService->log(
                'export_report',
                "Ekspor rapor Excel individu: {$patient->full_name} ({$reportData['period_label']})",
                $patient->posyandu_id,
                'Patient'
            );

            return response()->download($filePath)->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            \Log::error('Export individual Excel failed: '.$e->getMessage());
            return back()->with('error', 'Ekspor Excel gagal. Silakan coba lagi.');
        }
    }

    private function prepareExport(Request $request, ReportService $reportService)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'posyandu_id' => 'nullable|integer|exists:posyandus,id',
        ]);

        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin()
            ? $request->posyandu_id
            : $user->posyandu_id;

        if (! $posyanduId) {
            return back()->with('error', 'Posyandu tidak ditemukan.');
        }

        $posyandu = Posyandu::findOrFail($posyanduId);
        $reportData = $reportService->generateMonthlyReport($posyanduId, (int) $request->month, (int) $request->year);

        return compact('posyandu', 'reportData');
    }

    private function logExportActivity(ActivityLogService $activityLogService, string $type, array $data)
    {
        $posyandu = $data['posyandu'];
        $reportData = $data['reportData'];

        $activityLogService->log(
            'export_report',
            "Ekspor laporan {$type}: {$posyandu->name} - {$reportData['period']['month_name']} {$reportData['period']['year']}",
            $posyandu->id,
            'Posyandu'
        );
    }
}
