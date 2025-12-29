<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of pengumuman for Jamaah (read-only)
     */
    public function index()
    {
        $pengumumen = Pengumuman::with(['kegiatan', 'creator'])
            ->aktif()
            ->orderBy('prioritas', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modules.jamaah.pengumuman.index', compact('pengumumen'));
    }

    /**
     * Display the specified pengumuman detail (read-only)
     */
    public function show($id)
    {
        $pengumuman = Pengumuman::with(['kegiatan', 'creator'])->findOrFail($id);
        
        // Increment views
        $pengumuman->incrementViews();
        
        return view('modules.jamaah.pengumuman.show', compact('pengumuman'));
    }
}
