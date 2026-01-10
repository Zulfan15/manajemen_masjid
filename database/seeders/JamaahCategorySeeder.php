<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JamaahCategory;

class JamaahCategorySeeder extends Seeder
{
    public function run(): void
    {
        JamaahCategory::insert([
            ['nama' => 'Umum'],
            ['nama' => 'Pengurus'],
            ['nama' => 'Donatur'],
        ]);
    }
}
