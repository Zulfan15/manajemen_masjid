<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            // Kategori: Berita, Pengumuman, Artikel, Dakwah
            $table->enum('category', ['berita', 'pengumuman', 'artikel', 'dakwah']); 
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            // Menyimpan siapa yang memposting (relasi ke tabel users)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};