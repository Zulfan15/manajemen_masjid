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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->enum('jenis_kegiatan', ['rutin', 'insidental', 'event_khusus'])->default('rutin');
            $table->enum('kategori', ['kajian', 'sosial', 'ibadah', 'pendidikan', 'ramadan', 'maulid', 'qurban', 'lainnya'])->default('kajian');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi')->default('Masjid');
            
            // Penanggung Jawab
            $table->string('pic')->nullable(); // Nama PIC
            $table->string('kontak_pic')->nullable(); // No HP PIC
            
            // Peserta
            $table->integer('kuota_peserta')->nullable(); // null = unlimited
            $table->integer('jumlah_peserta')->default(0); // counter peserta terdaftar
            
            // Status
            $table->enum('status', ['direncanakan', 'berlangsung', 'selesai', 'dibatalkan'])->default('direncanakan');
            
            // Budget (opsional)
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('realisasi_biaya', 15, 2)->nullable();
            
            // Jadwal Rutin
            $table->boolean('is_recurring')->default(false); // apakah kegiatan berulang
            $table->enum('recurring_type', ['harian', 'mingguan', 'bulanan', 'tahunan'])->nullable();
            $table->string('recurring_day')->nullable(); // contoh: 'Jumat', '1,15' (tanggal), dll
            
            // Media
            $table->string('gambar')->nullable(); // poster/banner kegiatan
            
            // Additional
            $table->text('catatan')->nullable();
            $table->boolean('butuh_pendaftaran')->default(true); // apakah perlu daftar atau langsung datang
            $table->boolean('sertifikat_tersedia')->default(false); // apakah ada sertifikat
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('jenis_kegiatan');
            $table->index('kategori');
            $table->index('status');
            $table->index('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
