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

    // =========================================================================
    // MODULE 10: USER MANAGEMENT & ACTIVITY LOGS
    // =========================================================================

    // User Management (Only Super Admin and Module Admins)
    Route::prefix('users')->name('users.')->group(function () {
        // Super Admin & Module Admins can view users
        Route::get('/', [UserManagementController::class, 'index'])
            ->name('index');

        Route::get('/{userId}', [UserManagementController::class, 'show'])
            ->name('show');

        // Module-specific promotion (for module admins)
        Route::get('/promote/{module}', [UserManagementController::class, 'showPromote'])
            ->name('promote.show');
        Route::post('/promote/{module}', [UserManagementController::class, 'promote'])
            ->name('promote');
        Route::delete('/demote/{module}/{userId}', [UserManagementController::class, 'demote'])
            ->name('demote');

        // Role management (super admin only)
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/{userId}/roles', [UserManagementController::class, 'showRoles'])->name('roles');
            Route::post('/{userId}/roles', [UserManagementController::class, 'assignRole'])->name('roles.assign');
            Route::delete('/{userId}/roles/{roleName}', [UserManagementController::class, 'removeRole'])->name('roles.remove');
        });
    });

    // Activity Logs (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/recent', [ActivityLogController::class, 'recent'])->name('recent');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
    });

    // Module Activity Logs (Module Admins can view their module logs)
    Route::get('/{module}/logs', [ActivityLogController::class, 'moduleLog'])
        ->name('module.logs')
        ->middleware(['auth', 'module.access:{module}']);

    // =========================================================================
    // MODULE 5: QURBAN MANAGEMENT (FULLY IMPLEMENTED WITH GRANULAR PERMISSIONS)
    // =========================================================================
    Route::middleware(['module.access:kurban'])->prefix('kurban')->name('kurban.')->group(function () {
        // Data Kurban (Main CRUD)
        Route::get('/', [KurbanController::class, 'index'])
            ->name('index')
            ->middleware('permission:kurban.view');

        Route::get('/create', [KurbanController::class, 'create'])
            ->name('create')
            ->middleware('permission:kurban.create');

        Route::post('/', [KurbanController::class, 'store'])
            ->name('store')
            ->middleware('permission:kurban.create');

        Route::get('/{kurban}', [KurbanController::class, 'show'])
            ->name('show')
            ->middleware('permission:kurban.view');

        Route::get('/{kurban}/edit', [KurbanController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:kurban.update');

        Route::put('/{kurban}', [KurbanController::class, 'update'])
            ->name('update')
            ->middleware('permission:kurban.update');

        Route::delete('/{kurban}', [KurbanController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:kurban.delete');

        // Peserta Kurban
        Route::get('/{kurban}/peserta/create', [KurbanController::class, 'createPeserta'])
            ->name('peserta.create')
            ->middleware('permission:kurban.peserta.create');

        Route::post('/{kurban}/peserta', [KurbanController::class, 'storePeserta'])
            ->name('peserta.store')
            ->middleware('permission:kurban.peserta.create');

        Route::get('/{kurban}/peserta/{peserta}/edit', [KurbanController::class, 'editPeserta'])
            ->name('peserta.edit')
            ->middleware('permission:kurban.peserta.update');

        Route::put('/{kurban}/peserta/{peserta}', [KurbanController::class, 'updatePeserta'])
            ->name('peserta.update')
            ->middleware('permission:kurban.peserta.update');

        Route::delete('/{kurban}/peserta/{peserta}', [KurbanController::class, 'destroyPeserta'])
            ->name('peserta.destroy')
            ->middleware('permission:kurban.peserta.delete');

        // Distribusi Kurban
        Route::get('/{kurban}/distribusi/create', [KurbanController::class, 'createDistribusi'])
            ->name('distribusi.create')
            ->middleware('permission:kurban.distribusi.create');

        Route::post('/{kurban}/distribusi', [KurbanController::class, 'storeDistribusi'])
            ->name('distribusi.store')
            ->middleware('permission:kurban.distribusi.create');

        Route::get('/{kurban}/distribusi/{distribusi}/edit', [KurbanController::class, 'editDistribusi'])
            ->name('distribusi.edit')
            ->middleware('permission:kurban.distribusi.update');

        Route::put('/{kurban}/distribusi/{distribusi}', [KurbanController::class, 'updateDistribusi'])
            ->name('distribusi.update')
            ->middleware('permission:kurban.distribusi.update');

        Route::delete('/{kurban}/distribusi/{distribusi}', [KurbanController::class, 'destroyDistribusi'])
            ->name('distribusi.destroy')
            ->middleware('permission:kurban.distribusi.delete');

        // Export
        Route::get('/export', [KurbanController::class, 'export'])
            ->name('export')
            ->middleware('permission:kurban.export');
    });

    // =========================================================================
    // MODULE ROUTES - NAVIGATION ONLY (No Implementation)
    // =========================================================================

    // Module 1: Jamaah Management
    Route::middleware(['module.access:jamaah'])->prefix('jamaah')->name('jamaah.')->group(function () {
        // Dashboard & List
        Route::get('/', [\App\Http\Controllers\JamaahController::class, 'index'])->name('index');

        // Export
        Route::get('/export', [\App\Http\Controllers\JamaahController::class, 'export'])->name('export');

        // Create Jamaah
        Route::get('/create', [\App\Http\Controllers\JamaahController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\JamaahController::class, 'store'])->name('store');

        // Donasi Management
        Route::prefix('donasi')->name('donasi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\JamaahController::class, 'donations'])->name('index');
            Route::patch('/{donation}/status', [\App\Http\Controllers\JamaahController::class, 'updateDonationStatus'])->name('update-status');
            Route::delete('/{donation}', [\App\Http\Controllers\JamaahController::class, 'destroyDonation'])->name('destroy');
        });

        // Detail & Edit Jamaah (harus setelah route statis)
        Route::get('/{jamaah}', [\App\Http\Controllers\JamaahController::class, 'show'])->name('show');
        Route::get('/{jamaah}/edit', [\App\Http\Controllers\JamaahController::class, 'edit'])->name('edit');
        Route::put('/{jamaah}', [\App\Http\Controllers\JamaahController::class, 'update'])->name('update');
        Route::delete('/{jamaah}', [\App\Http\Controllers\JamaahController::class, 'destroy'])->name('destroy');

        // Role/Kategori
        Route::get('/{jamaah}/role/edit', [\App\Http\Controllers\JamaahController::class, 'editRole'])->name('role.edit');
        Route::post('/{jamaah}/role', [\App\Http\Controllers\JamaahController::class, 'updateRole'])->name('role.update');

        // Donasi per Jamaah
        Route::get('/{jamaah}/donasi/create', [\App\Http\Controllers\JamaahController::class, 'createDonation'])->name('donasi.create');
        Route::post('/{jamaah}/donasi', [\App\Http\Controllers\JamaahController::class, 'storeDonation'])->name('donasi.store');
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
        Route::get('pengeluaran/cetak-semua', [\App\Http\Controllers\PengeluaranController::class, 'cetakSemua'])->name('pengeluaran.cetakAll');

        // Transaksi Pengeluaran
        Route::resource('pengeluaran', \App\Http\Controllers\PengeluaranController::class)
            ->except(['create', 'edit', 'show']);

        // Transaksi Pemasukan
        Route::resource('pemasukan', \App\Http\Controllers\PemasukanController::class)
            ->except(['create', 'edit', 'show']);
    });

    // Laporan Keuangan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\LaporanController::class, 'index'])->name('index');
        Route::get('/export/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [\App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/data-keuangan', [\App\Http\Controllers\LaporanController::class, 'getDataKeuangan'])->name('data-keuangan');
        Route::get('/data-kegiatan', [\App\Http\Controllers\LaporanController::class, 'getDataKegiatanBulanan'])->name('data-kegiatan');
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
        // Dashboard Modul - View only
        Route::get('/dashboard', [\App\Http\Controllers\TakmirController::class, 'dashboard'])
            ->name('dashboard')
            ->middleware('permission:takmir.dashboard.view');

        // Struktur Organisasi - View only
        Route::get('/struktur-organisasi', [\App\Http\Controllers\TakmirController::class, 'strukturOrganisasi'])
            ->name('struktur-organisasi')
            ->middleware('permission:takmir.struktur_organisasi.view');

        // Export - Requires export permission
        Route::get('/export', [\App\Http\Controllers\TakmirController::class, 'export'])
            ->name('export')
            ->middleware('permission:takmir.export');

        // Verifikasi Jamaah - Admin only
        Route::prefix('verifikasi-jamaah')->name('verifikasi-jamaah.')->group(function () {
            Route::get('/', [\App\Http\Controllers\JamaahVerificationController::class, 'index'])
                ->name('index')
                ->middleware('permission:takmir.verifikasi_jamaah.view');

            Route::post('/{user}/verify', [\App\Http\Controllers\JamaahVerificationController::class, 'verify'])
                ->name('verify')
                ->middleware('permission:takmir.verifikasi_jamaah.approve');

            Route::delete('/{user}/unverify', [\App\Http\Controllers\JamaahVerificationController::class, 'unverify'])
                ->name('unverify')
                ->middleware('permission:takmir.verifikasi_jamaah.approve');
        });

        // Aktivitas Harian - Full CRUD with granular permissions
        Route::prefix('aktivitas')->name('aktivitas.')->group(function () {
            Route::get('/export', [\App\Http\Controllers\AktivitasHarianController::class, 'export'])
                ->name('export')
                ->middleware('permission:takmir.export');

            Route::get('/', [\App\Http\Controllers\AktivitasHarianController::class, 'index'])
                ->name('index')
                ->middleware('permission:takmir.aktivitas.view');

            Route::get('/create', [\App\Http\Controllers\AktivitasHarianController::class, 'create'])
                ->name('create')
                ->middleware('permission:takmir.aktivitas.create');

            Route::post('/', [\App\Http\Controllers\AktivitasHarianController::class, 'store'])
                ->name('store')
                ->middleware('permission:takmir.aktivitas.create');

            Route::get('/{aktivita}', [\App\Http\Controllers\AktivitasHarianController::class, 'show'])
                ->name('show')
                ->middleware('permission:takmir.aktivitas.view');

            Route::get('/{aktivita}/edit', [\App\Http\Controllers\AktivitasHarianController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:takmir.aktivitas.update');

            Route::put('/{aktivita}', [\App\Http\Controllers\AktivitasHarianController::class, 'update'])
                ->name('update')
                ->middleware('permission:takmir.aktivitas.update');

            Route::delete('/{aktivita}', [\App\Http\Controllers\AktivitasHarianController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:takmir.aktivitas.delete');
        });

        // Pemilihan - Granular permissions
        Route::prefix('pemilihan')->name('pemilihan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PemilihanController::class, 'index'])
                ->name('index')
                ->middleware('permission:takmir.pemilihan.view');

            Route::get('/{id}/vote', [\App\Http\Controllers\PemilihanController::class, 'vote'])
                ->name('vote')
                ->middleware('permission:takmir.pemilihan.vote');

            Route::post('/{id}/vote', [\App\Http\Controllers\PemilihanController::class, 'submitVote'])
                ->name('submitVote')
                ->middleware('permission:takmir.pemilihan.vote');

            Route::get('/{id}/hasil', [\App\Http\Controllers\PemilihanController::class, 'hasil'])
                ->name('hasil')
                ->middleware('permission:takmir.pemilihan.view');

            // Admin routes - Create, Edit, Delete
            Route::get('/create', [\App\Http\Controllers\PemilihanController::class, 'create'])
                ->name('create')
                ->middleware('permission:takmir.pemilihan.create');

            Route::post('/', [\App\Http\Controllers\PemilihanController::class, 'store'])
                ->name('store')
                ->middleware('permission:takmir.pemilihan.create');

            Route::get('/{id}', [\App\Http\Controllers\PemilihanController::class, 'show'])
                ->name('show')
                ->middleware('permission:takmir.pemilihan.view');

            Route::get('/{id}/edit', [\App\Http\Controllers\PemilihanController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:takmir.pemilihan.update');

            Route::put('/{id}', [\App\Http\Controllers\PemilihanController::class, 'update'])
                ->name('update')
                ->middleware('permission:takmir.pemilihan.update');

            Route::delete('/{id}', [\App\Http\Controllers\PemilihanController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:takmir.pemilihan.delete');

            // Kandidat routes - Admin only
            Route::post('/{id}/kandidat', [\App\Http\Controllers\PemilihanController::class, 'storeKandidat'])
                ->name('kandidat.store')
                ->middleware('permission:takmir.pemilihan.create');

            Route::delete('/{pemilihanId}/kandidat/{kandidatId}', [\App\Http\Controllers\PemilihanController::class, 'destroyKandidat'])
                ->name('kandidat.destroy')
                ->middleware('permission:takmir.pemilihan.delete');
        });

        // Takmir resource - CRUD operations with granular permissions
        Route::get('/', [\App\Http\Controllers\TakmirController::class, 'index'])
            ->name('index')
            ->middleware('permission:takmir.view');

        Route::get('/create', [\App\Http\Controllers\TakmirController::class, 'create'])
            ->name('create')
            ->middleware('permission:takmir.create');

        Route::post('/', [\App\Http\Controllers\TakmirController::class, 'store'])
            ->name('store')
            ->middleware('permission:takmir.create');

        Route::get('/{takmir}/show', [\App\Http\Controllers\TakmirController::class, 'show'])
            ->name('show')
            ->middleware('permission:takmir.view');

        Route::get('/{takmir}/edit', [\App\Http\Controllers\TakmirController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:takmir.update');

        Route::put('/{takmir}', [\App\Http\Controllers\TakmirController::class, 'update'])
            ->name('update')
            ->middleware('permission:takmir.update');

        Route::delete('/{takmir}', [\App\Http\Controllers\TakmirController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:takmir.delete');
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
    Route::get('/info-masjid', [InformasiController::class, 'publicIndex'])
        ->name('public.home');

    Route::get('/info-masjid/{slug}', [InformasiController::class, 'publicShow'])
        ->name('public.info.show');

    // Module 9: Reports & Statistics
    Route::middleware(['module.access:laporan'])->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\LaporanController::class, 'index'])->name('index');
        Route::get('/data-keuangan', [\App\Http\Controllers\LaporanController::class, 'getDataKeuangan'])->name('data-keuangan');
        Route::get(
            '/data-kegiatan-bulanan',
            [\App\Http\Controllers\LaporanController::class, 'getDataKegiatanBulanan']
        )->name('data-kegiatan-bulanan');
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
