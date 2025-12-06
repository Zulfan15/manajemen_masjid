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
        Schema::create('takmir', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jabatan', ['Ketua (DKM)', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Pengurus']);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('alamat')->nullable();
            $table->date('periode_mulai');
            $table->date('periode_akhir');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('takmir');
    }
};
