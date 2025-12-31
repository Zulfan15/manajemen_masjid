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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilihan_id')->constrained('pemilihan')->onDelete('cascade');
            $table->foreignId('kandidat_id')->constrained('kandidat')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('voted_at');
            $table->timestamps();

            // Constraint: 1 user hanya bisa vote 1x per pemilihan
            $table->unique(['pemilihan_id', 'user_id']);
            $table->index(['pemilihan_id', 'kandidat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
