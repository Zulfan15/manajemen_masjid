<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jamaah_category_jamaah', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jamaah_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('jamaah_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(
                ['jamaah_profile_id', 'jamaah_category_id'],
                'jamaah_category_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_category_jamaah');
    }
};