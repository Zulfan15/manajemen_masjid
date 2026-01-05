<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HistoryController; // ✅ TAMBAHKAN INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/my-logs', [ActivityLogController::class, 'myLogs'])->name('my-logs');

    // User Management (Admin access)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/promote/{module}', [UserManagementController::class, 'showPromote'])->name('promote.show');
        Route::post('/promote/{module}', [UserManagementController::class, 'promote'])->name('promote');
        Route::delete('/demote/{module}/{userId}', [UserManagementController::class, 'demote'])->name('demote');
        Route::get('/{userId}/roles', [UserManagementController::class, 'showRoles'])->name('roles');
        Route::post('/{userId}/roles', [UserManagementController::class, 'assignRole'])->name('roles.assign');
        Route::delete('/{userId}/roles/{roleName}', [UserManagementController::class, 'removeRole'])->name('roles.remove');
    });

    // Activity Logs (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/recent', [ActivityLogController::class, 'recent'])->name('recent');
    });

    Route::get('/{module}/logs', [ActivityLogController::class, 'moduleLog'])->name('module.logs');

    // ROUTE JAMAAH/USER
    Route::prefix('jamaah')->name('jamaah.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('jamaah.pemasukan');
        })->name('index');
        
        Route::get('/pemasukan', [PemasukanController::class, 'jamaahPemasukan'])->name('pemasukan');
        Route::post('/pemasukan', [PemasukanController::class, 'jamaahStore'])->name('pemasukan.store');
        Route::get('/pemasukan/{id}', [PemasukanController::class, 'jamaahDetail'])->name('pemasukan.detail');
    });

    // =========================================================================
    // KEUANGAN MODULE - PEMASUKAN, LAPORAN & HISTORY
    // =========================================================================
    Route::middleware(['module.access:keuangan'])->group(function () {
        
        // PEMASUKAN ROUTES (ADMIN)
        Route::prefix('pemasukan')->name('pemasukan.')->group(function () {
            Route::get('/', [PemasukanController::class, 'index'])->name('index');
            Route::get('/create', [PemasukanController::class, 'create'])->name('create');
            Route::post('/', [PemasukanController::class, 'store'])->name('store');
            Route::get('/{id}/data', [PemasukanController::class, 'getData'])->name('getData');
            Route::get('/{id}', [PemasukanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PemasukanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PemasukanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PemasukanController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/verifikasi', [PemasukanController::class, 'verifikasi'])->name('verifikasi');
            Route::post('/{id}/tolak', [PemasukanController::class, 'tolak'])->name('tolak');
            Route::post('/send-cancellation', [PemasukanController::class, 'sendCancellationNotification'])->name('sendCancellation');
        });

        // ✅ HISTORY ROUTES - BARU DITAMBAHKAN
        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/', [HistoryController::class, 'index'])->name('index');
            Route::post('/restore/{id}', [HistoryController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [HistoryController::class, 'forceDelete'])->name('forceDelete');
            Route::post('/restore-all', [HistoryController::class, 'restoreAll'])->name('restoreAll');
            Route::delete('/reset-all', [HistoryController::class, 'resetAll'])->name('resetAll');
        });

        // LAPORAN ROUTES
        Route::prefix('keuangan/laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/rekap', [LaporanController::class, 'rekap'])->name('rekap');
            Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('exportPdf');
            Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('exportExcel');
        });
    });

    // MODULE ROUTES - NAVIGATION ONLY
    Route::middleware(['module.access:jamaah'])->prefix('jamaah-admin')->name('jamaah-admin.')->group(function () {
        Route::get('/', function () {
            return view('modules.jamaah.index');
        })->name('index');
    });

    Route::middleware(['module.access:keuangan'])->prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', function () {
            return view('modules.keuangan.index');
        })->name('index');
    });

    Route::middleware(['module.access:kegiatan'])->prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', function () {
            return view('modules.kegiatan.index');
        })->name('index');
    });

    Route::middleware(['module.access:zis'])->prefix('zis')->name('zis.')->group(function () {
        Route::get('/', function () {
            return view('modules.zis.index');
        })->name('index');
    });

    Route::middleware(['module.access:kurban'])->prefix('kurban')->name('kurban.')->group(function () {
        Route::get('/', function () {
            return view('modules.kurban.index');
        })->name('index');
    });

    Route::middleware(['module.access:inventaris'])->prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', function () {
            return view('modules.inventaris.index');
        })->name('index');
    });

    Route::middleware(['module.access:takmir'])->prefix('takmir')->name('takmir.')->group(function () {
        Route::get('/', function () {
            return view('modules.takmir.index');
        })->name('index');
    });

    Route::middleware(['module.access:informasi'])->prefix('informasi')->name('informasi.')->group(function () {
        Route::get('/', function () {
            return view('modules.informasi.index');
        })->name('index');
    });

    Route::middleware(['module.access:laporan'])->prefix('laporan')->name('laporan-umum.')->group(function () {
        Route::get('/', function () {
            return view('modules.laporan.index');
        })->name('index');
    });
});

// Test Email Route
Route::get('/test-email', function () {
    Mail::raw('Test email dari Laravel - Manajemen Masjid!', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email dari Manajemen Masjid');
    });
    
    return 'Email berhasil dikirim! Cek file: storage/logs/laravel.log';
});

// Test route
Route::get('/test-user', function () {
    $user = \App\Models\User::where('username', 'superadmin')->first();
    if (!$user) {
        return 'User superadmin tidak ditemukan!';
    }
    
    $passwordValid = \Illuminate\Support\Facades\Hash::check('password', $user->password);
    
    return [
        'user_found' => true,
        'username' => $user->username,
        'email' => $user->email,
        'name' => $user->name,
        'password_valid' => $passwordValid,
        'roles' => $user->getRoleNames(),
        'message' => $passwordValid ? 'Login seharusnya berhasil!' : 'Password tidak cocok!'
    ];
});