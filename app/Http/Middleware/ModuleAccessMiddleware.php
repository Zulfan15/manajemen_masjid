<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();

        // Jika tidak login, redirect ke login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Super admin bisa akses semua module (read-only)
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Cek akses module untuk user
        // Format role: admin_keuangan, pengurus_keuangan, dll
        $allowedRoles = [
            "admin_{$module}",
            "pengurus_{$module}",
        ];

        // Khusus untuk keuangan, jamaah juga bisa akses (untuk input pemasukan)
        if ($module === 'keuangan') {
            $allowedRoles[] = 'jamaah';
        }

        // Cek apakah user punya salah satu role yang diizinkan
        if (!$user->hasAnyRole($allowedRoles)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke modul ini');
        }

        return $next($request);
    }
}