<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RoleService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    protected $roleService;
    protected $authService;

    public function __construct(RoleService $roleService, AuthService $authService)
    {
        $this->roleService = $roleService;
        $this->authService = $authService;
    }

    /**
     * Show user management page
     */
    public function index()
    {
        $currentUser = $this->authService->getCurrentUser();
        $users = User::with('roles')->paginate(15);

        return view('users.index', compact('users', 'currentUser'));
    }

    /**
     * Show promote user form for specific module
     */
    public function showPromote(string $module)
    {
        // Check if user is module admin
        if (!$this->authService->isModuleAdmin($module)) {
            abort(403, 'Anda tidak memiliki akses untuk mempromosikan user di modul ini.');
        }

        $promotableUsers = $this->roleService->getPromotableUsers($module);
        $officers = $this->roleService->getModuleOfficers($module);

        return view('users.promote', compact('module', 'promotableUsers', 'officers'));
    }

    /**
     * Promote user to officer
     */
    public function promote(Request $request, string $module)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if (!$this->authService->isModuleAdmin($module)) {
            abort(403, 'Anda tidak memiliki akses untuk mempromosikan user di modul ini.');
        }

        $user = User::findOrFail($request->user_id);
        $currentUser = $this->authService->getCurrentUser();

        $success = $this->roleService->promoteToOfficer($user, $module, $currentUser);

        if ($success) {
            return redirect()->route('users.promote.show', $module)
                ->with('success', "User {$user->name} berhasil dipromosikan menjadi pengurus {$module}.");
        }

        return back()->with('error', 'Gagal mempromosikan user.');
    }

    /**
     * Demote officer back to jamaah
     */
    public function demote(Request $request, string $module, int $userId)
    {
        if (!$this->authService->isModuleAdmin($module)) {
            abort(403, 'Anda tidak memiliki akses untuk menurunkan pangkat user di modul ini.');
        }

        $user = User::findOrFail($userId);
        $currentUser = $this->authService->getCurrentUser();

        $success = $this->roleService->demoteToJamaah($user, $module, $currentUser);

        if ($success) {
            return redirect()->route('users.promote.show', $module)
                ->with('success', "User {$user->name} berhasil diturunkan ke jamaah.");
        }

        return back()->with('error', 'Gagal menurunkan pangkat user.');
    }

    /**
     * Show user roles
     */
    public function showRoles(int $userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $allRoles = $this->roleService->getAllRoles();

        return view('users.roles', compact('user', 'allRoles'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, int $userId)
    {
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        // Only super admin can assign roles directly
        if (!$this->authService->isSuperAdmin()) {
            abort(403, 'Hanya super admin yang dapat mengubah role secara langsung.');
        }

        $user = User::findOrFail($userId);
        $currentUser = $this->authService->getCurrentUser();

        $success = $this->roleService->assignRole($user, $request->role_name, $currentUser);

        if ($success) {
            return back()->with('success', 'Role berhasil ditambahkan.');
        }

        return back()->with('error', 'Gagal menambahkan role.');
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, int $userId, string $roleName)
    {
        // Only super admin can remove roles directly
        if (!$this->authService->isSuperAdmin()) {
            abort(403, 'Hanya super admin yang dapat menghapus role secara langsung.');
        }

        $user = User::findOrFail($userId);
        $currentUser = $this->authService->getCurrentUser();

        $success = $this->roleService->removeRole($user, $roleName, $currentUser);

        if ($success) {
            return back()->with('success', 'Role berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus role.');
    }
}
