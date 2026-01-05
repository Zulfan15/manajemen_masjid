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
        Schema::table('pemasukan', function (Blueprint $table) {
            // Cek kolom 'status' sudah ada atau belum
            if (!Schema::hasColumn('pemasukan', 'status')) {
                $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('user_id');
            }
            
            // Kolom verified_at
            if (!Schema::hasColumn('pemasukan', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status');
            }
            
            // Kolom verified_by
            if (!Schema::hasColumn('pemasukan', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            }
            
            // Kolom rejected_at
            if (!Schema::hasColumn('pemasukan', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('verified_by');
            }
            
            // Kolom rejected_by
            if (!Schema::hasColumn('pemasukan', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            }
            
            // Kolom alasan_tolak
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
        Schema::table('pemasukan', function (Blueprint $table) {
            $columns = ['status', 'verified_at', 'verified_by', 'rejected_at', 'rejected_by', 'alasan_tolak'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('pemasukan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};