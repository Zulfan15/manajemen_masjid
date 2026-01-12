<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enhance kurbans table
        Schema::table('kurbans', function (Blueprint $table) {
            // Add gender field
            $table->enum('jenis_kelamin', ['jantan', 'betina'])->after('jenis_hewan')->nullable()->comment('Jenis kelamin hewan');
            
            // Add max quota based on animal type
            $table->integer('max_kuota')->after('jenis_kelamin')->default(1)->comment('Max kuota peserta: Sapi=7, Kambing/Domba=1');
            
            // Add total meat weight after slaughter
            $table->decimal('total_berat_daging', 8, 2)->after('total_biaya')->nullable()->comment('Total berat daging hasil sembelihan (kg)');
            
            // Add harga per bagian for price locking
            $table->decimal('harga_per_bagian', 12, 2)->after('total_berat_daging')->default(0)->comment('Harga per bagian/slot (locked price)');
        });

        // Enhance peserta_kurbans table
        Schema::table('peserta_kurbans', function (Blueprint $table) {
            // Add bin/binti field
            $table->string('bin_binti', 100)->after('nama_peserta')->nullable()->comment('Bin/Binti nama ayah/ibu');
        });

        // Enhance distribusi_kurbans table
        Schema::table('distribusi_kurbans', function (Blueprint $table) {
            // Update jenis_distribusi to match specification
            $table->dropColumn('jenis_distribusi');
        });

        Schema::table('distribusi_kurbans', function (Blueprint $table) {
            // Add new distribution types according to spec
            $table->enum('jenis_distribusi', [
                'shohibul_qurban',      // Hak Shohibul Qurban (1/3 bagian)
                'fakir_miskin',         // Fakir Miskin / Warga Sekitar
                'yayasan',              // Yayasan / Pihak Luar
            ])->after('estimasi_harga')->default('shohibul_qurban')->comment('Tipe alokasi distribusi');
            
            // Add allocation percentage
            $table->decimal('persentase_alokasi', 5, 2)->after('jenis_distribusi')->default(33.33)->comment('Persentase alokasi (default 1/3 = 33.33%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kurbans', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'max_kuota', 'total_berat_daging', 'harga_per_bagian']);
        });

        Schema::table('peserta_kurbans', function (Blueprint $table) {
            $table->dropColumn('bin_binti');
        });

        Schema::table('distribusi_kurbans', function (Blueprint $table) {
            $table->dropColumn(['jenis_distribusi', 'persentase_alokasi']);
        });

        Schema::table('distribusi_kurbans', function (Blueprint $table) {
            $table->enum('jenis_distribusi', ['keluarga_peserta', 'fakir_miskin', 'saudara', 'kerabat', 'lainnya'])->default('keluarga_peserta');
        });
    }
};
