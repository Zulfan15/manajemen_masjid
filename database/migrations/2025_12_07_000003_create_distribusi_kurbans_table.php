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
        Schema::create('distribusi_kurbans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurban_id')->constrained('kurbans')->onDelete('cascade');
            $table->foreignId('peserta_kurban_id')->nullable()->constrained('peserta_kurbans')->onDelete('set null');
            
            $table->string('penerima_nama')->comment('Nama penerima bagian kurban');
            $table->string('penerima_nomor_telepon', 20)->nullable();
            $table->text('penerima_alamat')->nullable();
            
            $table->decimal('berat_daging', 8, 2)->comment('Berat daging yang diterima (kg)');
            $table->decimal('estimasi_harga', 12, 2)->comment('Estimasi harga per kg x berat');
            
            $table->enum('jenis_distribusi', ['keluarga_peserta', 'fakir_miskin', 'saudara', 'kerabat', 'lainnya'])->comment('Jenis penerima distribusi');
            $table->date('tanggal_distribusi')->nullable()->comment('Tanggal pemberian daging');
            $table->enum('status_distribusi', ['belum_didistribusi', 'sedang_disiapkan', 'sudah_didistribusi'])->default('belum_didistribusi');
            
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('kurban_id');
            $table->index('peserta_kurban_id');
            $table->index('jenis_distribusi');
            $table->index('status_distribusi');
            $table->index('tanggal_distribusi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_kurbans');
    }
};
