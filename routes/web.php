<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;

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
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // My Activity Logs
    Route::get('/my-logs', [ActivityLogController::class, 'myLogs'])->name('my-logs');

    // User Management (Admin access)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        
        // Module-specific promotion (for module admins)
        Route::get('/promote/{module}', [UserManagementController::class, 'showPromote'])->name('promote.show');
        Route::post('/promote/{module}', [UserManagementController::class, 'promote'])->name('promote');
        Route::delete('/demote/{module}/{userId}', [UserManagementController::class, 'demote'])->name('demote');
        
        // Role management (super admin only)
        Route::get('/{userId}/roles', [UserManagementController::class, 'showRoles'])->name('roles');
        Route::post('/{userId}/roles', [UserManagementController::class, 'assignRole'])->name('roles.assign');
        Route::delete('/{userId}/roles/{roleName}', [UserManagementController::class, 'removeRole'])->name('roles.remove');
    });

    // Activity Logs (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/recent', [ActivityLogController::class, 'recent'])->name('recent');
    });

    // Module Activity Logs
    Route::get('/{module}/logs', [ActivityLogController::class, 'moduleLog'])->name('module.logs');

    // =========================================================================
    // MODULE ROUTES - NAVIGATION ONLY (No Implementation)
    // =========================================================================
    
    // Module 1: Jamaah Management
    Route::middleware(['module.access:jamaah'])->prefix('jamaah')->name('jamaah.')->group(function () {
        Route::get('/', function () {
            return view('modules.jamaah.index');
        })->name('index');
    });

    // Module 2: Finance
    Route::middleware(['module.access:keuangan'])->prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', function () {
            return view('modules.keuangan.index');
        })->name('index');
    });

    // Module 3: Activities & Events
    Route::middleware(['module.access:kegiatan'])->prefix('kegiatan')->name('kegiatan.')->group(function () {
        // CRUD Kegiatan
        Route::get('/', [App\Http\Controllers\KegiatanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\KegiatanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
        Route::post('/', [App\Http\Controllers\KegiatanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
        Route::get('/{id}', [App\Http\Controllers\KegiatanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\KegiatanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
        Route::put('/{id}', [App\Http\Controllers\KegiatanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
        Route::delete('/{id}', [App\Http\Controllers\KegiatanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
        
        // Pendaftaran Peserta
        Route::post('/{id}/register', [App\Http\Controllers\KegiatanController::class, 'registerPeserta'])->name('register');
        
        // Absensi
        Route::get('/{id}/absensi', [App\Http\Controllers\KegiatanController::class, 'absensi'])->name('absensi')->middleware('permission:kegiatan.update');
        Route::post('/{id}/absensi', [App\Http\Controllers\KegiatanController::class, 'storeAbsensi'])->name('absensi.store')->middleware('permission:kegiatan.update');
        
        // Notifikasi
        Route::post('/{id}/broadcast', [App\Http\Controllers\KegiatanController::class, 'broadcastNotification'])->name('broadcast')->middleware('permission:kegiatan.create');
        
        // Pengumuman
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [App\Http\Controllers\PengumumanController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\PengumumanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
            Route::post('/', [App\Http\Controllers\PengumumanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
            Route::get('/{pengumuman}', [App\Http\Controllers\PengumumanController::class, 'show'])->name('show');
            Route::get('/{pengumuman}/edit', [App\Http\Controllers\PengumumanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
            Route::put('/{pengumuman}', [App\Http\Controllers\PengumumanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
            Route::delete('/{pengumuman}', [App\Http\Controllers\PengumumanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
        });
        
        // Laporan Kegiatan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [App\Http\Controllers\LaporanKegiatanController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\LaporanKegiatanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
            Route::post('/', [App\Http\Controllers\LaporanKegiatanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
            Route::get('/{laporan}', [App\Http\Controllers\LaporanKegiatanController::class, 'show'])->name('show');
            Route::get('/{laporan}/edit', [App\Http\Controllers\LaporanKegiatanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
            Route::put('/{laporan}', [App\Http\Controllers\LaporanKegiatanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
            Route::delete('/{laporan}', [App\Http\Controllers\LaporanKegiatanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
            Route::get('/{laporan}/download', [App\Http\Controllers\LaporanKegiatanController::class, 'download'])->name('download');
        });
        
        // Generate Sertifikat
        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/', [App\Http\Controllers\SertifikatController::class, 'index'])->name('index');
            Route::post('/generate', [App\Http\Controllers\SertifikatController::class, 'generate'])->name('generate')->middleware('permission:kegiatan.create');
            Route::get('/{sertifikat}/download', [App\Http\Controllers\SertifikatController::class, 'download'])->name('download');
            Route::post('/download-batch', [App\Http\Controllers\SertifikatController::class, 'downloadBatch'])->name('download-batch')->middleware('permission:kegiatan.create');
            Route::delete('/{sertifikat}', [App\Http\Controllers\SertifikatController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
            Route::get('/peserta', [App\Http\Controllers\SertifikatController::class, 'getPeserta'])->name('peserta');
        });
    });

    // Module 4: ZIS Management
    Route::middleware(['module.access:zis'])->prefix('zis')->name('zis.')->group(function () {
        Route::get('/', function () {
            return view('modules.zis.index');
        })->name('index');
    });

    // Module 5: Qurban Management
    Route::middleware(['module.access:kurban'])->prefix('kurban')->name('kurban.')->group(function () {
        Route::get('/', function () {
            return view('modules.kurban.index');
        })->name('index');
    });

    // Module 6: Inventory Management
    Route::middleware(['module.access:inventaris'])->prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', function () {
            return view('modules.inventaris.index');
        })->name('index');
    });

    // Module 7: Takmir Management
    Route::middleware(['module.access:takmir'])->prefix('takmir')->name('takmir.')->group(function () {
        Route::get('/', function () {
            return view('modules.takmir.index');
        })->name('index');
    });

    // Module 8: Information & Announcements
    Route::middleware(['module.access:informasi'])->prefix('informasi')->name('informasi.')->group(function () {
        Route::get('/', function () {
            return view('modules.informasi.index');
        })->name('index');
    });

    // Module 9: Reports & Statistics
    Route::middleware(['module.access:laporan'])->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', function () {
            return view('modules.laporan.index');
        })->name('index');
    });
});

// Test route (remove in production)
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
