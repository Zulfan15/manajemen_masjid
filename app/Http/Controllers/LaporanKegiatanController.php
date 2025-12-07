<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Models\Kegiatan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanKegiatanController extends Controller
{
    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of laporan
     */
    public function index(Request $request)
    {
        $query = LaporanKegiatan::with(['kegiatan', 'creator'])
            ->orderBy('tanggal_pelaksanaan', 'desc');

        // Apply filters
        if ($request->filled('jenis')) {
            $query->byJenis($request->jenis);
        }

        if ($request->filled('bulan')) {
            $query->byBulan($request->bulan);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $laporans = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => LaporanKegiatan::count(),
            'bulan_ini' => LaporanKegiatan::whereMonth('tanggal_pelaksanaan', now()->month)->count(),
            'total_peserta' => LaporanKegiatan::sum('jumlah_hadir'),
            'kegiatan_aktif' => Kegiatan::aktif()->count(),
        ];

        return view('modules.kegiatan.laporan.index', compact('laporans', 'stats'));
    }

    /**
     * Show the form for creating a new laporan
     */
    public function create()
    {
        $kegiatans = Kegiatan::where('status', 'selesai')
            ->whereDoesntHave('laporan')
            ->get();
        
        return view('modules.kegiatan.laporan.create', compact('kegiatans'));
    }

    /**
     * Store a newly created laporan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:kajian,sosial,pendidikan,perayaan,lainnya',
            'tanggal_pelaksanaan' => 'required|date',
            'waktu_pelaksanaan' => 'required',
            'lokasi' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'jumlah_hadir' => 'nullable|integer|min:0',
            'jumlah_tidak_hadir' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'hasil_capaian' => 'nullable|string',
            'catatan_kendala' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_public' => 'boolean',
            'foto_dokumentasi.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo uploads
        $fotoPaths = [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $path = $foto->store('laporan_kegiatan', 'public');
                $fotoPaths[] = $path;
            }
        }
        
        // Only set foto_dokumentasi if there are photos
        if (!empty($fotoPaths)) {
            $validated['foto_dokumentasi'] = $fotoPaths;
        } else {
            $validated['foto_dokumentasi'] = null;
        }

        // Auto-calculate if not provided
        if (!isset($validated['jumlah_hadir'])) {
            $validated['jumlah_hadir'] = $validated['jumlah_peserta'];
        }
        if (!isset($validated['jumlah_tidak_hadir'])) {
            $validated['jumlah_tidak_hadir'] = $validated['jumlah_peserta'] - $validated['jumlah_hadir'];
        }

        $validated['created_by'] = Auth::id();

        $laporan = LaporanKegiatan::create($validated);

        // Log activity
        $this->activityLog->log(
            'create',
            'LaporanKegiatan',
            "Membuat laporan kegiatan: {$laporan->nama_kegiatan}",
            ['laporan_id' => $laporan->id]
        );

        return redirect()
            ->route('kegiatan.laporan.index')
            ->with('success', 'Laporan kegiatan berhasil dibuat');
    }

    /**
     * Display the specified laporan
     */
    public function show(LaporanKegiatan $laporan)
    {
        $laporan->load(['kegiatan', 'creator']);
        return view('modules.kegiatan.laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified laporan
     */
    public function edit(LaporanKegiatan $laporan)
    {
        $kegiatans = Kegiatan::where('status', 'selesai')->get();
        return view('modules.kegiatan.laporan.edit', compact('laporan', 'kegiatans'));
    }

    /**
     * Update the specified laporan
     */
    public function update(Request $request, LaporanKegiatan $laporan)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:kajian,sosial,pendidikan,perayaan,lainnya',
            'tanggal_pelaksanaan' => 'required|date',
            'waktu_pelaksanaan' => 'required',
            'lokasi' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'jumlah_hadir' => 'nullable|integer|min:0',
            'jumlah_tidak_hadir' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'hasil_capaian' => 'nullable|string',
            'catatan_kendala' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_public' => 'boolean',
            'foto_dokumentasi.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle new photo uploads
        $fotoPaths = $laporan->foto_dokumentasi ?? [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $path = $foto->store('laporan_kegiatan', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['foto_dokumentasi'] = $fotoPaths;

        $validated['updated_by'] = Auth::id();

        $laporan->update($validated);

        // Log activity
        $this->activityLog->log(
            'update',
            'LaporanKegiatan',
            "Mengupdate laporan kegiatan: {$laporan->nama_kegiatan}",
            ['laporan_id' => $laporan->id]
        );

        return redirect()
            ->route('kegiatan.laporan.index')
            ->with('success', 'Laporan kegiatan berhasil diupdate');
    }

    /**
     * Remove the specified laporan
     */
    public function destroy(LaporanKegiatan $laporan)
    {
        // Delete associated photos
        if ($laporan->foto_dokumentasi) {
            foreach ($laporan->foto_dokumentasi as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $nama = $laporan->nama_kegiatan;
        $laporan->delete();

        // Log activity
        $this->activityLog->log(
            'delete',
            'LaporanKegiatan',
            "Menghapus laporan kegiatan: {$nama}",
            ['laporan_id' => $laporan->id]
        );

        return redirect()
            ->route('kegiatan.laporan.index')
            ->with('success', 'Laporan kegiatan berhasil dihapus');
    }

    /**
     * Download laporan as PDF
     */
    public function download(LaporanKegiatan $laporan)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.kegiatan.laporan.pdf', compact('laporan'))
            ->setPaper('a4', 'portrait');
        
        $filename = 'Laporan_' . str_replace(' ', '_', $laporan->nama_kegiatan) . '_' . date('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }
}
