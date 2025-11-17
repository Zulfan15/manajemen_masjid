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
        Schema::create('kurban_distribusi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hasil_id')
                ->constrained('kurban_hasil_potong')
                ->onDelete('cascade');

            $table->foreignId('penerima_id')
                ->constrained('kurban_penerima')
                ->onDelete('cascade');

            $table->integer('jumlah_kantong')->default(1);
            $table->enum('status', ['proses', 'selesai'])->default('proses');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban_distribusi');
    }
};
