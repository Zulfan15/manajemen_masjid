<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah soft delete ke tabel pemasukan
        Schema::table('pemasukan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukan', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tambah soft delete ke tabel pengeluaran (jika ada)
        if (Schema::hasTable('pengeluaran')) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                if (!Schema::hasColumn('pengeluaran', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down()
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        if (Schema::hasTable('pengeluaran')) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};