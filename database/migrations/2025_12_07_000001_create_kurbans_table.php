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
        Schema::create('kurbans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kurban')->unique()->comment('Nomor identitas kurban');
            $table->enum('jenis_hewan', ['sapi', 'kambing', 'domba'])->comment('Jenis hewan kurban');
            $table->string('nama_hewan')->nullable()->comment('Nama hewan (opsional)');
            $table->decimal('berat_badan', 8, 2)->comment('Berat badan hewan dalam kg');
            $table->enum('kondisi_kesehatan', ['sehat', 'cacat_ringan', 'cacat_berat'])->default('sehat');
            $table->date('tanggal_persiapan')->comment('Tanggal persiapan kurban');
            $table->date('tanggal_penyembelihan')->nullable()->comment('Tanggal penyembelihan');
            
            $table->decimal('harga_hewan', 12, 2)->comment('Harga belian hewan');
            $table->decimal('biaya_operasional', 12, 2)->default(0)->comment('Biaya penyembelihan, transportasi, dll');
            $table->decimal('total_biaya', 12, 2)->comment('Total harga hewan + operasional');
            
            $table->enum('status', ['disiapkan', 'siap_sembelih', 'disembelih', 'didistribusi', 'selesai'])->default('disiapkan');
            $table->text('catatan')->nullable()->comment('Catatan tambahan');
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('jenis_hewan');
            $table->index('status');
            $table->index('tanggal_persiapan');
            $table->index('tanggal_penyembelihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurbans');
    }
};
