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
        Schema::create('kegiatan_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // penerima notifikasi
            
            // Notifikasi
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'reminder', 'pengumuman', 'konfirmasi', 'pembatalan'])->default('info');
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Delivery
            $table->enum('channel', ['in_app', 'email', 'whatsapp'])->default('in_app');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            
            // Additional
            $table->json('metadata')->nullable(); // data tambahan seperti link, action, dll
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_notifikasi');
    }
};
