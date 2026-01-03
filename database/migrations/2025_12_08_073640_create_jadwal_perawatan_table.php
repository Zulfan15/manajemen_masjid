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
        Schema::create('jadwal_perawatan', function (Blueprint $table) {
            $table->bigIncrements('jadwal_id');
            $table->unsignedBigInteger('aset_id');
            $table->date('tanggal_jadwal');
            $table->string('jenis_perawatan')->nullable();
            $table->enum('status', ['dijadwalkan', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('jadwal_perawatan');
    }
};
