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
        Schema::create('aktivitas_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('takmir_id')->constrained('takmir')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_aktivitas', ['Ibadah', 'Kebersihan', 'Administrasi', 'Pengajaran', 'Pembinaan', 'Keuangan', 'Sosial', 'Lainnya']);
            $table->text('deskripsi');
            $table->decimal('durasi_jam', 5, 2)->nullable()->comment('Durasi dalam jam');
            $table->string('bukti_foto')->nullable();
            $table->timestamps();

            // Index untuk query performance
            $table->index(['takmir_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_harian');
    }
};
