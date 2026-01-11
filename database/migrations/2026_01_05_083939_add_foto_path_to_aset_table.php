<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            if (!Schema::hasColumn('aset', 'foto_path')) {
                $table->string('foto_path')->nullable()->after('qr_payload');
            }
        });
    }

    public function down(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            if (Schema::hasColumn('aset', 'foto_path')) {
                $table->dropColumn('foto_path');
            }
        });
    }
};
