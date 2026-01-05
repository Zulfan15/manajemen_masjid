<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Assign role to user
     */
    public function assignRole(User $user, string $roleName, User $assignedBy): bool
    {
        try {
            DB::beginTransaction();

            $role = Role::findByName($roleName);
            $user->assignRole($role);

            // Log the activity
            $this->activityLogService->log(
                'role_assigned',
                'user_management',
                "Role '{$role->display_name}' assigned to user {$user->name}",
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'role_name' => $roleName,
                    'role_display_name' => $role->display_name,
                    'assigned_by' => $assignedBy->name,
                    'assigned_by_id' => $assignedBy->id,
                ]
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Role assignment failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, string $roleName, User $removedBy): bool
    {
        try {
            DB::beginTransaction();

            $role = Role::findByName($roleName);
            $user->removeRole($role);

            // Log the activity
            $this->activityLogService->log(
                'role_removed',
                'user_management',
                "Role '{$role->display_name}' removed from user {$user->name}",
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'role_name' => $roleName,
                    'role_display_name' => $role->display_name,
                    'removed_by' => $removedBy->name,
                    'removed_by_id' => $removedBy->id,
                ]
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Role removal failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all users with specific role
     */
    public function getUsersByRole(string $roleName)
    {
        return User::role($roleName)->get();
    }

    /**
     * Promote jamaah to module officer
     */
    public function promoteToOfficer(User $user, string $module, User $assignedBy): bool
    {
        $roleName = "pengurus_{$module}";
        
        // Check if user has jamaah role
        if (!$user->hasRole('jamaah')) {
            return false;
        }

        return $this->assignRole($user, $roleName, $assignedBy);
    }

    /**
     * Demote officer back to jamaah
     */
    public function demoteToJamaah(User $user, string $module, User $removedBy): bool
    {
        $roleName = "pengurus_{$module}";
        return $this->removeRole($user, $roleName, $removedBy);
    }

    /**
     * Get all roles for a module
     */
    public function getModuleRoles(string $module)
    {
        return Role::where('name', 'like', "%{$module}%")->get();
    }

    /**
     * Get all available roles
     */
    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Get roles by type (admin or pengurus)
     */
    public function getRolesByType(string $type)
    {
        return Role::where('name', 'like', "{$type}_%")->get();
    }

    /**
     * Check if role exists
     */
    public function roleExists(string $roleName): bool
    {
        return Role::where('name', $roleName)->exists();
    }

    /**
     * Get users who can be promoted in a module
     */
    public function getPromotableUsers(string $module)
    {
        // Get users who have jamaah role but not officer role for this module
        return User::role('jamaah')
            ->whereDoesntHave('roles', function ($query) use ($module) {
                $query->where('name', "pengurus_{$module}")
                      ->orWhere('name', "admin_{$module}");
            })
            ->get();
    }

    /**
     * Get module officers
     */
    public function getModuleOfficers(string $module)
    {
        return User::role("pengurus_{$module}")->get();
    }

    /**
     * Get module admin
     */
    public function getModuleAdmin(string $module)
    {
        return User::role("admin_{$module}")->first();
    }

    /**
     * Bulk assign role to multiple users
     */
    public function bulkAssignRole(array $userIds, string $roleName, User $assignedBy): int
    {
        $successCount = 0;
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user && $this->assignRole($user, $roleName, $assignedBy)) {
                $successCount++;
            }
        }

        return $successCount;
    }
}
