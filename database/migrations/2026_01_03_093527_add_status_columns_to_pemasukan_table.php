<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukan', 'status')) {
                $table->enum('status', ['pending', 'verified', 'rejected'])
                    ->default('pending')
                    ->after('keterangan');
            }
            if (!Schema::hasColumn('pemasukan', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pemasukan', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            if (Schema::hasColumn('pemasukan', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('pemasukan', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('pemasukan', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
