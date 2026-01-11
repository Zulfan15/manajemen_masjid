<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan field data pribadi jamaah yang lengkap
     */
    public function up(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            // Data Pribadi Tambahan
            if (!Schema::hasColumn('jamaah_profiles', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'pendidikan_terakhir')) {
                $table->string('pendidikan_terakhir')->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'status_pernikahan')) {
                $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'duda', 'janda'])->nullable()->after('pendidikan_terakhir');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'rt')) {
                $table->string('rt', 5)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'rw')) {
                $table->string('rw', 5)->nullable()->after('rt');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'kelurahan')) {
                $table->string('kelurahan')->nullable()->after('rw');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('kelurahan');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'status_aktif')) {
                $table->boolean('status_aktif')->default(true)->after('kecamatan');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'foto')) {
                $table->string('foto')->nullable()->after('status_aktif');
            }
            if (!Schema::hasColumn('jamaah_profiles', 'catatan')) {
                $table->text('catatan')->nullable()->after('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $columns = [
                'tanggal_lahir',
                'tempat_lahir',
                'jenis_kelamin',
                'pekerjaan',
                'pendidikan_terakhir',
                'status_pernikahan',
                'rt',
                'rw',
                'kelurahan',
                'kecamatan',
                'status_aktif',
                'foto',
                'catatan'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('jamaah_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
