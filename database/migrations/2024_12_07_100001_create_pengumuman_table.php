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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['kajian', 'kegiatan', 'event', 'umum'])->default('umum');
            $table->text('konten');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('prioritas', ['normal', 'tinggi', 'mendesak'])->default('normal');
            $table->integer('views')->default(0);
            
            // Relations
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'tanggal_mulai']);
            $table->index('kategori');
            $table->index('prioritas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
