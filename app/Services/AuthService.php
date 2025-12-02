<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Get currently authenticated user with roles
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Check if current user has specific role
     */
    public function hasRole(string $role): bool
    {
        $user = $this->getCurrentUser();
        return $user ? $user->hasRole($role) : false;
    }

    /**
     * Check if current user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $user = $this->getCurrentUser();
        return $user ? $user->hasPermissionTo($permission) : false;
    }

    /**
     * Check if current user has any of given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $user = $this->getCurrentUser();
        return $user ? $user->hasAnyRole($roles) : false;
    }

    /**
     * Check if current user can access specific module
     */
    public function canAccessModule(string $module): bool
    {
        $user = $this->getCurrentUser();
        return $user ? $user->canAccessModule($module) : false;
    }

    /**
     * Get accessible modules for current user
     */
    public function getAccessibleModules(): array
    {
        $user = $this->getCurrentUser();
        return $user ? $user->getAccessibleModules() : [];
    }

    /**
     * Check if current user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if current user is module admin
     */
    public function isModuleAdmin(string $module): bool
    {
        return $this->hasRole("admin_{$module}");
    }

    /**
     * Check if user can perform action on module
     */
    public function canPerformAction(string $module, string $action): bool
    {
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return false;
        }

        // Super admin can only view
        if ($user->isSuperAdmin()) {
            return $action === 'view';
        }

        // Check module-specific permission
        return $user->hasPermissionTo("{$module}.{$action}");
    }

    /**
     * Get user's role display names
     */
    public function getUserRoleNames(): array
    {
        $user = $this->getCurrentUser();
        return $user ? $user->roles->pluck('display_name')->toArray() : [];
    }

    /**
     * Get navigation items based on user permissions
     */
    public function getNavigationItems(): array
    {
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return [];
        }

        $modules = [
            'jamaah' => 'Manajemen Jamaah',
            'keuangan' => 'Keuangan Masjid',
            'kegiatan' => 'Kegiatan & Acara',
            'zis' => 'Manajemen ZIS',
            'kurban' => 'Manajemen Kurban',
            'inventaris' => 'Manajemen Inventaris',
            'takmir' => 'Manajemen Takmir',
            'informasi' => 'Informasi & Pengumuman',
            'laporan' => 'Laporan & Statistik',
        ];

        $navigation = [];
        $accessibleModules = $user->getAccessibleModules();

        foreach ($modules as $key => $label) {
            if (in_array($key, $accessibleModules)) {
                $navigation[] = [
                    'key' => $key,
                    'label' => $label,
                    'url' => route("{$key}.index"),
                    'can_edit' => $user->canAccessModule($key) && !$user->isSuperAdmin(),
                ];
            }
        }

        return $navigation;
    }
}
