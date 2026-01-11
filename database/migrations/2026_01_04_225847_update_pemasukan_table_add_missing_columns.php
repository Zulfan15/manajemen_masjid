<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            // 1. Rename jenis_pemasukan jadi jenis (jika ada)
            if (Schema::hasColumn('pemasukan', 'jenis_pemasukan')) {
                $table->renameColumn('jenis_pemasukan', 'jenis');
            }
            
            // 2. Tambah kolom tanggal (jika belum ada)
            if (!Schema::hasColumn('pemasukan', 'tanggal')) {
                $table->date('tanggal')->default(now())->after('jumlah');
            }
            
            // 3. Rename approved_at jadi verified_at (jika ada)
            if (Schema::hasColumn('pemasukan', 'approved_at')) {
                $table->renameColumn('approved_at', 'verified_at');
            } elseif (!Schema::hasColumn('pemasukan', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status');
            }
            
            // 4. Rename approved_by jadi verified_by (jika ada)
            if (Schema::hasColumn('pemasukan', 'approved_by')) {
                $table->renameColumn('approved_by', 'verified_by');
            } elseif (!Schema::hasColumn('pemasukan', 'verified_by')) {
                $table->foreignId('verified_by')
                      ->nullable()
                      ->after('verified_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            // 5. Tambah kolom rejected_at (jika belum ada)
            if (!Schema::hasColumn('pemasukan', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('verified_by');
            }
            
            // 6. Tambah kolom rejected_by (jika belum ada)
            if (!Schema::hasColumn('pemasukan', 'rejected_by')) {
                $table->foreignId('rejected_by')
                      ->nullable()
                      ->after('rejected_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            // 7. Tambah kolom alasan_tolak (jika belum ada)
            if (!Schema::hasColumn('pemasukan', 'alasan_tolak')) {
                $table->text('alasan_tolak')->nullable()->after('rejected_by');
            }
        });
        
        // 8. Update enum status jika masih pakai 'approved'
        DB::statement("ALTER TABLE pemasukan MODIFY COLUMN status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'");
        
        // 9. Update data lama: approved -> verified
        DB::table('pemasukan')
          ->where('status', 'approved')
          ->update(['status' => 'verified']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            // Reverse changes
            if (Schema::hasColumn('pemasukan', 'alasan_tolak')) {
                $table->dropColumn('alasan_tolak');
            }
            if (Schema::hasColumn('pemasukan', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            if (Schema::hasColumn('pemasukan', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};