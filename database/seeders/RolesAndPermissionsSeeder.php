<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========================================
        // GRANULAR PERMISSIONS STRUCTURE
        // Based on merged features from 8 branches
        // ========================================

        $permissions = [
            // JAMAAH MODULE
            'jamaah.view', 'jamaah.create', 'jamaah.update', 'jamaah.delete',
            'jamaah.export', 'jamaah.import',

            // KEUANGAN MODULE
            'keuangan.view', 'keuangan.create', 'keuangan.update', 'keuangan.delete',
            'keuangan.pengeluaran.view', 'keuangan.pengeluaran.create', 
            'keuangan.pengeluaran.update', 'keuangan.pengeluaran.delete',
            'keuangan.kategori.view', 'keuangan.kategori.create',
            'keuangan.kategori.update', 'keuangan.kategori.delete',
            'keuangan.export',

            // KEGIATAN MODULE
            'kegiatan.view', 'kegiatan.create', 'kegiatan.update', 'kegiatan.delete',
            'kegiatan.peserta.view', 'kegiatan.peserta.create', 'kegiatan.peserta.delete',
            'kegiatan.absensi.view', 'kegiatan.absensi.create', 'kegiatan.absensi.update',
            'kegiatan.laporan.view', 'kegiatan.laporan.create',
            'kegiatan.sertifikat.view', 'kegiatan.sertifikat.create', 'kegiatan.sertifikat.download',
            'kegiatan.export',

            // ZIS MODULE
            'zis.view', 'zis.create', 'zis.update', 'zis.delete',
            'zis.donatur.view', 'zis.donatur.create', 'zis.donatur.update',
            'zis.penyaluran.view', 'zis.penyaluran.create',
            'zis.export',

            // KURBAN MODULE
            'kurban.view', 'kurban.create', 'kurban.update', 'kurban.delete',
            'kurban.peserta.view', 'kurban.peserta.create', 
            'kurban.peserta.update', 'kurban.peserta.delete',
            'kurban.distribusi.view', 'kurban.distribusi.create',
            'kurban.distribusi.update', 'kurban.distribusi.delete',
            'kurban.export',

            // INVENTARIS MODULE
            'inventaris.view', 'inventaris.create', 'inventaris.update', 'inventaris.delete',
            'inventaris.aset.view', 'inventaris.aset.create',
            'inventaris.aset.update', 'inventaris.aset.delete',
            'inventaris.kondisi.view', 'inventaris.kondisi.create',
            'inventaris.jadwal_perawatan.view', 'inventaris.jadwal_perawatan.create',
            'inventaris.jadwal_perawatan.update', 'inventaris.jadwal_perawatan.delete',
            'inventaris.transaksi.view', 'inventaris.transaksi.create',
            'inventaris.export',

            // TAKMIR MODULE
            'takmir.view', 'takmir.create', 'takmir.update', 'takmir.delete',
            'takmir.dashboard.view',
            'takmir.struktur_organisasi.view',
            'takmir.verifikasi_jamaah.view', 'takmir.verifikasi_jamaah.approve',
            'takmir.aktivitas.view', 'takmir.aktivitas.create',
            'takmir.aktivitas.update', 'takmir.aktivitas.delete',
            'takmir.pemilihan.view', 'takmir.pemilihan.create',
            'takmir.pemilihan.update', 'takmir.pemilihan.delete',
            'takmir.pemilihan.vote',
            'takmir.export',

            // INFORMASI MODULE (3 sub-modules)
            'informasi.view',
            'informasi.berita.view', 'informasi.berita.create',
            'informasi.berita.update', 'informasi.berita.delete',
            'informasi.artikel.view', 'informasi.artikel.create',
            'informasi.artikel.update', 'informasi.artikel.delete',
            'informasi.pengumuman.view', 'informasi.pengumuman.create',
            'informasi.pengumuman.update', 'informasi.pengumuman.delete',

            // LAPORAN MODULE
            'laporan.view',
            'laporan.keuangan.view',
            'laporan.kegiatan.view',
            'laporan.jamaah.view',
            'laporan.inventaris.view',
            'laporan.export',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // ========================================
        // CREATE ROLES
        // ========================================

        // 1. SUPER ADMIN (READ-ONLY ALL MODULES)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $viewPermissions = array_filter($permissions, function($p) {
            return strpos($p, '.view') !== false;
        });
        $superAdmin->syncPermissions($viewPermissions);

        // 2. ADMIN JAMAAH
        $adminJamaah = Role::firstOrCreate(['name' => 'admin_jamaah', 'guard_name' => 'web']);
        $jamaahPerms = array_filter($permissions, function($p) {
            return strpos($p, 'jamaah.') === 0;
        });
        $adminJamaah->syncPermissions($jamaahPerms);

        // 3. ADMIN KEUANGAN
        $adminKeuangan = Role::firstOrCreate(['name' => 'admin_keuangan', 'guard_name' => 'web']);
        $keuanganPerms = array_filter($permissions, function($p) {
            return strpos($p, 'keuangan.') === 0;
        });
        $adminKeuangan->syncPermissions($keuanganPerms);

        // 4. ADMIN KEGIATAN
        $adminKegiatan = Role::firstOrCreate(['name' => 'admin_kegiatan', 'guard_name' => 'web']);
        $kegiatanPerms = array_filter($permissions, function($p) {
            return strpos($p, 'kegiatan.') === 0;
        });
        $adminKegiatan->syncPermissions($kegiatanPerms);

        // 5. ADMIN ZIS
        $adminZis = Role::firstOrCreate(['name' => 'admin_zis', 'guard_name' => 'web']);
        $zisPerms = array_filter($permissions, function($p) {
            return strpos($p, 'zis.') === 0;
        });
        $adminZis->syncPermissions($zisPerms);

        // 6. ADMIN KURBAN
        $adminKurban = Role::firstOrCreate(['name' => 'admin_kurban', 'guard_name' => 'web']);
        $kurbanPerms = array_filter($permissions, function($p) {
            return strpos($p, 'kurban.') === 0;
        });
        $adminKurban->syncPermissions($kurbanPerms);

        // 7. ADMIN INVENTARIS
        $adminInventaris = Role::firstOrCreate(['name' => 'admin_inventaris', 'guard_name' => 'web']);
        $inventarisPerms = array_filter($permissions, function($p) {
            return strpos($p, 'inventaris.') === 0;
        });
        $adminInventaris->syncPermissions($inventarisPerms);

        // 8. ADMIN TAKMIR
        $adminTakmir = Role::firstOrCreate(['name' => 'admin_takmir', 'guard_name' => 'web']);
        $takmirPerms = array_filter($permissions, function($p) {
            return strpos($p, 'takmir.') === 0;
        });
        $adminTakmir->syncPermissions($takmirPerms);

        // 9. ADMIN INFORMASI
        $adminInformasi = Role::firstOrCreate(['name' => 'admin_informasi', 'guard_name' => 'web']);
        $informasiPerms = array_filter($permissions, function($p) {
            return strpos($p, 'informasi.') === 0;
        });
        $adminInformasi->syncPermissions($informasiPerms);

        // 10. ADMIN LAPORAN
        $adminLaporan = Role::firstOrCreate(['name' => 'admin_laporan', 'guard_name' => 'web']);
        $laporanPerms = array_filter($permissions, function($p) {
            return strpos($p, 'laporan.') === 0;
        });
        $adminLaporan->syncPermissions($laporanPerms);

        // ========================================
        // PENGURUS ROLES (Officers)
        // ========================================
        
        // Pengurus roles have same permissions as their admin counterparts
        $modules = ['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan'];
        
        foreach ($modules as $module) {
            $pengurusRole = Role::firstOrCreate(['name' => "pengurus_{$module}", 'guard_name' => 'web']);
            $modulePerms = array_filter($permissions, function($p) use ($module) {
                return strpos($p, "{$module}.") === 0;
            });
            $pengurusRole->syncPermissions($modulePerms);
        }

        // 11. PENGURUS TAKMIR (Special case - can only manage aktivitas, cannot manage takmir data)
        $pengurusTakmir = Role::firstOrCreate(['name' => 'pengurus_takmir', 'guard_name' => 'web']);
        $pengurusTakmir->syncPermissions([
            'takmir.view',
            'takmir.dashboard.view',
            'takmir.struktur_organisasi.view',
            'takmir.aktivitas.view',
            'takmir.aktivitas.create',
            'takmir.aktivitas.update',
            'takmir.aktivitas.delete',
            'takmir.pemilihan.view',
            'takmir.pemilihan.vote',
        ]);

        // 12. JAMAAH ROLE (Limited Access)
        $jamaahRole = Role::firstOrCreate(['name' => 'jamaah', 'guard_name' => 'web']);
        $jamaahRole->syncPermissions(['jamaah.view']);

        echo "✅ Granular Roles and Permissions created successfully!\n";
        echo "📊 Total Roles: " . Role::count() . "\n";
        echo "🔐 Total Permissions: " . Permission::count() . "\n";
        echo "🎯 Permission breakdown:\n";
        echo "   - Jamaah: 6 permissions\n";
        echo "   - Keuangan: 13 permissions\n";
        echo "   - Kegiatan: 17 permissions\n";
        echo "   - ZIS: 10 permissions\n";
        echo "   - Kurban: 13 permissions\n";
        echo "   - Inventaris: 18 permissions\n";
        echo "   - Takmir: 18 permissions\n";
        echo "   - Informasi: 13 permissions\n";
        echo "   - Laporan: 6 permissions\n";
    }
}
