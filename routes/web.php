<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\LaporanKegiatanController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\KurbanController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventarisController;

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
    // MODULE 5: QURBAN MANAGEMENT (FULLY IMPLEMENTED)
    // =========================================================================
    Route::middleware(['permission:kurban.view'])->prefix('kurban')->name('kurban.')->group(function () {
        // Data Kurban (Main CRUD)
        Route::get('/', [KurbanController::class, 'index'])->name('index');
        Route::get('/create', [KurbanController::class, 'create'])->name('create');
        Route::post('/', [KurbanController::class, 'store'])->name('store');
        Route::get('/{kurban}', [KurbanController::class, 'show'])->name('show');
        Route::get('/{kurban}/edit', [KurbanController::class, 'edit'])->name('edit');
        Route::put('/{kurban}', [KurbanController::class, 'update'])->name('update');
        Route::delete('/{kurban}', [KurbanController::class, 'destroy'])->name('destroy');

        // Peserta Kurban
        Route::get('/{kurban}/peserta/create', [KurbanController::class, 'createPeserta'])->name('peserta.create');
        Route::post('/{kurban}/peserta', [KurbanController::class, 'storePeserta'])->name('peserta.store');
        Route::get('/{kurban}/peserta/{peserta}/edit', [KurbanController::class, 'editPeserta'])->name('peserta.edit');
        Route::put('/{kurban}/peserta/{peserta}', [KurbanController::class, 'updatePeserta'])->name('peserta.update');
        Route::delete('/{kurban}/peserta/{peserta}', [KurbanController::class, 'destroyPeserta'])->name('peserta.destroy');

        // Distribusi Kurban
        Route::get('/{kurban}/distribusi/create', [KurbanController::class, 'createDistribusi'])->name('distribusi.create');
        Route::post('/{kurban}/distribusi', [KurbanController::class, 'storeDistribusi'])->name('distribusi.store');
        Route::get('/{kurban}/distribusi/{distribusi}/edit', [KurbanController::class, 'editDistribusi'])->name('distribusi.edit');
        Route::put('/{kurban}/distribusi/{distribusi}', [KurbanController::class, 'updateDistribusi'])->name('distribusi.update');
        Route::delete('/{kurban}/distribusi/{distribusi}', [KurbanController::class, 'destroyDistribusi'])->name('distribusi.destroy');
    });

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
        
        // Kategori Pengeluaran
        Route::resource('kategori-pengeluaran', \App\Http\Controllers\KategoriPengeluaranController::class)
            ->except(['create', 'edit', 'show']);
        
        // Cetak Laporan (before resource route)
        Route::get('pengeluaran/cetak-laporan', [\App\Http\Controllers\PengeluaranController::class, 'cetakLaporan'])
            ->name('pengeluaran.cetak');
        
        // Transaksi Pengeluaran
        Route::resource('pengeluaran', \App\Http\Controllers\PengeluaranController::class)
            ->except(['create', 'edit', 'show']);
    });

    // Module 3: Activities & Events
    Route::middleware(['module.access:kegiatan'])->prefix('kegiatan')->name('kegiatan.')->group(function () {
        // Pengumuman (MUST BE BEFORE /{id} routes)
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [PengumumanController::class, 'index'])->name('index');
            Route::get('/create', [PengumumanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
            Route::post('/', [PengumumanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
            Route::get('/{pengumuman}', [PengumumanController::class, 'show'])->name('show');
            Route::get('/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
            Route::put('/{pengumuman}', [PengumumanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
            Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
        });
        
        // Laporan Kegiatan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanKegiatanController::class, 'index'])->name('index');
            Route::get('/create', [LaporanKegiatanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
            Route::post('/', [LaporanKegiatanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
            Route::get('/{laporan}', [LaporanKegiatanController::class, 'show'])->name('show');
            Route::get('/{laporan}/edit', [LaporanKegiatanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
            Route::put('/{laporan}', [LaporanKegiatanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
            Route::delete('/{laporan}', [LaporanKegiatanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
            Route::get('/{laporan}/download', [LaporanKegiatanController::class, 'download'])->name('download');
        });
        
        // Generate Sertifikat
        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/', [SertifikatController::class, 'index'])->name('index');
            Route::post('/generate', [SertifikatController::class, 'generate'])->name('generate')->middleware('permission:kegiatan.create');
            Route::get('/{sertifikat}/download', [SertifikatController::class, 'download'])->name('download');
            Route::post('/download-batch', [SertifikatController::class, 'downloadBatch'])->name('download-batch')->middleware('permission:kegiatan.create');
            Route::delete('/{sertifikat}', [App\Http\Controllers\SertifikatController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
            Route::get('/peserta', [App\Http\Controllers\SertifikatController::class, 'getPeserta'])->name('peserta');
        });
        
        // CRUD Kegiatan (MUST BE AFTER all prefixed routes)
        Route::get('/', [App\Http\Controllers\KegiatanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\KegiatanController::class, 'create'])->name('create')->middleware('permission:kegiatan.create');
        Route::post('/', [App\Http\Controllers\KegiatanController::class, 'store'])->name('store')->middleware('permission:kegiatan.create');
        
        // Pendaftaran Peserta
        Route::post('/{id}/register', [App\Http\Controllers\KegiatanController::class, 'registerPeserta'])->name('register');
        
        // Absensi
        Route::get('/{id}/absensi', [App\Http\Controllers\KegiatanController::class, 'absensi'])->name('absensi')->middleware('permission:kegiatan.update');
        Route::post('/{id}/absensi', [App\Http\Controllers\KegiatanController::class, 'storeAbsensi'])->name('absensi.store')->middleware('permission:kegiatan.update');
        
        // Notifikasi
        Route::post('/{id}/broadcast', [App\Http\Controllers\KegiatanController::class, 'broadcastNotification'])->name('broadcast')->middleware('permission:kegiatan.create');
        
        // Dynamic routes with {id} parameter (MUST BE LAST)
        Route::get('/{id}', [App\Http\Controllers\KegiatanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\KegiatanController::class, 'edit'])->name('edit')->middleware('permission:kegiatan.update');
        Route::put('/{id}', [App\Http\Controllers\KegiatanController::class, 'update'])->name('update')->middleware('permission:kegiatan.update');
        Route::delete('/{id}', [App\Http\Controllers\KegiatanController::class, 'destroy'])->name('destroy')->middleware('permission:kegiatan.delete');
    });

    // Module 4: ZIS Management
    Route::middleware(['module.access:zis'])->prefix('zis')->name('zis.')->group(function () {
        Route::get('/', function () {
            return view('modules.zis.index');
        })->name('index');
    });

    // Module 6: Inventory Management
    Route::middleware(['module.access:inventaris'])
        ->prefix('inventaris')
        ->name('inventaris.')
        ->group(function () {

            Route::get('/', [InventarisController::class, 'index'])->name('index');

            Route::get('/aset', [InventarisController::class, 'asetIndex'])->name('aset.index');
            Route::get('/aset/create', [InventarisController::class, 'asetCreate'])->name('aset.create');
            Route::get('/aset/{id}/edit', [InventarisController::class, 'asetEdit'])->name('aset.edit');
            Route::get('/aset/{id}', [InventarisController::class, 'asetShow'])->name('aset.show');
            Route::post('/aset', [InventarisController::class, 'asetStore'])->name('aset.store');
            Route::put('/aset/{id}', [InventarisController::class, 'asetUpdate'])->name('aset.update');
            Route::delete('/aset/{id}', [InventarisController::class, 'asetDestroy'])->name('aset.destroy');

            Route::get('/petugas', [InventarisController::class, 'petugasIndex'])->name('petugas.index');
            Route::get('/petugas/create', [InventarisController::class, 'petugasCreate'])->name('petugas.create');
        });

    // Module 7: Takmir Management
    Route::middleware(['module.access:takmir'])->prefix('takmir')->name('takmir.')->group(function () {
        // Aktivitas Harian routes - HARUS DI ATAS resource root untuk menghindari konflik
        Route::resource('aktivitas', \App\Http\Controllers\AktivitasHarianController::class)->parameters(['aktivitas' => 'aktivita']);

        // Pemilihan routes
        Route::prefix('pemilihan')->name('pemilihan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PemilihanController::class, 'index'])->name('index');
            Route::get('/{id}/vote', [\App\Http\Controllers\PemilihanController::class, 'vote'])->name('vote');
            Route::post('/{id}/vote', [\App\Http\Controllers\PemilihanController::class, 'submitVote'])->name('submitVote');
            Route::get('/{id}/hasil', [\App\Http\Controllers\PemilihanController::class, 'hasil'])->name('hasil');

            // Admin routes
            Route::get('/create', [\App\Http\Controllers\PemilihanController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\PemilihanController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\PemilihanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\PemilihanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\PemilihanController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\PemilihanController::class, 'destroy'])->name('destroy');
        });

        // Takmir resource (menggunakan root)
        Route::resource('/', \App\Http\Controllers\TakmirController::class)->parameters(['' => 'takmir']);
    });

    // Module 8: Information & Announcements
    Route::middleware(['module.access:informasi'])->prefix('informasi')->name('informasi.')->group(function () {
        // Dashboard Informasi (card Berita / Pengumuman / Artikel / Notifikasi)
        Route::get('/', [InformasiController::class, 'index'])->name('index');

        // CRUD masing-masing resource
        Route::resource('berita', NewsController::class);
        Route::resource('pengumuman', AnnouncementController::class);
        Route::resource('artikel', ArticleController::class);

        // Notifikasi
        Route::get('notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
        Route::post('notifikasi/send', [NotificationController::class, 'send'])->name('notifikasi.send');
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
