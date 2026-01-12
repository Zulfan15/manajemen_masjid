<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLogService;
    protected $authService;

    public function __construct(ActivityLogService $activityLogService, AuthService $authService)
    {
        $this->activityLogService = $activityLogService;
        $this->authService = $authService;
    }

    /**
     * Show all activity logs (Super Admin only)
     */
    public function index(Request $request)
    {
        // Only super admin can view all logs
        if (!$this->authService->isSuperAdmin()) {
            abort(403, 'Hanya super admin yang dapat melihat semua log aktivitas.');
        }

        $filters = [
            'module' => $request->input('module'),
            'action' => $request->input('action'),
            'user_id' => $request->input('user_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 20),
        ];

        $logs = $this->activityLogService->getAllActivities($filters);
        $statistics = $this->activityLogService->getStatistics([
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ]);

        return view('activity-logs.index', compact('logs', 'statistics', 'filters'));
    }

    /**
     * Show module activity logs
     */
    public function moduleLog(Request $request, string $module)
    {
        // Check if user can access this module
        if (!$this->authService->canAccessModule($module)) {
            abort(403, 'Anda tidak memiliki akses ke log modul ini.');
        }

        $filters = [
            'action' => $request->input('action'),
            'user_id' => $request->input('user_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'per_page' => $request->input('per_page', 15),
        ];

        $logs = $this->activityLogService->getModuleActivities($module, $filters);

        return view('activity-logs.module', compact('logs', 'module', 'filters'));
    }

    /**
     * Show user's own activity logs
     */
    public function myLogs(Request $request)
    {
        $user = $this->authService->getCurrentUser();

        $filters = [
            'module' => $request->input('module'),
            'action' => $request->input('action'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'per_page' => $request->input('per_page', 15),
        ];

        $logs = $this->activityLogService->getUserActivities($user->id, $filters);

        return view('activity-logs.my-logs', compact('logs', 'filters'));
    }

    /**
     * Show recent activities (for dashboard widget)
     */
    public function recent()
    {
        $limit = request()->input('limit', 10);
        $logs = $this->activityLogService->getRecentActivities($limit);

        return view('activity-logs.recent', compact('logs'));
    }

    /**
     * Export activity logs to CSV (Super Admin only)
     */
    public function export(Request $request)
    {
        if (!$this->authService->isSuperAdmin()) {
            abort(403, 'Hanya super admin yang dapat mengekspor log aktivitas.');
        }

        $filters = [
            'module' => $request->input('module'),
            'action' => $request->input('action'),
            'user_id' => $request->input('user_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'per_page' => 10000, // Get all for export
        ];

        $logs = $this->activityLogService->getAllActivities($filters);

        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, ['ID', 'User', 'Action', 'Module', 'Description', 'IP Address', 'User Agent', 'Timestamp']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
