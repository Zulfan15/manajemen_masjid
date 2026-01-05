<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penyaluran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penyaluran')->unique();
            $table->foreignId('mustahiq_id')->constrained('mustahiq')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('jenis_bantuan');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_penyaluran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyaluran');
    }
};
