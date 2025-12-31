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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Data Sertifikat
            $table->string('nomor_sertifikat')->unique();
            $table->string('nama_peserta');
            $table->string('nama_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->string('tempat_kegiatan')->nullable();
            
            // Template & Design
            $table->enum('template', ['kajian', 'workshop', 'pelatihan', 'default'])->default('default');
            $table->string('ttd_pejabat')->nullable(); // Nama pejabat yang menandatangani
            $table->string('jabatan_pejabat')->nullable();
            
            // File Management
            $table->string('file_path')->nullable(); // Path to generated PDF
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable(); // Additional data like QR code, validation URL, etc.
            
            // Relations
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['kegiatan_id', 'user_id']);
            $table->index('nomor_sertifikat');
            $table->index('template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
