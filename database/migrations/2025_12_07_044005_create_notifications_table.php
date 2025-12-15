<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke pengumuman/berita/artikel (nullable)
            $table->foreignId('announcement_id')->nullable()->constrained('announcements')->onDelete('cascade');
            $table->foreignId('news_id')->nullable()->constrained('news')->onDelete('cascade');
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade');

            $table->enum('type', ['email', 'whatsapp']);
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('notifications');
    }
};
