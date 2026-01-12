<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JamaahProfile;
use App\Models\JamaahCategory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class RealJamaahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Pakai locale Indonesia

        // 1. Pastikan Kategori Ada
        $categories = [
            'Umum' => 'Jamaah umum warga sekitar masjid',
            'Pengurus' => 'Anggota takmir atau panitia masjid',
            'Donatur' => 'Donatur tetap kegiatan masjid',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $desc) {
            $categoryModels[$name] = JamaahCategory::firstOrCreate(
                ['nama' => $name],
                ['deskripsi' => $desc]
            );
        }

        // 2. Buat 3 Jamaah untuk setiap kategori
        foreach ($categoryModels as $catName => $catModel) {
            $this->command->info("Creating 3 jamaah for category: $catName");

            for ($i = 0; $i < 3; $i++) {
                $gender = $faker->randomElement(['L', 'P']);
                $firstName = $gender == 'L' ? $faker->firstNameMale : $faker->firstNameFemale;
                $lastName = $faker->lastName;
                $fullName = "$firstName $lastName";

                // Create User
                $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');
                $username = strtolower($firstName . $lastName . rand(1, 999));

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $fullName,
                        'username' => $username, // Added username
                        'password' => Hash::make('password'),
                        'is_verified' => true,
                    ]
                );

                // Assign role jamaah if not exists
                if (!$user->hasRole('jamaah')) {
                    $user->assignRole('jamaah');
                }

                // Create Profile
                $profile = JamaahProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_lengkap' => $fullName,
                        'tanggal_lahir' => $faker->dateTimeBetween('-60 years', '-20 years'),
                        'tempat_lahir' => $faker->city,
                        'jenis_kelamin' => $gender,
                        'pekerjaan' => $faker->jobTitle,
                        'pendidikan_terakhir' => $faker->randomElement(['SMA', 'S1', 'S2', 'D3']),
                        'status_pernikahan' => $faker->randomElement(['menikah', 'belum_menikah']),
                        'no_hp' => $faker->phoneNumber,
                        'alamat' => $faker->address,
                        'rt' => sprintf('%03d', rand(1, 15)),
                        'rw' => sprintf('%03d', rand(1, 10)),
                        'kelurahan' => 'Sukarame',
                        'kecamatan' => 'Sukarame',
                        'status_aktif' => true,
                    ]
                );

                // Attach Category
                if (!$profile->categories()->where('jamaah_category_id', $catModel->id)->exists()) {
                    $profile->categories()->attach($catModel->id);
                }
            }
        }
    }
}
