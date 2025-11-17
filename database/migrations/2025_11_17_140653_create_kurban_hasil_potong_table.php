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
        Schema::create('kurban_hasil_potong', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penyembelihan_id')
                ->constrained('kurban_penyembelihan')
                ->onDelete('cascade');

            $table->float('daging')->default(0);
            $table->float('tulang')->default(0);
            $table->float('jeroan')->default(0);
            $table->float('kulit')->default(0);

            $table->integer('total_kantong')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban_hasil_potong');
    }
};
