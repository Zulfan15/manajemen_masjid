<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected $authService;
    protected $activityLogService;

    public function __construct(AuthService $authService, ActivityLogService $activityLogService)
    {
        $this->authService = $authService;
        $this->activityLogService = $activityLogService;
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            // $this->activityLogService->logAuth('login_failed', null, [
            //     'username' => $request->username,
            //     'reason' => 'user_not_found',
            // ]);

            return back()->withErrors([
                'username' => 'Username atau email tidak ditemukan.',
            ])->withInput();
        }

        // Check if account is locked
        if ($user->isLocked()) {
            // $this->activityLogService->logAuth('login_failed', $user, [
            //     'username' => $request->username,
            //     'reason' => 'account_locked',
            // ]);

            return back()->withErrors([
                'username' => 'Akun Anda dikunci. Silakan coba lagi nanti.',
            ])->withInput();
        }

        // Attempt login
        $credentials = [
            filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Reset login attempts and update last login
            $user->resetLoginAttempts();
            $user->updateLastLogin();

            // Log successful login
            // $this->activityLogService->logAuth('login', $user);

            return redirect()->intended(route('dashboard'));
        }

        // Increment failed login attempts
        $user->incrementLoginAttempts();

        // $this->activityLogService->logAuth('login_failed', $user, [
        //     'username' => $request->username,
        //     'reason' => 'invalid_credentials',
        //     'attempts' => $user->login_attempts,
        // ]);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Assign default jamaah role
        $user->assignRole('jamaah');

        // Log registration
        $this->activityLogService->logAuth('register', $user);

        // Auto login
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout
        $this->activityLogService->logAuth('logout', $user);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah keluar.');
    }

    /**
     * Show password reset request form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Here you would send password reset email
        // For now, we'll just log it
        $user = User::where('email', $request->email)->first();
        
        $this->activityLogService->logAuth('password_reset_requested', $user);

        return back()
            ->with('success', 'Link reset password telah dikirim ke email Anda.');
    }
}
