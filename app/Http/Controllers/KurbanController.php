<?php

namespace App\Http\Controllers;

use App\Models\Kurban;
use App\Models\PesertaKurban;
use App\Models\DistribusiKurban;
use App\Services\AuthService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'nama_hewan' => 'nullable|string|max:100',
            'berat_badan' => 'required|numeric|min:0.01',
            'kondisi_kesehatan' => 'required|in:sehat,cacat_ringan,cacat_berat',
            'tanggal_persiapan' => 'required|date',
            'tanggal_penyembelihan' => 'nullable|date|after_or_equal:tanggal_persiapan',
            'harga_hewan' => 'required|numeric|min:0',
            'biaya_operasional' => 'nullable|numeric|min:0',
            'status' => 'required|in:disiapkan,siap_sembelih,disembelih,didistribusi,selesai',
            'catatan' => 'nullable|string',
        ]);

        $validated['biaya_operasional'] = $validated['biaya_operasional'] ?? 0;
        $validated['total_biaya'] = $validated['harga_hewan'] + $validated['biaya_operasional'];
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
        // 1. Check permission
        if (!$this->authService->hasPermission('kurban.create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah peserta.');
        }

        // 2. Validasi Input Dasar
        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:100',
            'nomor_identitas' => 'nullable|string|max:20',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tipe_peserta' => 'required|in:perorangan,keluarga',
            'jumlah_jiwa' => 'required|integer|min:1',
            'jumlah_bagian' => 'required|numeric|min:0.01',
            // Nominal kita hitung sendiri, jadi validasinya cukup numeric saja (tidak wajib required dari form)
            'nominal_pembayaran' => 'nullable|numeric', 
            'status_pembayaran' => 'required|in:belum_lunas,lunas,cicilan',
            'catatan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // ==========================================
        // 3. BUSINESS LOGIC: VALIDASI KUOTA & ATURAN
        // ==========================================

        // Aturan A: Kambing/Domba Wajib 1 Bagian
        if (in_array($kurban->jenis_hewan, ['kambing', 'domba']) && (float) $request->jumlah_bagian !== 1.0) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_bagian' => 'Untuk Kambing/Domba, jumlah bagian harus 1 (satu ekor utuh).']);
        }

        // Aturan B: Cek Ketersediaan Slot (Kuota)
        $maxBagian = ($kurban->jenis_hewan === 'sapi') ? 7.00 : 1.00;
        $currentUsed = $kurban->pesertaKurbans()->sum('jumlah_bagian');
        $newTotal = $currentUsed + $request->jumlah_bagian;

        if ($newTotal > $maxBagian) {
            $sisa = $maxBagian - $currentUsed;
            return back()
                ->withInput()
                ->withErrors(['jumlah_bagian' => "Kuota hewan tidak cukup. Sisa slot hanya: " . (float)$sisa . " bagian."]);
        }

        // ==========================================
        // 4. LOGIC HARGA OTOMATIS (SERVER SIDE)
        // ==========================================
        // Kita hitung ulang harga di sini agar aman dari manipulasi input
        $hargaPerBagian = $kurban->total_biaya / $maxBagian;
        $hargaPasti = round($validated['jumlah_bagian'] * $hargaPerBagian); // Bulatkan ke rupiah terdekat
        
        // Timpa nilai nominal_pembayaran dengan hasil hitungan server
        $validated['nominal_pembayaran'] = $hargaPasti;

        // ==========================================
        // 5. PENYIAPAN DATA & SIMPAN
        // ==========================================

        $validated['kurban_id'] = $kurban->id;
        $validated['created_by'] = auth()->id();

        if ($request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
        }

        if ($validated['status_pembayaran'] === 'lunas') {
            $validated['tanggal_pembayaran'] = now()->toDateString();
        }

        $peserta = PesertaKurban::create($validated);

        // 6. Log activity
        $this->activityLogService->log(
            'peserta_kurban_create',
            'kurban',
            "Peserta '{$peserta->nama_peserta}' ditambahkan (Tagihan: Rp " . number_format($hargaPasti, 0, ',', '.') . ")",
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
        // 1. Check permission
        if (!$this->authService->hasPermission('kurban.update')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah peserta.');
        }

        // 2. Validasi Input
        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:100',
            'nomor_identitas' => 'nullable|string|max:20',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tipe_peserta' => 'required|in:perorangan,keluarga',
            'jumlah_jiwa' => 'required|integer|min:1',
            'jumlah_bagian' => 'required|numeric|min:0.01',
            'nominal_pembayaran' => 'nullable|numeric', // Tidak wajib input user, akan dihitung ulang
            'status_pembayaran' => 'required|in:belum_lunas,lunas,cicilan',
            'catatan' => 'nullable|string',
        ]);

        // ==========================================
        // 3. BUSINESS LOGIC: VALIDASI KUOTA & ATURAN
        // ==========================================

        // Aturan A: Kambing/Domba Wajib 1 Bagian
        if (in_array($kurban->jenis_hewan, ['kambing', 'domba']) && (float) $request->jumlah_bagian !== 1.0) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_bagian' => 'Untuk Kambing/Domba, jumlah bagian harus 1 (satu ekor utuh).']);
        }

        // Aturan B: Cek Ketersediaan Slot (Logic Update)
        $maxBagian = ($kurban->jenis_hewan === 'sapi') ? 7.00 : 1.00;
        $currentTotalInDB = $kurban->pesertaKurbans()->sum('jumlah_bagian');
        
        // (Total di DB) - (Punya Dia yang LAMA) + (Punya Dia yang BARU)
        $simulasiTotal = $currentTotalInDB - $peserta->jumlah_bagian + $request->jumlah_bagian;

        if ($simulasiTotal > $maxBagian) {
            $sisaSebenarnya = $maxBagian - ($currentTotalInDB - $peserta->jumlah_bagian);
            return back()
                ->withInput()
                ->withErrors(['jumlah_bagian' => "Update gagal. Kuota hewan akan berlebih. Maksimal update untuk peserta ini: " . (float)$sisaSebenarnya]);
        }

        // ==========================================
        // 4. LOGIC HARGA OTOMATIS (SERVER SIDE)
        // ==========================================
        $hargaPerBagian = $kurban->total_biaya / $maxBagian;
        $hargaPasti = round($validated['jumlah_bagian'] * $hargaPerBagian);
        
        $validated['nominal_pembayaran'] = $hargaPasti;

        // Auto set tanggal pembayaran jika status berubah jadi lunas
        if ($validated['status_pembayaran'] === 'lunas' && is_null($peserta->tanggal_pembayaran)) {
            $validated['tanggal_pembayaran'] = now()->toDateString();
        }

        // ==========================================
        // 5. UPDATE DATA
        // ==========================================

        $validated['updated_by'] = auth()->id();
        $peserta->update($validated);

        // 6. Log activity
        $this->activityLogService->log(
            'peserta_kurban_update',
            'kurban',
            "Peserta '{$peserta->nama_peserta}' diubah (Tagihan Update: Rp " . number_format($hargaPasti, 0, ',', '.') . ")",
            ['peserta_kurban_id' => $peserta->id, 'kurban_id' => $kurban->id]
        );

        return redirect()->route('kurban.show', $kurban)
            ->with('success', 'Peserta kurban berhasil diubah.');
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
            'jenis_distribusi' => 'required|in:keluarga_peserta,fakir_miskin,saudara,kerabat,lainnya',
            'catatan' => 'nullable|string',
        ]);

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
            'jenis_distribusi' => 'required|in:keluarga_peserta,fakir_miskin,saudara,kerabat,lainnya',
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

    /**
     * Export Laporan PDF
     */
    public function exportPdf(Kurban $kurban)
    {
        // Cek permission (Opsional, sesuaikan)
        if (!$this->authService->hasPermission('kurban.view')) {
            abort(403);
        }

        // Ambil data relasi
        $pesertaKurbans = $kurban->pesertaKurbans;
        $distribusiKurbans = $kurban->distribusiKurbans;

        // Load View khusus PDF
        $pdf = Pdf::loadView('modules.kurban.print-pdf', compact('kurban', 'pesertaKurbans', 'distribusiKurbans'));
        
        // Set ukuran kertas (A4 Portrait)
        $pdf->setPaper('a4', 'portrait');

        // Download file
        return $pdf->stream('Laporan-Kurban-' . $kurban->nomor_kurban . '.pdf');
    }
}
