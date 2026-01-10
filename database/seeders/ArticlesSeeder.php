<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('articles')->insert([
            [
                'id' => 5,
                'title' => 'Keutamaan Shalat Berjamaah di Masjid',
                'slug' => 'keutamaan-shalat-berjamaah-di-masjid-1765814496',
                'content' => 'Shalat berjamaah memiliki keutamaan besar di sisi Allah. Rasulullah ﷺ bersabda, "Shalat berjamaah lebih utama dua puluh tujuh derajat dibandingkan shalat sendirian."
Selain pahala yang berlipat, shalat berjamaah juga mempererat ukhuwah antarjamaah dan menumbuhkan semangat kebersamaan di rumah Allah.
Mari biasakan diri menghadiri shalat berjamaah lima waktu di masjid.',
                'category_id' => 3,
                'author_name' => 'Admin Informasi',
                'thumbnail' => 'articles/49U7xJ6bIE7DChGNz2isZCb3qErDjeGe81tVusHS.jpg',
                'published_at' => '2025-12-15 23:01:36',
                'created_at' => '2025-12-15 23:01:36',
                'updated_at' => '2025-12-15 23:01:36',
            ],
            [
                'id' => 6,
                'title' => 'Mengapa Hati Kita Harus Selalu Dijaga?',
                'slug' => 'mengapa-hati-kita-harus-selalu-dijaga-1765814653',
                'content' => 'Hati adalah pusat kendali perilaku manusia. Jika hati baik, maka seluruh amal akan baik; jika hati rusak, maka rusaklah semuanya.
Menjaga hati berarti menjauhi iri, dengki, dan sombong. Perbanyak dzikir dan istighfar agar hati tetap bersih dari penyakit batin.',
                'category_id' => 4,
                'author_name' => 'Admin Informasi',
                'thumbnail' => 'articles/mXFVJbncDcIpkNVkJR0BerLQq0CcqD4op9IphZon.jpg',
                'published_at' => '2025-12-15 23:04:13',
                'created_at' => '2025-12-15 23:04:13',
                'updated_at' => '2025-12-15 23:04:13',
            ],
        ]);
    }
}
