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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('jenis_transaksi', ['zakat fitrah', 'zakat mal', 'infaq', 'sedekah']);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_transaksi');
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
        Schema::dropIfExists('transaksi');
    }
};
