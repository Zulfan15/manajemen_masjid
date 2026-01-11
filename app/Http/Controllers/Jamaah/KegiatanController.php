<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of kegiatan for Jamaah (read-only)
     */
    public function index()
    {
        $kegiatans = Kegiatan::with(['creator', 'peserta' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('modules.jamaah.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Display the specified kegiatan detail (read-only)
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::with('creator')->findOrFail($id);
        
        return view('modules.jamaah.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Register current user to a kegiatan
     */
    public function register(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Check if kegiatan status allows registration
        if (in_array($kegiatan->status, ['dibatalkan', 'selesai'])) {
            return back()->with('error', 'Pendaftaran tidak tersedia. Kegiatan sudah ' . $kegiatan->status . '.');
        }

        // Check if kegiatan is full
        if ($kegiatan->isFull()) {
            return back()->with('error', 'Kuota peserta sudah penuh!');
        }

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $validated['kegiatan_id'] = $id;
            $validated['user_id'] = auth()->id();
            $validated['metode_pendaftaran'] = 'online';
            $validated['status_pendaftaran'] = 'terdaftar';
            $validated['tanggal_daftar'] = now();

            // Check if user already registered
            $exists = \App\Models\KegiatanPeserta::where('kegiatan_id', $id)
                ->where('user_id', auth()->id())
                ->exists();

            if ($exists) {
                return back()->with('error', 'Anda sudah terdaftar di kegiatan ini!');
            }

            \App\Models\KegiatanPeserta::create($validated);
            $kegiatan->incrementPeserta();

            return back()->with('success', 'Pendaftaran berhasil! Terima kasih telah mendaftar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
