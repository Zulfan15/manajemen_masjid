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
        Schema::create('add_min_aset', function (Blueprint $table) {
            $table->bigIncrements('transaksi_id');
            $table->unsignedBigInteger('aset_id');
            $table->enum('jenis_transaksi', ['tambah', 'kurang']);
            $table->date('tanggal_transaksi')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('add_min_aset');
    }
};
