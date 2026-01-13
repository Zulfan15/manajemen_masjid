<?php

namespace App\Http\Controllers;

use App\Models\Kurban;
use App\Models\PesertaKurban;
use App\Models\DistribusiKurban;
use App\Models\Pemasukan; // Pastikan model ini ada untuk fitur sync keuangan
use App\Services\AuthService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Menggunakan DOMPDF sesuai view sebelumnya

class KurbanController extends Controller
{
    protected $authService;
    protected $activityLogService;

    public function __construct(AuthService $authService, ActivityLogService $activityLogService)
    {
        $this->authService = $authService;
        $this->activityLogService = $activityLogService;
    }

    // =========================================================================
    // DATA KURBAN (LOGIC TARGET: Support Max Kuota & Auto Price)
    // =========================================================================

    /**
     * Dashboard Kurban - Menampilkan statistik dan ringkasan
     */
    public function dashboard(Request $request)
    {
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke modul kurban.');
        }

        $tahun = $request->input('tahun', now()->year);
        $status = $request->input('status');

        // Base query untuk kurban tahun ini
        $query = Kurban::whereYear('tanggal_persiapan', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $kurbans = $query->with(['pesertaKurbans', 'distribusiKurbans'])->get();

        // Hitung statistik
        $statistics = [
            'total_kurban' => $kurbans->count(),
            'total_peserta' => $kurbans->sum(fn($k) => $k->pesertaKurbans->count()),
            'total_pembayaran' => $kurbans->sum(fn($k) => $k->pesertaKurbans->sum('nominal_pembayaran')),
            'total_daging_distribusi' => $kurbans->sum(fn($k) => $k->distribusiKurbans->sum('berat_daging')),
            // Status counts untuk view
            'kurban_disiapkan' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'disiapkan')->count(),
            'kurban_siap_sembelih' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'siap_sembelih')->count(),
            'kurban_disembelih' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'disembelih')->count(),
            'kurban_selesai' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'selesai')->count(),
        ];

        // Status distribution untuk chart
        $statusDistribution = [
            'disiapkan' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'disiapkan')->count(),
            'siap_sembelih' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'siap_sembelih')->count(),
            'disembelih' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'disembelih')->count(),
            'selesai' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('status', 'selesai')->count(),
        ];

        // Jenis hewan distribution
        $jenisDistribution = [
            'sapi' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('jenis_hewan', 'sapi')->count(),
            'kambing' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('jenis_hewan', 'kambing')->count(),
            'domba' => Kurban::whereYear('tanggal_persiapan', $tahun)->where('jenis_hewan', 'domba')->count(),
        ];

        // Kurban terbaru
        $latestKurbans = Kurban::whereYear('tanggal_persiapan', $tahun)
            ->with(['pesertaKurbans'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('modules.kurban.dashboard', compact(
            'tahun',
            'statistics',
            'statusDistribution',
            'jenisDistribution',
            'latestKurbans',
            'kurbans'
        ));
    }

    public function index(Request $request)
    {
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke modul kurban.');
        }

        $query = Kurban::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_hewan')) {
            $query->where('jenis_hewan', $request->jenis_hewan);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_persiapan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_persiapan', '<=', $request->end_date);
        }

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

    public function create()
    {
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat kurban baru.');
        }
        return view('modules.kurban.create');
    }

    public function store(Request $request)
    {
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

        // Auto Set Max Kuota
        $validated['max_kuota'] = match ($validated['jenis_hewan']) {
            'sapi' => 7,
            'kambing', 'domba' => 1,
            default => 1,
        };

        // Auto Calculate Locked Price
        $validated['harga_per_bagian'] = round($validated['total_biaya'] / $validated['max_kuota'], 2);
        $validated['created_by'] = auth()->id();

        $kurban = Kurban::create($validated);

        $this->activityLogService->log('kurban_create', 'kurban', "Kurban baru '{$kurban->nomor_kurban}' telah dibuat", ['kurban_id' => $kurban->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Kurban baru berhasil dibuat.');
    }

    public function show(Kurban $kurban)
    {
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403, 'Anda tidak memiliki akses ke modul kurban.');
        }

        $pesertaKurbans = $kurban->pesertaKurbans()->paginate(10, ['*'], 'peserta_page');
        $distribusiKurbans = $kurban->distribusiKurbans()->paginate(10, ['*'], 'distribusi_page');

        return view('modules.kurban.show', compact('kurban', 'pesertaKurbans', 'distribusiKurbans'));
    }

    public function edit(Kurban $kurban)
    {
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kurban.');
        }
        return view('modules.kurban.edit', compact('kurban'));
    }

    public function update(Request $request, Kurban $kurban)
    {
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
        if ($kurban->harga_hewan != $validated['harga_hewan'] || $kurban->biaya_operasional != $validated['biaya_operasional']) {
            $validated['harga_per_bagian'] = round($validated['total_biaya'] / $kurban->max_kuota, 2);
        }

        $validated['updated_by'] = auth()->id();
        $kurban->update($validated);

        $this->activityLogService->log('kurban_update', 'kurban', "Kurban '{$kurban->nomor_kurban}' telah diubah", ['kurban_id' => $kurban->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Kurban berhasil diubah.');
    }

    public function destroy(Kurban $kurban)
    {
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kurban.');
        }

        $nomorKurban = $kurban->nomor_kurban;
        $kurban->delete();

        $this->activityLogService->log('kurban_delete', 'kurban', "Kurban '{$nomorKurban}' telah dihapus", ['kurban_id' => $kurban->id]);

        return redirect()->route('kurban.index')->with('success', 'Kurban berhasil dihapus.');
    }

    // =========================================================================
    // PESERTA KURBAN (LOGIC TARGET: Smart Validation & Finance Sync)
    // =========================================================================

    public function createPeserta(Kurban $kurban)
    {
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah peserta.');
        }
        return view('modules.kurban.peserta-create', compact('kurban'));
    }

    public function storePeserta(Request $request, Kurban $kurban)
    {
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
            return redirect()->back()->withInput()->withErrors(['quota' => "Kuota untuk {$kurban->jenis_hewan} '{$kurban->nomor_kurban}' sudah penuh."]);
        }

        // SMART VALIDATION: Kambing Rule
        if (in_array($kurban->jenis_hewan, ['kambing', 'domba'])) {
            if ($validated['jumlah_bagian'] != 1) {
                return redirect()->back()->withInput()->withErrors(['jumlah_bagian' => 'Kambing/Domba harus 1 orang = 1 ekor.']);
            }
        }

        // SMART VALIDATION: Sapi Rule
        if ($kurban->jenis_hewan === 'sapi') {
            if (!$kurban->canAddParticipant($validated['jumlah_bagian'])) {
                return redirect()->back()->withInput()->withErrors(['quota' => "Sisa kuota sapi tidak mencukupi untuk jumlah bagian yang diminta."]);
            }
        }

        // AUTOMATIC CALCULATOR
        $validated['nominal_pembayaran'] = $kurban->calculatePembayaran((float) $validated['jumlah_bagian']);

        $validated['kurban_id'] = $kurban->id;
        $validated['created_by'] = auth()->id();

        if ($request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
        }

        if ($validated['status_pembayaran'] === 'lunas') {
            $validated['tanggal_pembayaran'] = now()->toDateString();
        }

        $peserta = PesertaKurban::create($validated);

        // SYNC TO FINANCE (Target Feature)
        if ($validated['status_pembayaran'] === 'lunas') {
            // Cek apakah model Pemasukan ada, jika tidak, skip blok ini
            if (class_exists(Pemasukan::class)) {
                Pemasukan::create([
                    'jenis' => 'qurban',
                    'sumber' => "Qurban - {$peserta->nama_peserta}",
                    'jumlah' => $validated['nominal_pembayaran'],
                    'tanggal' => now(),
                    'keterangan' => "[Auto Sync] Pembayaran Qurban {$kurban->jenis_hewan} ({$kurban->nomor_kurban})",
                    'user_id' => auth()->id(),
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                ]);
            }
        }

        $this->activityLogService->log('peserta_kurban_create', 'kurban', "Peserta '{$peserta->nama_peserta}' ditambahkan", ['peserta_kurban_id' => $peserta->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Peserta kurban berhasil ditambahkan.');
    }

    public function editPeserta(Kurban $kurban, PesertaKurban $peserta)
    {
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah peserta.');
        }
        return view('modules.kurban.peserta-edit', compact('kurban', 'peserta'));
    }

    public function updatePeserta(Request $request, Kurban $kurban, PesertaKurban $peserta)
    {
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit peserta.');
        }

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:100',
            'bin_binti' => 'nullable|string|max:100',
            'nomor_identitas' => 'nullable|string|max:20',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            // Jumlah bagian biasanya tidak diubah di edit jika sudah bayar, tapi kita izinkan dengan validasi
            'jumlah_bagian' => 'required|numeric|min:0.25',
            'status_pembayaran' => 'required|in:belum_lunas,lunas,cicilan',
            'catatan' => 'nullable|string',
        ]);

        // VALIDASI KUOTA SAAT EDIT
        // (Total di DB) - (Punya Dia Lama) + (Punya Dia Baru)
        $currentUsed = $kurban->pesertaKurbans()->sum('jumlah_bagian');
        $newUsage = $currentUsed - $peserta->jumlah_bagian + $request->jumlah_bagian;

        $maxQuota = $kurban->max_kuota ?: $kurban->getMaxKuotaByJenisHewan();

        if ($newUsage > $maxQuota) {
            return redirect()->back()->withInput()->withErrors(['jumlah_bagian' => "Gagal update. Kuota akan melebihi batas maksimal."]);
        }

        // Recalculate price if bagian changes
        $validated['nominal_pembayaran'] = $kurban->calculatePembayaran((float) $validated['jumlah_bagian']);

        $oldStatus = $peserta->status_pembayaran;

        // Auto set tanggal lunas
        if ($validated['status_pembayaran'] === 'lunas' && $oldStatus !== 'lunas') {
            $validated['tanggal_pembayaran'] = now()->toDateString();

            // SYNC TO FINANCE (Target Feature)
            if (class_exists(Pemasukan::class)) {
                Pemasukan::create([
                    'jenis' => 'qurban',
                    'sumber' => "Qurban - {$validated['nama_peserta']}",
                    'jumlah' => $validated['nominal_pembayaran'],
                    'tanggal' => now(),
                    'keterangan' => "[Auto Sync] Pelunasan Qurban {$kurban->jenis_hewan} ({$kurban->nomor_kurban})",
                    'user_id' => auth()->id(),
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                ]);
            }
        }

        $peserta->update($validated);

        $this->activityLogService->log('peserta_kurban_update', 'kurban', "Peserta '{$peserta->nama_peserta}' diubah", ['peserta_kurban_id' => $peserta->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroyPeserta(Kurban $kurban, PesertaKurban $peserta)
    {
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peserta.');
        }

        $namaPeserta = $peserta->nama_peserta;
        $peserta->delete();

        $this->activityLogService->log('peserta_kurban_delete', 'kurban', "Peserta '{$namaPeserta}' dihapus", ['kurban_id' => $kurban->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Peserta kurban berhasil dihapus.');
    }

    // =========================================================================
    // DISTRIBUSI KURBAN (LOGIC TARGET: Modern Types & Percentage)
    // =========================================================================

    public function createDistribusi(Kurban $kurban)
    {
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah distribusi.');
        }
        $pesertaKurbans = $kurban->pesertaKurbans()->get();
        return view('modules.kurban.distribusi-create', compact('kurban', 'pesertaKurbans'));
    }

    public function storeDistribusi(Request $request, Kurban $kurban)
    {
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

        if (!isset($validated['persentase_alokasi'])) {
            $validated['persentase_alokasi'] = match ($validated['jenis_distribusi']) {
                'shohibul_qurban' => 33.33,
                'fakir_miskin' => 33.33,
                'yayasan' => 33.34,
                default => 33.33,
            };
        }

        $validated['kurban_id'] = $kurban->id;
        $validated['created_by'] = auth()->id();

        $distribusi = DistribusiKurban::create($validated);

        $this->activityLogService->log('distribusi_kurban_create', 'kurban', "Distribusi ke '{$distribusi->penerima_nama}' ditambahkan", ['distribusi_kurban_id' => $distribusi->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Distribusi kurban berhasil ditambahkan.');
    }

    public function editDistribusi(Kurban $kurban, DistribusiKurban $distribusi)
    {
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah distribusi.');
        }
        $pesertaKurbans = $kurban->pesertaKurbans()->get();
        return view('modules.kurban.distribusi-edit', compact('kurban', 'distribusi', 'pesertaKurbans'));
    }

    public function updateDistribusi(Request $request, Kurban $kurban, DistribusiKurban $distribusi)
    {
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

        $this->activityLogService->log('distribusi_kurban_update', 'kurban', "Distribusi ke '{$distribusi->penerima_nama}' diubah", ['distribusi_kurban_id' => $distribusi->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Distribusi kurban berhasil diubah.');
    }

    public function destroyDistribusi(Kurban $kurban, DistribusiKurban $distribusi)
    {
        if (!$this->authService->hasPermission('kurban.delete')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus distribusi.');
        }

        $namaPenerima = $distribusi->penerima_nama;
        $distribusi->delete();

        $this->activityLogService->log('distribusi_kurban_delete', 'kurban', "Distribusi ke '{$namaPenerima}' dihapus", ['kurban_id' => $kurban->id]);

        return redirect()->route('kurban.show', $kurban)->with('success', 'Distribusi kurban berhasil dihapus.');
    }

    // =========================================================================
    // LAPORAN PDF (LOGIC SOURCE: Menggunakan View Manual)
    // =========================================================================

    /**
     * Export Laporan PDF Menggunakan DOMPDF langsung (Sesuai View yang sudah dibuat)
     */
    public function exportPdf(Kurban $kurban)
    {
        // Cek permission
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403);
        }

        // Ambil data relasi
        $pesertaKurbans = $kurban->pesertaKurbans;
        $distribusiKurbans = $kurban->distribusiKurbans;

        // Load View khusus PDF (Sesuai file modules/kurban/print-pdf.blade.php yang dibuat sebelumnya)
        $pdf = Pdf::loadView('modules.kurban.print-pdf', compact('kurban', 'pesertaKurbans', 'distribusiKurbans'));

        // Set ukuran kertas (A4 Portrait)
        $pdf->setPaper('a4', 'portrait');

        // Download file
        return $pdf->stream('Laporan-Kurban-' . $kurban->nomor_kurban . '.pdf');
    }
}