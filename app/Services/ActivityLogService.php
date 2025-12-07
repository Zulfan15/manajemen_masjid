<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity
     */
    public function log(
        string $action,
        string $module,
        string $description,
        array $properties = []
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'properties' => json_encode($properties),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Log authentication event
     */
    public function logAuth(string $action, User $user = null, array $properties = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user ? $user->id : Auth::id(),
            'action' => $action,
            'module' => 'authentication',
            'description' => $this->getAuthDescription($action, $user),
            'properties' => json_encode($properties),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Log CRUD operation
     */
    public function logCrud(
        string $operation,
        string $module,
        string $resourceType,
        $resourceId,
        array $changes = []
    ): ActivityLog {
        $description = $this->getCrudDescription($operation, $resourceType, $resourceId);

        return $this->log(
            "{$operation}_{$resourceType}",
            $module,
            $description,
            array_merge([
                'operation' => $operation,
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
            ], $changes)
        );
    }

    /**
     * Get user activities with filters
     */
    public function getUserActivities(int $userId, array $filters = [])
    {
        $query = ActivityLog::forUser($userId);

        if (isset($filters['module'])) {
            $query->forModule($filters['module']);
        }

        if (isset($filters['action'])) {
            $query->forAction($filters['action']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get module activities with filters
     */
    public function getModuleActivities(string $module, array $filters = [])
    {
        $query = ActivityLog::forModule($module);

        if (isset($filters['action'])) {
            $query->forAction($filters['action']);
        }

        if (isset($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        return $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 10)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities for super admin dashboard
     */
    public function getAllActivities(array $filters = [])
    {
        $query = ActivityLog::with('user');

        if (isset($filters['module'])) {
            $query->forModule($filters['module']);
        }

        if (isset($filters['action'])) {
            $query->forAction($filters['action']);
        }

        if (isset($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        if (isset($filters['search'])) {
            $query->where('description', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Get activity statistics
     */
    public function getStatistics(array $filters = [])
    {
        $query = ActivityLog::query();

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        return [
            'total_activities' => $query->count(),
            'unique_users' => $query->distinct('user_id')->count(),
            'by_module' => $query->select('module', DB::raw('count(*) as count'))
                ->groupBy('module')
                ->pluck('count', 'module'),
            'by_action' => $query->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->pluck('count', 'action'),
        ];
    }

    /**
     * Clean old logs (optional maintenance)
     */
    public function cleanOldLogs(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        return ActivityLog::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Get auth description
     */
    private function getAuthDescription(string $action, ?User $user): string
    {
        $userName = $user ? $user->name : (Auth::user() ? Auth::user()->name : 'Unknown');

        return match($action) {
            'login' => "User {$userName} logged in",
            'logout' => "User {$userName} logged out",
            'login_failed' => "Failed login attempt for {$userName}",
            'account_locked' => "Account {$userName} was locked due to multiple failed login attempts",
            'password_reset_requested' => "User {$userName} requested password reset",
            'password_reset_completed' => "User {$userName} completed password reset",
            'register' => "New user {$userName} registered",
            default => "Authentication action: {$action}",
        };
    }

    /**
     * Get CRUD description
     */
    private function getCrudDescription(string $operation, string $resourceType, $resourceId): string
    {
        $userName = Auth::user() ? Auth::user()->name : 'Unknown';

        return match($operation) {
            'create' => "User {$userName} created {$resourceType} #{$resourceId}",
            'update' => "User {$userName} updated {$resourceType} #{$resourceId}",
            'delete' => "User {$userName} deleted {$resourceType} #{$resourceId}",
            'view' => "User {$userName} viewed {$resourceType} #{$resourceId}",
            default => "User {$userName} performed {$operation} on {$resourceType} #{$resourceId}",
        };
    }
}
