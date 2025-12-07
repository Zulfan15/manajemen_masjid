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
        Schema::create('peserta_kurbans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurban_id')->constrained('kurbans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('nama_peserta')->comment('Nama peserta kurban');
            $table->string('nomor_identitas', 20)->nullable()->comment('Nomor KTP/Identitas');
            $table->string('nomor_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            
            $table->enum('tipe_peserta', ['perorangan', 'keluarga'])->default('perorangan');
            $table->integer('jumlah_jiwa')->default(1)->comment('Jumlah jiwa dalam kurban jika keluarga');
            $table->decimal('jumlah_bagian', 5, 2)->default(1)->comment('Jumlah bagian (boleh pecahan)');
            
            $table->decimal('nominal_pembayaran', 12, 2)->comment('Nominal yang dibayarkan peserta');
            $table->enum('status_pembayaran', ['belum_lunas', 'lunas', 'cicilan'])->default('belum_lunas');
            $table->date('tanggal_pembayaran')->nullable();
            
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('kurban_id');
            $table->index('user_id');
            $table->index('status_pembayaran');
            $table->unique(['kurban_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_kurbans');
    }
};
