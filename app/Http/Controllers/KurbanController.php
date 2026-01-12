<?php

namespace App\Http\Controllers;

use App\Models\Kurban;
use App\Models\PesertaKurban;
use App\Models\DistribusiKurban;
use App\Exports\KurbanReportExport;
use App\Services\AuthService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KurbanController extends Controller
{
    protected $authService;
    protected $activityLogService;

    public function __construct(AuthService $authService, ActivityLogService $activityLogService)
    {
        $this->authService = $authService;
        $this->activityLogService = $activityLogService;
    }

    // ===== DATA KURBAN =====

    /**
     * Tampilkan daftar kurban
     */
    public function index(Request $request)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke modul kurban.');
        }

        $query = Kurban::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis hewan
        if ($request->filled('jenis_hewan')) {
            $query->where('jenis_hewan', $request->jenis_hewan);
        }

        // Filter berdasarkan tanggal persiapan
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_persiapan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_persiapan', '<=', $request->end_date);
        }

        // Sorting
        $query->orderBy('tanggal_persiapan', 'desc');

        $kurbans = $query->paginate(15);
        $filters = [
            'status' => $request->status,
            'jenis_hewan' => $request->jenis_hewan,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        return view('modules.kurban.index', compact('kurbans', 'filters'));
    }

    /**
     * Tampilkan form create kurban
     */
    public function create()
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat kurban baru.');
        }

        return view('modules.kurban.create');
    }

    /**
     * Simpan kurban baru
     */
    public function store(Request $request)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat kurban baru.');
        }

        $validated = $request->validate([
            'nomor_kurban' => 'required|string|unique:kurbans',
            'jenis_hewan' => 'required|in:sapi,kambing,domba',
            'jenis_kelamin' => 'nullable|in:jantan,betina',
            'nama_hewan' => 'nullable|string|max:100',
            'berat_badan' => 'required|numeric|min:0.01',
            'kondisi_kesehatan' => 'required|in:sehat,cacat_ringan,cacat_berat',
            'tanggal_persiapan' => 'required|date',
            'harga_hewan' => 'required|numeric|min:0',
            'biaya_operasional' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['biaya_operasional'] = $validated['biaya_operasional'] ?? 0;
        $validated['total_biaya'] = $validated['harga_hewan'] + $validated['biaya_operasional'];

        // Set max_kuota based on jenis_hewan
        $validated['max_kuota'] = match ($validated['jenis_hewan']) {
            'sapi' => 7,
            'kambing', 'domba' => 1,
            default => 1,
        };

        // Calculate and lock price per bagian
        $validated['harga_per_bagian'] = round($validated['total_biaya'] / $validated['max_kuota'], 2);

        $validated['created_by'] = auth()->id();

        $kurban = Kurban::create($validated);

        // Log activity
        $this->activityLogService->log(
            'kurban_create',
            'kurban',
            "Kurban baru '{$kurban->nomor_kurban}' telah dibuat",
            ['kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Kurban baru berhasil dibuat.');
    }

    /**
     * Tampilkan detail kurban
     */
    public function show(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke modul kurban.');
        }

        $pesertaKurbans = $kurban->pesertaKurbans()->paginate(10, ['*'], 'peserta_page');
        $distribusiKurbans = $kurban->distribusiKurbans()->paginate(10, ['*'], 'distribusi_page');

        return view('modules.kurban.show', compact('kurban', 'pesertaKurbans', 'distribusiKurbans'));
    }

    /**
     * Tampilkan form edit kurban
     */
    public function edit(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kurban.');
        }

        return view('modules.kurban.edit', compact('kurban'));
    }

    /**
     * Update kurban
     */
    public function update(Request $request, Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kurban.');
        }

        $validated = $request->validate([
            'jenis_kelamin' => 'nullable|in:jantan,betina',
            'nama_hewan' => 'nullable|string|max:100',
            'berat_badan' => 'required|numeric|min:0.01',
            'kondisi_kesehatan' => 'required|in:sehat,cacat_ringan,cacat_berat',
            'tanggal_persiapan' => 'required|date',
            'tanggal_penyembelihan' => 'nullable|date|after_or_equal:tanggal_persiapan',
            'harga_hewan' => 'required|numeric|min:0',
            'biaya_operasional' => 'nullable|numeric|min:0',
            'total_berat_daging' => 'nullable|numeric|min:0',
            'status' => 'required|in:disiapkan,siap_sembelih,disembelih,didistribusi,selesai',
            'catatan' => 'nullable|string',
        ]);

        $validated['biaya_operasional'] = $validated['biaya_operasional'] ?? 0;
        $validated['total_biaya'] = $validated['harga_hewan'] + $validated['biaya_operasional'];

        // Recalculate locked price if costs changed
        if (
            $kurban->harga_hewan != $validated['harga_hewan'] ||
            $kurban->biaya_operasional != $validated['biaya_operasional']
        ) {
            $validated['harga_per_bagian'] = round($validated['total_biaya'] / $kurban->max_kuota, 2);
        }

        $validated['updated_by'] = auth()->id();

        $kurban->update($validated);

        // Log activity
        $this->activityLogService->log(
            'kurban_update',
            'kurban',
            "Kurban '{$kurban->nomor_kurban}' telah diubah",
            ['kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Kurban berhasil diubah.');
    }

    /**
     * Hapus kurban
     */
    public function destroy(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kurban.');
        }

        $nomorKurban = $kurban->nomor_kurban;
        $kurban->delete();

        // Log activity
        $this->activityLogService->log(
            'kurban_delete',
            'kurban',
            "Kurban '{$nomorKurban}' telah dihapus",
            ['kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.index')
            ->with('success', 'Kurban berhasil dihapus.');
    }

    // ===== PESERTA KURBAN =====

    /**
     * Tampilkan form tambah peserta kurban
     */
    public function createPeserta(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah peserta.');
        }

        return view('modules.kurban.peserta-create', compact('kurban'));
    }

    /**
     * Simpan peserta kurban baru
     */
    public function storePeserta(Request $request, Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah peserta.');
        }

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:100',
            'bin_binti' => 'nullable|string|max:100',
            'nomor_identitas' => 'nullable|string|max:20',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tipe_peserta' => 'required|in:perorangan,keluarga',
            'jumlah_jiwa' => 'required|integer|min:1',
            'jumlah_bagian' => 'required|numeric|min:0.25',
            'status_pembayaran' => 'required|in:belum_lunas,lunas,cicilan',
            'catatan' => 'nullable|string',
        ]);

        // SMART VALIDATION: Check quota availability
        if ($kurban->isKuotaFull()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quota' => "Kuota untuk {$kurban->jenis_hewan} '{$kurban->nomor_kurban}' sudah penuh. Maksimal {$kurban->max_kuota} peserta."]);
        }

        // SMART VALIDATION: For Kambing/Domba, must be exactly 1 person = 1 unit
        if (in_array($kurban->jenis_hewan, ['kambing', 'domba'])) {
            if ($validated['jumlah_bagian'] != 1) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jumlah_bagian' => 'Kambing/Domba harus 1 orang = 1 ekor. Tidak bisa patungan.']);
            }
        }

        // SMART VALIDATION: For Sapi, check if still accepting participants
        if ($kurban->jenis_hewan === 'sapi') {
            if (!$kurban->canAddParticipant()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quota' => "Sistem menolak! Kuota sapi maksimal 7 orang sudah terpenuhi."]);
            }
        }

        // AUTOMATIC CALCULATOR: Calculate payment based on locked price
        $validated['nominal_pembayaran'] = $kurban->calculatePembayaran((int) $validated['jumlah_bagian']);

        $validated['kurban_id'] = $kurban->id;
        $validated['created_by'] = auth()->id();

        if ($request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
        }

        if ($validated['status_pembayaran'] === 'lunas') {
            $validated['tanggal_pembayaran'] = now()->toDateString();
        }

        $peserta = PesertaKurban::create($validated);

        // SYNC TO FINANCE: If user pays immediately (Lunas)
        if ($validated['status_pembayaran'] === 'lunas') {
            \App\Models\Pemasukan::create([
                'jenis' => 'qurban',
                'sumber' => "Qurban - {$peserta->nama_peserta}",
                'jumlah' => $validated['nominal_pembayaran'],
                'tanggal' => now(),
                'keterangan' => "[Auto Sync Kurban] Pembayaran Qurban {$kurban->jenis_hewan} (Kelompok {$kurban->nomor_kurban})",
                'user_id' => auth()->id(),
                'status' => 'verified', // Auto verified
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
        }

        // Log activity
        $this->activityLogService->log(
            'peserta_kurban_create',
            'kurban',
            "Peserta '{$peserta->nama_peserta}' ditambahkan ke kurban '{$kurban->nomor_kurban}'",
            ['peserta_kurban_id' => $peserta->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Peserta kurban berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit peserta kurban
     */
    public function editPeserta(Kurban $kurban, PesertaKurban $peserta)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah peserta.');
        }

        return view('modules.kurban.peserta-edit', compact('kurban', 'peserta'));
    }

    /**
     * Update peserta kurban
     */
    public function updatePeserta(Request $request, Kurban $kurban, PesertaKurban $peserta)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit peserta.');
        }

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:100',
            'bin_binti' => 'nullable|string|max:100',
            'nomor_identitas' => 'nullable|string|max:20',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status_pembayaran' => 'required|in:belum_lunas,lunas,cicilan',
            'catatan' => 'nullable|string',
        ]);

        $oldStatus = $peserta->status_pembayaran;

        // Update tanggal pelunasan jika baru lunas sekarang
        if ($validated['status_pembayaran'] === 'lunas' && $oldStatus !== 'lunas') {
            $validated['tanggal_pembayaran'] = now()->toDateString();

            // SYNC TO FINANCE: Record payment when status changes to 'lunas'
            \App\Models\Pemasukan::create([
                'jenis' => 'qurban',
                'sumber' => "Qurban - {$validated['nama_peserta']}",
                'jumlah' => $peserta->nominal_pembayaran, // Use stored nominal
                'tanggal' => now(),
                'keterangan' => "[Auto Sync Kurban] Pelunasan Qurban {$kurban->jenis_hewan} (Kelompok {$kurban->nomor_kurban})",
                'user_id' => auth()->id(),
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
        }

        $peserta->update($validated);

        // Log activity
        $this->activityLogService->log(
            'peserta_kurban_update',
            'kurban',
            "Peserta '{$peserta->nama_peserta}' pada kurban '{$kurban->nomor_kurban}' diubah",
            ['peserta_kurban_id' => $peserta->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Hapus peserta kurban
     */
    public function destroyPeserta(Kurban $kurban, PesertaKurban $peserta)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peserta.');
        }

        $namaPeserta = $peserta->nama_peserta;
        $peserta->delete();

        // Log activity
        $this->activityLogService->log(
            'peserta_kurban_delete',
            'kurban',
            "Peserta '{$namaPeserta}' dihapus dari kurban '{$kurban->nomor_kurban}'",
            ['peserta_kurban_id' => $peserta->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Peserta kurban berhasil dihapus.');
    }

    // ===== DISTRIBUSI KURBAN =====

    /**
     * Tampilkan form tambah distribusi kurban
     */
    public function createDistribusi(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah distribusi.');
        }

        $pesertaKurbans = $kurban->pesertaKurbans()->get();

        return view('modules.kurban.distribusi-create', compact('kurban', 'pesertaKurbans'));
    }

    /**
     * Simpan distribusi kurban baru
     */
    public function storeDistribusi(Request $request, Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah distribusi.');
        }

        $validated = $request->validate([
            'peserta_kurban_id' => 'nullable|exists:peserta_kurbans,id',
            'penerima_nama' => 'required|string|max:100',
            'penerima_nomor_telepon' => 'nullable|string|max:20',
            'penerima_alamat' => 'nullable|string',
            'berat_daging' => 'required|numeric|min:0.01',
            'estimasi_harga' => 'required|numeric|min:0',
            'jenis_distribusi' => 'required|in:shohibul_qurban,fakir_miskin,yayasan',
            'persentase_alokasi' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Set default persentase_alokasi based on jenis_distribusi
        if (!isset($validated['persentase_alokasi'])) {
            $validated['persentase_alokasi'] = match ($validated['jenis_distribusi']) {
                'shohibul_qurban' => 33.33, // 1/3 bagian
                'fakir_miskin' => 33.33,     // 1/3 bagian
                'yayasan' => 33.34,          // 1/3 bagian (rounding)
                default => 33.33,
            };
        }

        $validated['kurban_id'] = $kurban->id;
        $validated['created_by'] = auth()->id();

        $distribusi = DistribusiKurban::create($validated);

        // Log activity
        $this->activityLogService->log(
            'distribusi_kurban_create',
            'kurban',
            "Distribusi kurban untuk '{$distribusi->penerima_nama}' ditambahkan",
            ['distribusi_kurban_id' => $distribusi->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Distribusi kurban berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit distribusi kurban
     */
    public function editDistribusi(Kurban $kurban, DistribusiKurban $distribusi)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah distribusi.');
        }

        $pesertaKurbans = $kurban->pesertaKurbans()->get();

        return view('modules.kurban.distribusi-edit', compact('kurban', 'distribusi', 'pesertaKurbans'));
    }

    /**
     * Update distribusi kurban
     */
    public function updateDistribusi(Request $request, Kurban $kurban, DistribusiKurban $distribusi)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah distribusi.');
        }

        $validated = $request->validate([
            'peserta_kurban_id' => 'nullable|exists:peserta_kurbans,id',
            'penerima_nama' => 'required|string|max:100',
            'penerima_nomor_telepon' => 'nullable|string|max:20',
            'penerima_alamat' => 'nullable|string',
            'berat_daging' => 'required|numeric|min:0.01',
            'estimasi_harga' => 'required|numeric|min:0',
            'jenis_distribusi' => 'required|in:shohibul_qurban,fakir_miskin,yayasan',
            'persentase_alokasi' => 'nullable|numeric|min:0|max:100',
            'status_distribusi' => 'required|in:belum_didistribusi,sedang_disiapkan,sudah_didistribusi',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        if ($validated['status_distribusi'] === 'sudah_didistribusi' && is_null($distribusi->tanggal_distribusi)) {
            $validated['tanggal_distribusi'] = now()->toDateString();
        }

        $distribusi->update($validated);

        // Log activity
        $this->activityLogService->log(
            'distribusi_kurban_update',
            'kurban',
            "Distribusi kurban untuk '{$distribusi->penerima_nama}' diubah",
            ['distribusi_kurban_id' => $distribusi->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Distribusi kurban berhasil diubah.');
    }

    /**
     * Hapus distribusi kurban
     */
    public function destroyDistribusi(Kurban $kurban, DistribusiKurban $distribusi)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus distribusi.');
        }

        $namaPenerima = $distribusi->penerima_nama;
        $distribusi->delete();

        // Log activity
        $this->activityLogService->log(
            'distribusi_kurban_delete',
            'kurban',
            "Distribusi kurban untuk '{$namaPenerima}' dihapus",
            ['distribusi_kurban_id' => $distribusi->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Distribusi kurban berhasil dihapus.');
    }

    // ===== LAPORAN & DASHBOARD =====

    /**
     * Generate and download PDF report for specific Kurban
     * Report includes: Financial data, Participant data, Distribution details
     */
    public function downloadReport(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat laporan kurban.');
        }

        $exporter = new KurbanReportExport($kurban);

        // Log activity
        $this->activityLogService->log(
            'kurban_report_download',
            'kurban',
            "Laporan kurban '{$kurban->nomor_kurban}' diunduh",
            ['kurban_id' => $kurban->id]
        );

        return $exporter->download();
    }

    /**
     * Show PDF report in browser for specific Kurban
     */
    public function viewReport(Kurban $kurban)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat laporan kurban.');
        }

        $exporter = new KurbanReportExport($kurban);

        return $exporter->stream();
    }

    /**
     * Display Kurban dashboard with visual progress
     */
    public function dashboard(Request $request)
    {
        // Check permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke dashboard kurban.');
        }

        $query = Kurban::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year
        $tahun = $request->input('tahun', now()->year);
        $query->whereYear('tanggal_persiapan', $tahun);

        $kurbans = $query->with(['pesertaKurbans', 'distribusiKurbans'])
            ->orderBy('tanggal_persiapan', 'desc')
            ->get();

        // Calculate dashboard statistics
        $statistics = [
            'total_kurban' => $kurbans->count(),
            'total_peserta' => $kurbans->sum(fn($k) => $k->pesertaKurbans->count()),
            'total_pembayaran' => $kurbans->sum(fn($k) => $k->totalPembayaran()),
            'total_daging_distribusi' => $kurbans->sum(fn($k) => $k->totalDagingDidistribusi()),

            'kurban_disiapkan' => $kurbans->where('status', 'disiapkan')->count(),
            'kurban_siap_sembelih' => $kurbans->where('status', 'siap_sembelih')->count(),
            'kurban_disembelih' => $kurbans->where('status', 'disembelih')->count(),
            'kurban_selesai' => $kurbans->where('status', 'selesai')->count(),
        ];

        return view('modules.kurban.dashboard', compact('kurbans', 'statistics', 'tahun'));
    }
}

