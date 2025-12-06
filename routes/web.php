<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Kurban\KurbanPesertaController;
use App\Http\Controllers\Kurban\KurbanHewanController;
use App\Http\Controllers\Kurban\KurbanAlokasiController;
use App\Http\Controllers\Kurban\KurbanPenyembelihanController;
use App\Http\Controllers\Kurban\KurbanHasilPotongController;
use App\Http\Controllers\Kurban\KurbanPenerimaController;
use App\Http\Controllers\Kurban\KurbanDistribusiController;

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
        Route::get('/', function () {
            return view('modules.kegiatan.index');
        })->name('index');
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
        // Peserta Kurban
        Route::get('/peserta', [KurbanPesertaController::class, 'index'])->name('peserta.index');
        Route::get('/peserta/create', [KurbanPesertaController::class, 'create'])->name('peserta.create');
        Route::post('/peserta', [KurbanPesertaController::class, 'store'])->name('peserta.store');
        Route::get('/peserta/{id}/edit', [KurbanPesertaController::class, 'edit'])->name('peserta.edit');
        Route::put('/peserta/{id}', [KurbanPesertaController::class, 'update'])->name('peserta.update');
        Route::delete('/peserta/{id}', [KurbanPesertaController::class, 'destroy'])->name('peserta.destroy');

        // Hewan Kurban
        Route::get('/hewan', [KurbanHewanController::class, 'index'])->name('hewan.index');
        Route::get('/hewan/create', [KurbanHewanController::class, 'create'])->name('hewan.create');
        Route::post('/hewan', [KurbanHewanController::class, 'store'])->name('hewan.store');
        Route::get('/hewan/{id}/edit', [KurbanHewanController::class, 'edit'])->name('hewan.edit');
        Route::put('/hewan/{id}', [KurbanHewanController::class, 'update'])->name('hewan.update');
        Route::delete('/hewan/{id}', [KurbanHewanController::class, 'destroy'])->name('hewan.destroy');

        // Alokasi Peserta ↔ Hewan
        Route::get('/alokasi', [KurbanAlokasiController::class, 'index'])->name('alokasi.index');
        Route::get('/alokasi/create', [KurbanAlokasiController::class, 'create'])->name('alokasi.create');
        Route::post('/alokasi', [KurbanAlokasiController::class, 'store'])->name('alokasi.store');
        Route::delete('/alokasi/{id}', [KurbanAlokasiController::class, 'destroy'])->name('alokasi.destroy');

        // Jadwal Penyembelihan
        Route::get('/penyembelihan', [KurbanPenyembelihanController::class, 'index'])->name('penyembelihan.index');
        Route::get('/penyembelihan/create', [KurbanPenyembelihanController::class, 'create'])->name('penyembelihan.create');
        Route::post('/penyembelihan', [KurbanPenyembelihanController::class, 'store'])->name('penyembelihan.store');
        Route::get('/penyembelihan/{id}/edit', [KurbanPenyembelihanController::class, 'edit'])->name('penyembelihan.edit');
        Route::put('/penyembelihan/{id}', [KurbanPenyembelihanController::class, 'update'])->name('penyembelihan.update');

        // Hasil Potongan
        Route::get('/hasil', [KurbanHasilPotongController::class, 'index'])->name('hasil.index');
        Route::get('/hasil/create', [KurbanHasilPotongController::class, 'create'])->name('hasil.create');
        Route::post('/hasil', [KurbanHasilPotongController::class, 'store'])->name('hasil.store');

        // Penerima Manfaat
        Route::get('/penerima', [KurbanPenerimaController::class, 'index'])->name('penerima.index');
        Route::get('/penerima/create', [KurbanPenerimaController::class, 'create'])->name('penerima.create');
        Route::post('/penerima', [KurbanPenerimaController::class, 'store'])->name('penerima.store');

        // Distribusi Daging
        Route::get('/distribusi', [KurbanDistribusiController::class, 'index'])->name('distribusi.index');
        Route::get('/distribusi/create', [KurbanDistribusiController::class, 'create'])->name('distribusi.create');
        Route::post('/distribusi', [KurbanDistribusiController::class, 'store'])->name('distribusi.store');
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
