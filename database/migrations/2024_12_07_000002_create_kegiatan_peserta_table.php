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
        Schema::create('kegiatan_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // null jika bukan jamaah terdaftar
            
            // Data peserta (untuk non-jamaah atau override data jamaah)
            $table->string('nama_peserta');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            
            // Status
            $table->enum('status_pendaftaran', ['terdaftar', 'dikonfirmasi', 'dibatalkan'])->default('terdaftar');
            $table->timestamp('tanggal_daftar')->useCurrent();
            
            // Additional
            $table->text('keterangan')->nullable();
            $table->string('metode_pendaftaran')->default('online'); // online, offline, manual
            
            $table->timestamps();
            
            // Indexes & Unique
            $table->unique(['kegiatan_id', 'user_id']); // satu user hanya bisa daftar sekali per kegiatan
            $table->index('status_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_peserta');
    }
};
