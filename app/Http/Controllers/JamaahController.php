<?php

namespace App\Http\Controllers;

use App\Models\{JamaahProfile, JamaahCategory};
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JamaahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'module.access:jamaah']);
    }

    /**
     * ===============================
     * LIST JAMAAH
     * ===============================
     */


    public function index()
    {
        $jamaahs = JamaahProfile::with([
            'categories',
            'user',
            'donations',
            'participations'
        ])->get();

        // 👥 Total Jamaah
        $totalJamaah = $jamaahs->count();

        // 🆕 Jamaah Baru Bulan Ini
        $jamaahBaruBulanIni = $jamaahs->filter(function ($j) {
            return $j->created_at
                && $j->created_at->isSameMonth(now());
        })->count();

        // 🤲 Total Relawan / Pengurus Aktif
        $totalRelawan = $jamaahs->filter(function ($j) {
            return $j->categories->contains('nama', 'Pengurus');
        })->count();

        // 📊 Tingkat Partisipasi Jamaah
        $jamaahAktif = $jamaahs->filter(function ($j) {
            return $j->donations->isNotEmpty()
                || $j->participations->isNotEmpty();
        })->count();

        $tingkatPartisipasi = $totalJamaah > 0
            ? round(($jamaahAktif / $totalJamaah) * 100, 1)
            : 0;

        return view(
            'modules.jamaah.index',
            compact(
                'jamaahs',
                'totalJamaah',
                'jamaahBaruBulanIni',
                'totalRelawan',
                'tingkatPartisipasi'
            )
        );
    }


    /**
     * ===============================
     * DETAIL JAMAAH
     * ===============================
     */
    public function show(JamaahProfile $jamaah)
    {
        return view('modules.jamaah.show', compact('jamaah'));
    }

    /**
     * ===============================
     * PROFIL JAMAAH SENDIRI
     * ===============================
     */
    public function myProfile()
    {
        $jamaah = JamaahProfile::where('user_id', auth()->id())
            ->with([
                'categories',
                'donations',
                'participations.activity'
            ])
            ->firstOrFail();

        return view('modules.jamaah.my-profile', compact('jamaah'));
    }

    /**
     * ===============================
     * FORM UBAH KATEGORI / ROLE JAMAAH
     * ===============================
     * (Admin Jamaah only)
     */
    public function editRole(JamaahProfile $jamaah)
    {
        $categories = JamaahCategory::all();

        return view('modules.jamaah.edit-role', compact('jamaah', 'categories'));
    }

    /**
     * ===============================
     * SIMPAN PERUBAHAN ROLE / KATEGORI
     * ===============================
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'main_role' => 'required|in:umum,pengurus',
            'donatur' => 'nullable|boolean',
        ]);

        $jamaah = JamaahProfile::findOrFail($id);

        // Mapping role → nama kategori
        $mainCategoryName = $request->main_role === 'umum'
            ? 'Umum'
            : 'Pengurus';

        $mainCategory = JamaahCategory::where('nama', $mainCategoryName)->firstOrFail();
        $donaturCategory = JamaahCategory::where('nama', 'Donatur')->firstOrFail();

        // Ambil ID kategori utama
        $umumId = JamaahCategory::where('nama', 'Umum')->value('id');
        $pengurusId = JamaahCategory::where('nama', 'Pengurus')->value('id');

        // 1️⃣ Hapus kategori utama lama
        $jamaah->categories()->detach([$umumId, $pengurusId]);

        // 2️⃣ Pasang kategori utama baru
        $jamaah->categories()->attach($mainCategory->id);

        // 3️⃣ Donatur opsional
        if ($request->boolean('donatur')) {
            $jamaah->categories()->syncWithoutDetaching([$donaturCategory->id]);
        } else {
            $jamaah->categories()->detach($donaturCategory->id);
        }

        return redirect()
            ->route('jamaah.index')
            ->with('success', 'Kategori jamaah berhasil diperbarui');
    }
}
