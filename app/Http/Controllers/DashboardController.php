<?php

namespace App\Http\Controllers;

use App\Services\AuthService;

class DashboardController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show dashboard
     */
    public function index()
    {
        $user = $this->authService->getCurrentUser();
        $accessibleModules = $this->authService->getAccessibleModules();
        $navigation = $this->authService->getNavigationItems();

        return view('dashboard.index', compact('user', 'accessibleModules', 'navigation'));
    }
}
