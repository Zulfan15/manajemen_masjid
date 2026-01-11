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
        // CEK DULU: Jika tabel belum ada, baru buat
        if (!Schema::hasTable('pemasukan')) {
            Schema::create('pemasukan', function (Blueprint $table) {
                $table->id();
                
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade')
                      ->comment('User yang melakukan transaksi');
                
                $table->enum('jenis', [
                    'Donasi', 'Zakat', 'Infak', 'Sedekah', 
                    'Sewa', 'Usaha', 'Lain-lain'
                ])->comment('Jenis pemasukan');
                
                $table->decimal('jumlah', 15, 2)
                      ->comment('Jumlah pemasukan dalam rupiah');
                
                $table->date('tanggal')
                      ->default(now())
                      ->comment('Tanggal transaksi dilakukan');
                
                $table->string('sumber', 255)
                      ->nullable()
                      ->comment('Nama donatur atau sumber dana');
                
                $table->text('keterangan')
                      ->nullable()
                      ->comment('Keterangan atau catatan tambahan');
                
                $table->enum('status', ['pending', 'verified', 'rejected'])
                      ->default('pending')
                      ->comment('Status verifikasi transaksi');
                
                $table->timestamp('verified_at')
                      ->nullable()
                      ->comment('Waktu transaksi diverifikasi');
                
                $table->foreignId('verified_by')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('set null')
                      ->comment('Admin yang memverifikasi');
                
                $table->timestamp('rejected_at')
                      ->nullable()
                      ->comment('Waktu transaksi ditolak');
                
                $table->foreignId('rejected_by')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('set null')
                      ->comment('Admin yang menolak');
                
                $table->text('alasan_tolak')
                      ->nullable()
                      ->comment('Alasan penolakan transaksi');
                
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('status');
                $table->index('tanggal');
                $table->index('jenis');
            });
        }
        
        // TAMBAH kolom yang kurang (jika tabel sudah ada tapi kolom belum ada)
        Schema::table('pemasukan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukan', 'tanggal')) {
                $table->date('tanggal')->default(now())->after('jumlah');
            }
            
            if (!Schema::hasColumn('pemasukan', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('pemasukan', 'verified_by')) {
                $table->foreignId('verified_by')
                      ->nullable()
                      ->after('verified_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('pemasukan', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('verified_by');
            }
            
            if (!Schema::hasColumn('pemasukan', 'rejected_by')) {
                $table->foreignId('rejected_by')
                      ->nullable()
                      ->after('rejected_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('pemasukan', 'alasan_tolak')) {
                $table->text('alasan_tolak')->nullable()->after('rejected_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};