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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang input (Relasi ke tabel users)
            $table->foreignId('kategori_id')->constrained('kategori_pengeluaran')->onDelete('cascade'); // Relasi ke tabel kategori
            $table->string('judul_pengeluaran'); // Judul singkat
            $table->text('deskripsi')->nullable(); // Detail pengeluaran
            $table->decimal('jumlah', 15, 2); // Nominal uang (Max 15 digit, 2 desimal)
            $table->date('tanggal'); // Tanggal transaksi
            $table->string('bukti_transaksi')->nullable(); // Path foto struk/nota (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
