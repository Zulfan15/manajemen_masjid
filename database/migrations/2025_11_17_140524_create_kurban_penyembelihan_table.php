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
        Schema::create('kurban_penyembelihan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hewan_id')
                ->constrained('kurban_hewan')
                ->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('petugas')->nullable();
            $table->enum('status', ['menunggu', 'disembelih'])->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban_penyembelihan');
    }
};
