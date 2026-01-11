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
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->string('no_hp');
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['L', 'P']);
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
        Schema::dropIfExists('muzakki');
    }
};
