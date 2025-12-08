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
        Schema::create('kondisi_barang', function (Blueprint $table) {
            $table->bigIncrements('kondisi_id');
            $table->unsignedBigInteger('aset_id');
            $table->date('tanggal_pemeriksaan')->nullable();
            $table->enum('kondisi', ['baik', 'perlu_perbaikan', 'rusak_berat'])->default('baik');
            $table->string('petugas_pemeriksa')->nullable(); // kalau mau simpan nama bebas
            $table->unsignedBigInteger('id_petugas')->nullable(); // FK ke users
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('aset_id')
                ->references('aset_id')->on('aset')
                ->onDelete('cascade');

            $table->foreign('id_petugas')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_barang');
    }
};
