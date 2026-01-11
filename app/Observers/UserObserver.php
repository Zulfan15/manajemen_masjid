<?php

namespace App\Observers;

use App\Models\User;
use App\Models\JamaahProfile;
use App\Models\JamaahCategory;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // CEGAH DUPLIKASI
        if ($user->jamaahProfile) {
            return;
        }

        // BUAT PROFILE
        $profile = JamaahProfile::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'status_aktif'  => true,
        ]);

        // AMBIL KATEGORI DEFAULT
        $umum = JamaahCategory::where('nama', 'Umum')->first();

        if ($umum) {
            $profile->categories()->attach($umum->id);
        }
    }
}
