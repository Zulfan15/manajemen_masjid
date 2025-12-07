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
        Schema::create('kegiatan_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('kegiatan_peserta')->onDelete('cascade');
            
            // Kehadiran
            $table->enum('status_kehadiran', ['hadir', 'tidak_hadir', 'izin', 'sakit'])->default('hadir');
            $table->timestamp('waktu_absen')->useCurrent();
            $table->string('metode_absen')->default('manual'); // manual, qr_code, auto
            
            // Location tracking (opsional)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            // Additional
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->onDelete('set null'); // admin yang mencatat
            
            $table->timestamps();
            
            // Indexes & Unique
            $table->unique(['kegiatan_id', 'peserta_id']); // satu peserta hanya bisa absen sekali per kegiatan
            $table->index('status_kehadiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_absensi');
    }
};
