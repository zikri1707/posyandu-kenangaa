<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
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

    private function prepareExport(Request $request, ReportService $reportService)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'posyandu_id' => 'nullable|integer|exists:posyandus,id',
        ]);

        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() || $user->isCoordinator()
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
