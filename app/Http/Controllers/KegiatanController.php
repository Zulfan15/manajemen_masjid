<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use App\Models\KegiatanAbsensi;
use App\Models\KegiatanNotifikasi;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of kegiatan
     */
    public function index(Request $request)
    {
        $query = Kegiatan::with(['creator', 'peserta']);

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis_kegiatan', $request->jenis);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_mulai', '<=', $request->tanggal_selesai);
        }

        $kegiatans = $query->orderBy('tanggal_mulai', 'desc')->paginate(10);

        // Statistics
        $stats = [
            'total' => Kegiatan::count(),
            'mendatang' => Kegiatan::mendatang()->count(),
            'berlangsung' => Kegiatan::berlangsung()->count(),
            'selesai' => Kegiatan::selesai()->count(),
        ];

        return view('modules.kegiatan.index', compact('kegiatans', 'stats'));
    }

    /**
     * Show the form for creating a new kegiatan
     */
    public function create()
    {
        return view('modules.kegiatan.create');
    }

    /**
     * Store a newly created kegiatan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:rutin,insidental,event_khusus',
            'kategori' => 'required|in:kajian,sosial,ibadah,pendidikan,ramadan,maulid,qurban,lainnya',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'kontak_pic' => 'nullable|string|max:20',
            'kuota_peserta' => 'nullable|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|in:harian,mingguan,bulanan,tahunan',
            'recurring_day' => 'nullable|string',
            'butuh_pendaftaran' => 'boolean',
            'sertifikat_tersedia' => 'boolean',
            'catatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload gambar if exists
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . Str::slug($validated['nama_kegiatan']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kegiatan', $filename, 'public');
                $validated['gambar'] = $path;
            }

            $validated['created_by'] = auth()->id();
            $validated['status'] = 'direncanakan';

            $kegiatan = Kegiatan::create($validated);

            // Log activity
            $this->activityLogService->logCrud('create', 'kegiatan', 'kegiatan', $kegiatan->id, [
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
            ]);

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified kegiatan
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::with(['peserta.user', 'peserta.absensi', 'creator'])->findOrFail($id);
        
        $pesertaStats = [
            'total' => $kegiatan->peserta->count(),
            'terdaftar' => $kegiatan->peserta->where('status_pendaftaran', 'terdaftar')->count(),
            'dikonfirmasi' => $kegiatan->peserta->where('status_pendaftaran', 'dikonfirmasi')->count(),
            'hadir' => $kegiatan->absensi()->hadir()->count(),
        ];

        return view('modules.kegiatan.show', compact('kegiatan', 'pesertaStats'));
    }

    /**
     * Show the form for editing the specified kegiatan
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('modules.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified kegiatan
     */
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:rutin,insidental,event_khusus',
            'kategori' => 'required|in:kajian,sosial,ibadah,pendidikan,ramadan,maulid,qurban,lainnya',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'kontak_pic' => 'nullable|string|max:20',
            'kuota_peserta' => 'nullable|integer|min:1',
            'status' => 'required|in:direncanakan,berlangsung,selesai,dibatalkan',
            'budget' => 'nullable|numeric|min:0',
            'realisasi_biaya' => 'nullable|numeric|min:0',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|in:harian,mingguan,bulanan,tahunan',
            'recurring_day' => 'nullable|string',
            'butuh_pendaftaran' => 'boolean',
            'sertifikat_tersedia' => 'boolean',
            'catatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload gambar baru if exists
            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($kegiatan->gambar && Storage::disk('public')->exists($kegiatan->gambar)) {
                    Storage::disk('public')->delete($kegiatan->gambar);
                }

                $file = $request->file('gambar');
                $filename = time() . '_' . Str::slug($validated['nama_kegiatan']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kegiatan', $filename, 'public');
                $validated['gambar'] = $path;
            }

            $validated['updated_by'] = auth()->id();

            $kegiatan->update($validated);

            // Log activity
            $this->activityLogService->logCrud('update', 'kegiatan', 'kegiatan', $kegiatan->id);

            DB::commit();

            return redirect()->route('kegiatan.show', $kegiatan->id)
                ->with('success', 'Kegiatan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified kegiatan
     */
    public function destroy($id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $nama = $kegiatan->nama_kegiatan;

            // Delete image
            if ($kegiatan->gambar && Storage::disk('public')->exists($kegiatan->gambar)) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }

            $kegiatan->delete();

            // Log activity
            $this->activityLogService->logCrud('delete', 'kegiatan', 'kegiatan', $id, [
                'nama_kegiatan' => $nama,
            ]);

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Register peserta to kegiatan
     */
    public function registerPeserta(Request $request, $kegiatanId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        // Check if kegiatan is full
        if ($kegiatan->isFull()) {
            return back()->with('error', 'Kuota peserta sudah penuh!');
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_peserta' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $validated['kegiatan_id'] = $kegiatanId;
            $validated['user_id'] = $validated['user_id'] ?? auth()->id();
            $validated['metode_pendaftaran'] = 'online';
            $validated['status_pendaftaran'] = 'terdaftar';

            // Check if user already registered
            $exists = KegiatanPeserta::where('kegiatan_id', $kegiatanId)
                ->where('user_id', $validated['user_id'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Anda sudah terdaftar di kegiatan ini!');
            }

            $peserta = KegiatanPeserta::create($validated);
            $kegiatan->incrementPeserta();

            // Send notification
            $this->sendNotification($kegiatan, $peserta->user_id, 'konfirmasi');

            // Log activity
            $this->activityLogService->log('register_peserta', 'kegiatan', 
                "Mendaftar ke kegiatan: {$kegiatan->nama_kegiatan}", [
                    'kegiatan_id' => $kegiatanId,
                    'peserta_id' => $peserta->id,
                ]);

            DB::commit();

            return back()->with('success', 'Pendaftaran berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show absensi page for kegiatan
     */
    public function absensi($kegiatanId)
    {
        $kegiatan = Kegiatan::with(['peserta.user', 'peserta.absensi'])->findOrFail($kegiatanId);
        
        return view('modules.kegiatan.absensi', compact('kegiatan'));
    }

    /**
     * Store absensi for peserta
     */
    public function storeAbsensi(Request $request, $kegiatanId)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:kegiatan_peserta,id',
            'status_kehadiran' => 'required|in:hadir,tidak_hadir,izin,sakit',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $validated['kegiatan_id'] = $kegiatanId;
            $validated['metode_absen'] = 'manual';
            $validated['dicatat_oleh'] = auth()->id();

            // Check if already exists
            $exists = KegiatanAbsensi::where('kegiatan_id', $kegiatanId)
                ->where('peserta_id', $validated['peserta_id'])
                ->first();

            if ($exists) {
                $exists->update($validated);
                $message = 'Absensi berhasil diperbarui!';
            } else {
                KegiatanAbsensi::create($validated);
                $message = 'Absensi berhasil dicatat!';
            }

            // Log activity
            $this->activityLogService->log('catat_absensi', 'kegiatan', 
                "Mencatat absensi kegiatan", [
                    'kegiatan_id' => $kegiatanId,
                    'peserta_id' => $validated['peserta_id'],
                    'status' => $validated['status_kehadiran'],
                ]);

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to user
     */
    private function sendNotification($kegiatan, $userId, $tipe = 'info')
    {
        $messages = [
            'info' => [
                'judul' => 'Informasi Kegiatan Baru',
                'pesan' => "Ada kegiatan baru: {$kegiatan->nama_kegiatan} pada " . $kegiatan->tanggal_mulai->format('d M Y'),
            ],
            'reminder' => [
                'judul' => 'Pengingat Kegiatan',
                'pesan' => "Jangan lupa! Kegiatan {$kegiatan->nama_kegiatan} akan dimulai besok",
            ],
            'konfirmasi' => [
                'judul' => 'Pendaftaran Berhasil',
                'pesan' => "Anda berhasil terdaftar di kegiatan: {$kegiatan->nama_kegiatan}",
            ],
            'pembatalan' => [
                'judul' => 'Kegiatan Dibatalkan',
                'pesan' => "Kegiatan {$kegiatan->nama_kegiatan} telah dibatalkan",
            ],
        ];

        KegiatanNotifikasi::create([
            'kegiatan_id' => $kegiatan->id,
            'user_id' => $userId,
            'judul' => $messages[$tipe]['judul'],
            'pesan' => $messages[$tipe]['pesan'],
            'tipe' => $tipe,
            'channel' => 'in_app',
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Broadcast notification to all jamaah
     */
    public function broadcastNotification(Request $request, $kegiatanId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        $validated = $request->validate([
            'tipe' => 'required|in:info,reminder,pengumuman',
        ]);

        try {
            // Get all users with jamaah role
            $jamaahs = User::role('jamaah')->get();

            foreach ($jamaahs as $jamaah) {
                $this->sendNotification($kegiatan, $jamaah->id, $validated['tipe']);
            }

            return back()->with('success', 'Notifikasi berhasil dikirim ke semua jamaah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
