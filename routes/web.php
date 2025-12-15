<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;

// Tambahan controller modul informasi
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================
// PUBLIC INFORMATION (NON LOGIN)
// ============================
Route::get('/info-masjid', [InformasiController::class, 'publicIndex'])
    ->name('public.home');

Route::get('/info-masjid/{slug}', [InformasiController::class, 'publicShow'])
    ->name('public.info.show');


// ============================
// GUEST ROUTES
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.email');
});


// ============================
// AUTH (LOGIN WAJIB)
// ============================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // My Activity Logs
    Route::get('/my-logs', [ActivityLogController::class, 'myLogs'])->name('my-logs');

    // ============================
    // USER MANAGEMENT
    // ============================
    Route::prefix('users')->name('users.')->group(function () {

        Route::get('/', [UserManagementController::class, 'index'])->name('index');

        // Module admin — promote/demote
        Route::get('/promote/{module}', [UserManagementController::class, 'showPromote'])
            ->name('promote.show');
        Route::post('/promote/{module}', [UserManagementController::class, 'promote'])
            ->name('promote');

        Route::delete('/demote/{module}/{userId}', [UserManagementController::class, 'demote'])
            ->name('demote');

        // Role management (super admin only)
        Route::get('/{userId}/roles', [UserManagementController::class, 'showRoles'])
            ->name('roles');
        Route::post('/{userId}/roles', [UserManagementController::class, 'assignRole'])
            ->name('roles.assign');
        Route::delete('/{userId}/roles/{roleName}', [UserManagementController::class, 'removeRole'])
            ->name('roles.remove');
    });

    // ============================
    // ACTIVITY LOGS (SUPER ADMIN)
    // ============================
    Route::middleware(['role:super_admin'])
        ->prefix('activity-logs')
        ->name('activity-logs.')
        ->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/recent', [ActivityLogController::class, 'recent'])->name('recent');
        });

    // Module Activity Logs
    Route::get('/{module}/logs', [ActivityLogController::class, 'moduleLog'])->name('module.logs');

    // =========================================================================
    // MODULE ROUTES
    // =========================================================================

    // 1. JAMA'AH
    Route::middleware(['module.access:jamaah'])
        ->prefix('jamaah')
        ->name('jamaah.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.jamaah.index');
            })->name('index');
        });

    // 2. KEUANGAN
    Route::middleware(['module.access:keuangan'])
        ->prefix('keuangan')
        ->name('keuangan.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.keuangan.index');
            })->name('index');
        });

    // 3. KEGIATAN
    Route::middleware(['module.access:kegiatan'])
        ->prefix('kegiatan')
        ->name('kegiatan.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.kegiatan.index');
            })->name('index');
        });

    // 4. ZIS
    Route::middleware(['module.access:zis'])
        ->prefix('zis')
        ->name('zis.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.zis.index');
            })->name('index');
        });

    // 5. KURBAN
    Route::middleware(['module.access:kurban'])
        ->prefix('kurban')
        ->name('kurban.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.kurban.index');
            })->name('index');
        });

    // 6. INVENTARIS
    Route::middleware(['module.access:inventaris'])
        ->prefix('inventaris')
        ->name('inventaris.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.inventaris.index');
            })->name('index');
        });

    // 7. TAKMIR
    Route::middleware(['module.access:takmir'])
        ->prefix('takmir')
        ->name('takmir.')
            ->group(function () {
            Route::get('/', function () {
                return view('modules.inventaris.index');
            })->name('index');
        });

    // ==========================================================
    // 8. INFORMASI — FULL CRUD ADMIN INFORMASI (PENTING!)
    // ==========================================================
    Route::middleware(['auth', 'module.access:informasi'])
    ->prefix('informasi')
    ->name('informasi.')
    ->group(function () {

        // Dashboard Informasi (card Berita / Pengumuman / Artikel / Notifikasi)
        Route::get('/', [InformasiController::class, 'index'])->name('index');

        // CRUD masing-masing resource
        Route::resource('berita', App\Http\Controllers\NewsController::class);
        Route::resource('pengumuman', App\Http\Controllers\AnnouncementController::class);
        Route::resource('artikel', App\Http\Controllers\ArticleController::class);

        // Notifikasi
        Route::get('notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
        Route::post('notifikasi/send', [NotificationController::class, 'send'])->name('notifikasi.send');

        Route::get('/env-test', function() {
         return env('MAIL_MAILER');
        });
});

    // 9. LAPORAN
    Route::middleware(['module.access:laporan'])
        ->prefix('laporan')
        ->name('laporan.')
        ->group(function () {
            Route::get('/', function () {
                return view('modules.laporan.index');
            })->name('index');
        });
});


// ============================
// DEBUG (opsional)
// ============================
Route::get('/test-user', function () {
    $user = \App\Models\User::where('username', 'superadmin')->first();

    return [
        'found' => !!$user,
        'username' => $user->username ?? null,
        'roles' => $user?->getRoleNames(),
    ];
});
