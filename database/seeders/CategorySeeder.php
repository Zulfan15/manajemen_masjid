<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'fiqih',
                'description' => null,
                'created_at' => '2025-12-07 20:50:20',
                'updated_at' => '2025-12-07 20:50:20',
            ],
            [
                'id' => 2,
                'name' => 'buka puasa',
                'description' => null,
                'created_at' => '2025-12-08 13:44:08',
                'updated_at' => '2025-12-08 13:44:08',
            ],
            [
                'id' => 3,
                'name' => 'Ibadah',
                'description' => null,
                'created_at' => '2025-12-15 23:01:36',
                'updated_at' => '2025-12-15 23:01:36',
            ],
            [
                'id' => 4,
                'name' => 'akhlak',
                'description' => null,
                'created_at' => '2025-12-15 23:04:13',
                'updated_at' => '2025-12-15 23:04:13',
            ],
        ]);
    }
}
