<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Menambahkan field tambahan pada tabel donations
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('type');
            }
            if (!Schema::hasColumn('donations', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending')->after('donation_date');
            }
            if (!Schema::hasColumn('donations', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('status');
            }
            if (!Schema::hasColumn('donations', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('metode_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $columns = ['keterangan', 'status', 'metode_pembayaran', 'bukti_transfer'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('donations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
