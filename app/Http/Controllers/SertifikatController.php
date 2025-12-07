<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display sertifikat generation page
     */
    public function index(Request $request)
    {
        // Get completed activities for selection
        $kegiatans = Kegiatan::where('status', 'selesai')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Get certificate history
        $query = Sertifikat::with(['kegiatan', 'generator'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('kegiatan_id')) {
            $query->byKegiatan($request->kegiatan_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sertifikats = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Sertifikat::count(),
            'bulan_ini' => Sertifikat::whereMonth('created_at', now()->month)->count(),
            'total_download' => Sertifikat::sum('download_count'),
        ];

        return view('modules.kegiatan.sertifikat.index', compact('kegiatans', 'sertifikats', 'stats'));
    }

    /**
     * Generate certificates
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'template' => 'required|in:kajian,workshop,pelatihan,default',
            'input_method' => 'required|in:manual,upload,from_peserta',
            'participants' => 'required_if:input_method,manual|string',
            'excel_file' => 'required_if:input_method,upload|file|mimes:xlsx,xls',
            'ttd_pejabat' => 'nullable|string|max:255',
            'jabatan_pejabat' => 'nullable|string|max:255',
        ]);

        $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);
        $pesertaList = [];

        // Get participants based on input method
        if ($validated['input_method'] === 'manual') {
            $pesertaList = array_filter(explode("\n", $validated['participants']));
            $pesertaList = array_map('trim', $pesertaList);
        } elseif ($validated['input_method'] === 'upload') {
            // TODO: Implement Excel parsing
            return back()->with('error', 'Upload Excel belum diimplementasikan');
        } elseif ($validated['input_method'] === 'from_peserta') {
            // Get from registered participants who attended
            $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatan->id)
                ->whereHas('absensi', function ($q) {
                    $q->where('status_kehadiran', 'hadir');
                })
                ->with('user')
                ->get();
            
            foreach ($peserta as $p) {
                $pesertaList[] = $p->user ? $p->user->name : $p->nama_peserta;
            }
        }

        if (empty($pesertaList)) {
            return back()->with('error', 'Tidak ada peserta yang akan digenerate sertifikat');
        }

        // Generate certificates
        $generated = [];
        $urutan = Sertifikat::where('kegiatan_id', $kegiatan->id)->count() + 1;

        foreach ($pesertaList as $namaPeserta) {
            if (empty($namaPeserta)) continue;

            $sertifikat = Sertifikat::create([
                'kegiatan_id' => $kegiatan->id,
                'nomor_sertifikat' => Sertifikat::generateNomorSertifikat($kegiatan->id, $urutan),
                'nama_peserta' => $namaPeserta,
                'nama_kegiatan' => $kegiatan->nama,
                'tanggal_kegiatan' => $kegiatan->tanggal_mulai,
                'tempat_kegiatan' => $kegiatan->lokasi,
                'template' => $validated['template'],
                'ttd_pejabat' => $validated['ttd_pejabat'] ?? 'Pengurus Masjid',
                'jabatan_pejabat' => $validated['jabatan_pejabat'] ?? 'Ketua Takmir',
                'generated_by' => Auth::id(),
                'metadata' => [
                    'generated_at' => now()->toDateTimeString(),
                    'batch_id' => Str::uuid(),
                ],
            ]);

            $generated[] = $sertifikat;
            $urutan++;
        }

        // Log activity
        $this->activityLog->log(
            'create',
            'Sertifikat',
            "Generate " . count($generated) . " sertifikat untuk kegiatan: {$kegiatan->nama}",
            ['kegiatan_id' => $kegiatan->id, 'jumlah' => count($generated)]
        );

        return redirect()
            ->route('kegiatan.sertifikat.index')
            ->with('success', count($generated) . ' sertifikat berhasil digenerate');
    }

    /**
     * Download single certificate
     */
    public function download(Sertifikat $sertifikat)
    {
        $sertifikat->incrementDownload();

        // TODO: Generate PDF certificate
        // For now, return JSON data
        return response()->json([
            'message' => 'Download certificate (PDF generation not yet implemented)',
            'data' => $sertifikat,
        ]);
    }

    /**
     * Download all certificates for a kegiatan as ZIP
     */
    public function downloadBatch(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
        ]);

        $sertifikats = Sertifikat::where('kegiatan_id', $validated['kegiatan_id'])->get();

        if ($sertifikats->isEmpty()) {
            return back()->with('error', 'Tidak ada sertifikat untuk didownload');
        }

        // TODO: Generate ZIP file with all certificates
        // For now, return JSON data
        return response()->json([
            'message' => 'Download batch (ZIP generation not yet implemented)',
            'count' => $sertifikats->count(),
            'data' => $sertifikats,
        ]);
    }

    /**
     * Delete certificate
     */
    public function destroy(Sertifikat $sertifikat)
    {
        $nama = $sertifikat->nama_peserta;
        $sertifikat->delete();

        // Log activity
        $this->activityLog->log(
            'delete',
            'Sertifikat',
            "Menghapus sertifikat: {$nama}",
            ['sertifikat_id' => $sertifikat->id]
        );

        return redirect()
            ->route('kegiatan.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus');
    }

    /**
     * Get participants for a kegiatan (for AJAX)
     */
    public function getPeserta(Request $request)
    {
        $kegiatanId = $request->input('kegiatan_id');
        
        $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatanId)
            ->whereHas('absensi', function ($q) {
                $q->where('status_kehadiran', 'hadir');
            })
            ->with('user')
            ->get()
            ->map(function ($p) {
                return [
                    'nama' => $p->user ? $p->user->name : $p->nama_peserta,
                    'email' => $p->email,
                ];
            });

        return response()->json($peserta);
    }
}
