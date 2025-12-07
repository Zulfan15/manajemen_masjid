<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Kegiatan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of pengumuman
     */
    public function index(Request $request)
    {
        $query = Pengumuman::with(['creator', 'kegiatan'])
            ->orderBy('prioritas', 'desc')
            ->orderBy('tanggal_mulai', 'desc');

        // Apply filters
        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $pengumumans = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Pengumuman::count(),
            'aktif' => Pengumuman::aktif()->count(),
            'total_views' => Pengumuman::sum('views'),
            'bulan_ini' => Pengumuman::whereMonth('created_at', now()->month)->count(),
        ];

        return view('modules.kegiatan.pengumuman.index', compact('pengumumans', 'stats'));
    }

    /**
     * Show the form for creating a new pengumuman
     */
    public function create()
    {
        $kegiatans = Kegiatan::aktif()->get();
        return view('modules.kegiatan.pengumuman.create', compact('kegiatans'));
    }

    /**
     * Store a newly created pengumuman
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kajian,kegiatan,event,umum',
            'konten' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'prioritas' => 'required|in:normal,tinggi,mendesak',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $validated['created_by'] = Auth::id();

        $pengumuman = Pengumuman::create($validated);

        // Log activity
        $this->activityLog->log(
            'create',
            'Pengumuman',
            "Membuat pengumuman: {$pengumuman->judul}",
            ['pengumuman_id' => $pengumuman->id]
        );

        return redirect()
            ->route('kegiatan.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    /**
     * Display the specified pengumuman
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load(['creator', 'kegiatan']);
        $pengumuman->incrementViews();

        return view('modules.kegiatan.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified pengumuman
     */
    public function edit(Pengumuman $pengumuman)
    {
        $kegiatans = Kegiatan::aktif()->get();
        return view('modules.kegiatan.pengumuman.edit', compact('pengumuman', 'kegiatans'));
    }

    /**
     * Update the specified pengumuman
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kajian,kegiatan,event,umum',
            'konten' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'prioritas' => 'required|in:normal,tinggi,mendesak',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $validated['updated_by'] = Auth::id();

        $pengumuman->update($validated);

        // Log activity
        $this->activityLog->log(
            'update',
            'Pengumuman',
            "Mengupdate pengumuman: {$pengumuman->judul}",
            ['pengumuman_id' => $pengumuman->id]
        );

        return redirect()
            ->route('kegiatan.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diupdate');
    }

    /**
     * Remove the specified pengumuman
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $judul = $pengumuman->judul;
        $pengumuman->delete();

        // Log activity
        $this->activityLog->log(
            'delete',
            'Pengumuman',
            "Menghapus pengumuman: {$judul}",
            ['pengumuman_id' => $pengumuman->id]
        );

        return redirect()
            ->route('kegiatan.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
