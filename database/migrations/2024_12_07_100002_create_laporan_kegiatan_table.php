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
        Schema::create('laporan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->onDelete('cascade');
            
            // Data Kegiatan
            $table->string('nama_kegiatan');
            $table->enum('jenis_kegiatan', ['kajian', 'sosial', 'pendidikan', 'perayaan', 'lainnya']);
            $table->date('tanggal_pelaksanaan');
            $table->time('waktu_pelaksanaan');
            $table->string('lokasi');
            
            // Data Peserta
            $table->integer('jumlah_peserta')->default(0);
            $table->integer('jumlah_hadir')->default(0);
            $table->integer('jumlah_tidak_hadir')->default(0);
            
            // Penanggung Jawab
            $table->string('penanggung_jawab')->nullable();
            
            // Konten Laporan
            $table->text('deskripsi');
            $table->text('hasil_capaian')->nullable();
            $table->text('catatan_kendala')->nullable();
            
            // Dokumentasi
            $table->json('foto_dokumentasi')->nullable(); // Array of file paths
            
            // Status & Publikasi
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('is_public')->default(false);
            
            // Relations
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['kegiatan_id', 'tanggal_pelaksanaan']);
            $table->index('jenis_kegiatan');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatan');
    }
};
