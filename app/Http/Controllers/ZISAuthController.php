<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZISAuthController extends Controller
{
    public function index()
    {
        return view('modules.zis.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('zis')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('zis.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah nih!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('zis')->logout();
        
        $request->session()->regenerateToken();
        
        return redirect(route('zis.login'));
    }
}
