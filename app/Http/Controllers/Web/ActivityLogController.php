<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     * Only accessible by superadmin.
     */
    public function index(Request $request)
    {
        // Authorization: Only superadmin can view all activity logs
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access. Only superadmin can view activity logs.');
        }

        $query = ActivityLog::orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Filter by entity type
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by description or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%");
            });
        }

        $activityLogs = $query->paginate(20)->withQueryString();

        // Calculate total stats efficiently using a single query
        $counts = DB::table('activity_logs')
            ->select('action_type', DB::raw('count(*) as count'))
            ->groupBy('action_type')
            ->pluck('count', 'action_type')
            ->toArray();

        $totalStats = [
            'total' => array_sum($counts),
            'create' => $counts['create'] ?? 0,
            'update' => $counts['update'] ?? 0,
            'delete' => $counts['delete'] ?? 0,
        ];

        // Get unique users for filter dropdown
        $users = DB::table('activity_logs')
            ->select('user_id', 'user_name', 'role')
            ->whereNotNull('user_id')
            ->distinct()
            ->get();

        // Get unique action types for filter dropdown
        $actionTypes = DB::table('activity_logs')
            ->select('action_type')
            ->distinct()
            ->get()
            ->pluck('action_type');

        // Get unique entity types for filter dropdown
        $entityTypes = DB::table('activity_logs')
            ->select('entity_type')
            ->whereNotNull('entity_type')
            ->distinct()
            ->get()
            ->pluck('entity_type');

        return view('admin.activity-logs.index', compact('activityLogs', 'users', 'actionTypes', 'entityTypes', 'totalStats'));
    }

    /**
     * Display the specified activity log details.
     */
    public function show(ActivityLog $activityLog)
    {
        // Authorization: Only superadmin can view activity log details
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access. Only superadmin can view activity logs.');
        }

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Get statistics for activity logs dashboard.
     */
    public function statistics()
    {
        // Authorization: Only superadmin can view statistics
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $totalLogs = ActivityLog::count();

        $logsByAction = ActivityLog::select('action_type', DB::raw('count(*) as count'))
            ->groupBy('action_type')
            ->get();

        $logsByUser = ActivityLog::select('user_id', 'user_name', 'role', DB::raw('count(*) as count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id', 'user_name', 'role')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $recentSuspiciousActivities = ActivityLog::where('action_type', 'delete')
            ->orWhere('action_type', 'login_failed')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return response()->json([
            'total_logs' => $totalLogs,
            'logs_by_action' => $logsByAction,
            'logs_by_user' => $logsByUser,
            'suspicious_activities' => $recentSuspiciousActivities,
        ]);
    }
}
