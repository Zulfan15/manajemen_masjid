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
        Schema::create('kandidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilihan_id')->constrained('pemilihan')->onDelete('cascade');
            $table->foreignId('takmir_id')->constrained('takmir')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            
            $table->unique(['pemilihan_id', 'nomor_urut']);
            $table->index('pemilihan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandidat');
    }
};
