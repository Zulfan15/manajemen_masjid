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
        Schema::create('aset', function (Blueprint $table) {
            $table->bigIncrements('aset_id');
            $table->string('nama_aset');
            $table->string('kategori')->nullable(); // Elektronik, Furnitur, dll
            $table->string('lokasi')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->enum('status', ['aktif', 'rusak', 'hilang', 'dibuang'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->string('qr_payload')->nullable(); // payload untuk QR code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
