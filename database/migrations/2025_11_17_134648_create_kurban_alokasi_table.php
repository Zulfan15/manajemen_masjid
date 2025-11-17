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
        Schema::create('kurban_alokasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peserta_id')
                ->constrained('kurban_peserta')
                ->onDelete('cascade');

            $table->foreignId('hewan_id')
                ->constrained('kurban_hewan')
                ->onDelete('cascade');

            $table->tinyInteger('porsi')->default(1); // 1-7 jika sapi
            $table->string('nama_shohibul')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban_alokasi');
    }
};
