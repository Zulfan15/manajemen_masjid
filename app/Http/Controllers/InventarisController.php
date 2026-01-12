<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\TransaksiAset;
use App\Models\JadwalPerawatan;
use App\Models\KondisiBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class InventarisController extends Controller
{
    public function index()
    {
        $totalAset = Aset::count();
        $totalJadwalPerawatan = JadwalPerawatan::count();
        $totalPerluPerbaikan = KondisiBarang::where('kondisi', 'perlu_perbaikan')->count();

        $now = Carbon::now();
        $totalTransaksiBulanIni = TransaksiAset::whereMonth('tanggal_transaksi', $now->month)
            ->whereYear('tanggal_transaksi', $now->year)
            ->count();

        $asetPerKategori = Aset::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        $asetTerbaru = Aset::orderByDesc('aset_id')
            ->take(5)
            ->get();

        $aktivitasTerbaru = TransaksiAset::with('aset', 'petugas')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.inventaris.index', [
            'totalAset' => $totalAset,
            'totalJadwalPerawatan' => $totalJadwalPerawatan,
            'totalPerluPerbaikan' => $totalPerluPerbaikan,
            'totalTransaksiBulanIni' => $totalTransaksiBulanIni,
            'asetPerKategori' => $asetPerKategori,
            'asetTerbaru' => $asetTerbaru,
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }

    public function asetIndex(Request $request)
    {
        $query = Aset::query();

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $assets = $query->orderByDesc('aset_id')
            ->paginate(10)
            ->withQueryString();

        $kategoriOptions = Aset::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('modules.inventaris.aset.index', compact('assets', 'kategoriOptions'));
    }

    public function asetShow($id)
    {
        $asset = Aset::findOrFail($id);

        $kondisiTerbaru = KondisiBarang::where('aset_id', $asset->aset_id)
            ->orderByDesc('tanggal_pemeriksaan')
            ->first();

        $riwayatPerawatan = JadwalPerawatan::where('aset_id', $asset->aset_id)
            ->orderByDesc('tanggal_jadwal')
            ->get();

        $umurText = '-';
        if (!empty($asset->tanggal_perolehan)) {
            $start = Carbon::parse($asset->tanggal_perolehan);
            $diff = $start->diff(Carbon::now());
            $parts = [];
            if ($diff->y > 0)
                $parts[] = $diff->y . ' Tahun';
            if ($diff->m > 0)
                $parts[] = $diff->m . ' Bulan';
            $umurText = count($parts) ? implode(' ', $parts) : '0 Bulan';
        }

        $qrCodeText = $asset->qr_payload ?: ('AST-' . str_pad($asset->aset_id, 3, '0', STR_PAD_LEFT));

        return view('modules.inventaris.aset.show', compact(
            'asset',
            'kondisiTerbaru',
            'riwayatPerawatan',
            'umurText',
            'qrCodeText'
        ));
    }

    public function asetCreate()
    {
        $kategoriOptions = Aset::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->sort()
            ->values();

        $kondisiOptions = [
            'baik' => 'Layak',
            'perlu_perbaikan' => 'Perbaikan',
            'rusak' => 'Rusak',
        ];

        return view('modules.inventaris.aset.create', compact('kategoriOptions', 'kondisiOptions'));
    }

    public function petugasIndex(Request $request)
    {
        $query = User::query();

        // Search (nama/username/email) - mockup search by name, username, or role
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('username', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        if (method_exists(User::class, 'roles')) {
            $query->with('roles');
        }

        $petugas = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('modules.inventaris.petugas.index', compact('petugas'));
    }

    public function asetStore(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,rusak,hilang,dibuang'],
            'harga_perolehan' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $aset = Aset::create([
            'nama_aset' => $validated['nama_aset'],
            'kategori' => $validated['kategori'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? null,
            'status' => $validated['status'],
            'harga_perolehan' => $validated['harga_perolehan'] ?? 0,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // SYNC TO FINANCE: Record Expense if price > 0
        if (($validated['harga_perolehan'] ?? 0) > 0) {
            // Find or create 'Inventaris' category
            $kategoriPengeluaran = \App\Models\KategoriPengeluaran::firstOrCreate(
                ['nama_kategori' => 'Inventaris & Aset'],
                ['deskripsi' => 'Pengeluaran untuk pembelian aset dan inventaris masjid']
            );

            \App\Models\Pengeluaran::create([
                'user_id' => auth()->id(),
                'kategori_id' => $kategoriPengeluaran->id,
                'judul_pengeluaran' => "Pembelian Aset: {$aset->nama_aset}",
                'deskripsi' => "[Auto Sync Inventaris] Pembelian aset baru kategori " . ($aset->kategori ?? 'Umum'),
                'jumlah' => $aset->harga_perolehan,
                'tanggal' => $aset->tanggal_perolehan ?? now(),
            ]);
        }

        return redirect()
            ->route('inventaris.aset.index')
            ->with('success', 'Aset berhasil ditambahkan dan tercatat di keuangan (jika ada harga).');
    }

    public function asetEdit($id)
    {
        $asset = Aset::findOrFail($id);

        $kategoriOptions = Aset::select('kategori')->distinct()->pluck('kategori')->filter()->sort()->values();

        return view('modules.inventaris.aset.edit', compact('asset', 'kategoriOptions'));
    }

    public function asetUpdate(Request $request, $id)
    {
        $asset = Aset::findOrFail($id);

        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,rusak,hilang,dibuang'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        return redirect()->route('inventaris.aset.show', $asset->aset_id)
            ->with('success', 'Aset berhasil diupdate.');
    }

    public function asetDestroy($id)
    {
        $asset = Aset::findOrFail($id);
        $asset->delete();

        return redirect()->route('inventaris.aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }

    public function petugasCreate()
    {
        $roles = Role::orderBy('name')->get();
        return view('modules.inventaris.petugas.create', compact('roles'));
    }

    // =========================================================================
    // JADWAL PERAWATAN CRUD
    // =========================================================================

    /**
     * Tampilkan daftar jadwal perawatan
     */
    public function perawatanIndex(Request $request)
    {
        $query = JadwalPerawatan::with(['aset', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan aset
        if ($request->filled('aset_id')) {
            $query->where('aset_id', $request->aset_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_jadwal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_jadwal', '<=', $request->end_date);
        }

        $jadwalPerawatan = $query->orderByDesc('tanggal_jadwal')
            ->paginate(15)
            ->withQueryString();

        $asets = Aset::orderBy('nama_aset')->get();

        return view('modules.inventaris.perawatan.index', compact('jadwalPerawatan', 'asets'));
    }

    /**
     * Tampilkan form buat jadwal perawatan baru
     */
    public function perawatanCreate()
    {
        $asets = Aset::orderBy('nama_aset')->get();
        $petugas = User::orderBy('name')->get();
        $jenisPerawatan = [
            'Pembersihan',
            'Perbaikan Ringan',
            'Perbaikan Berat',
            'Penggantian Komponen',
            'Servis Rutin',
            'Kalibrasi',
            'Lainnya',
        ];

        return view('modules.inventaris.perawatan.create', compact('asets', 'petugas', 'jenisPerawatan'));
    }

    /**
     * Simpan jadwal perawatan baru
     */
    public function perawatanStore(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => ['required', 'exists:aset,aset_id'],
            'tanggal_jadwal' => ['required', 'date'],
            'jenis_perawatan' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:dijadwalkan,selesai,dibatalkan'],
            'id_petugas' => ['nullable', 'exists:users,id'],
            'note' => ['nullable', 'string'],
        ]);

        JadwalPerawatan::create($validated);

        return redirect()
            ->route('inventaris.perawatan.index')
            ->with('success', 'Jadwal perawatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail jadwal perawatan
     */
    public function perawatanShow($id)
    {
        $jadwal = JadwalPerawatan::with(['aset', 'petugas'])->findOrFail($id);

        return view('modules.inventaris.perawatan.show', compact('jadwal'));
    }

    /**
     * Tampilkan form edit jadwal perawatan
     */
    public function perawatanEdit($id)
    {
        $jadwal = JadwalPerawatan::findOrFail($id);
        $asets = Aset::orderBy('nama_aset')->get();
        $petugas = User::orderBy('name')->get();
        $jenisPerawatan = [
            'Pembersihan',
            'Perbaikan Ringan',
            'Perbaikan Berat',
            'Penggantian Komponen',
            'Servis Rutin',
            'Kalibrasi',
            'Lainnya',
        ];

        return view('modules.inventaris.perawatan.edit', compact('jadwal', 'asets', 'petugas', 'jenisPerawatan'));
    }

    /**
     * Update jadwal perawatan
     */
    public function perawatanUpdate(Request $request, $id)
    {
        $jadwal = JadwalPerawatan::findOrFail($id);

        $validated = $request->validate([
            'aset_id' => ['required', 'exists:aset,aset_id'],
            'tanggal_jadwal' => ['required', 'date'],
            'jenis_perawatan' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:dijadwalkan,selesai,dibatalkan'],
            'id_petugas' => ['nullable', 'exists:users,id'],
            'note' => ['nullable', 'string'],
        ]);

        $jadwal->update($validated);

        return redirect()
            ->route('inventaris.perawatan.index')
            ->with('success', 'Jadwal perawatan berhasil diupdate.');
    }

    /**
     * Hapus jadwal perawatan
     */
    public function perawatanDestroy($id)
    {
        $jadwal = JadwalPerawatan::findOrFail($id);
        $jadwal->delete();

        return redirect()
            ->route('inventaris.perawatan.index')
            ->with('success', 'Jadwal perawatan berhasil dihapus.');
    }

    /**
     * Update status jadwal perawatan secara langsung
     */
    public function perawatanUpdateStatus(Request $request, $id)
    {
        $jadwal = JadwalPerawatan::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:dijadwalkan,selesai,dibatalkan'],
        ]);

        $jadwal->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status perawatan berhasil diupdate.');
    }

    // =========================================================================
    // KONDISI BARANG CRUD
    // =========================================================================

    /**
     * Tampilkan daftar pemeriksaan kondisi barang
     */
    public function kondisiIndex(Request $request)
    {
        $query = KondisiBarang::with(['aset', 'petugas']);

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan aset
        if ($request->filled('aset_id')) {
            $query->where('aset_id', $request->aset_id);
        }

        // Filter berdasarkan tanggal pemeriksaan
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->end_date);
        }

        $kondisiBarang = $query->orderByDesc('tanggal_pemeriksaan')
            ->paginate(15)
            ->withQueryString();

        $asets = Aset::orderBy('nama_aset')->get();

        return view('modules.inventaris.kondisi.index', compact('kondisiBarang', 'asets'));
    }

    /**
     * Tampilkan form pemeriksaan kondisi baru
     */
    public function kondisiCreate()
    {
        $asets = Aset::orderBy('nama_aset')->get();
        $petugas = User::orderBy('name')->get();
        $kondisiOptions = [
            'baik' => 'Baik / Layak Pakai',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'rusak_berat' => 'Rusak Berat',
        ];

        return view('modules.inventaris.kondisi.create', compact('asets', 'petugas', 'kondisiOptions'));
    }

    /**
     * Simpan pemeriksaan kondisi baru
     */
    public function kondisiStore(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => ['required', 'exists:aset,aset_id'],
            'tanggal_pemeriksaan' => ['required', 'date'],
            'kondisi' => ['required', 'in:baik,perlu_perbaikan,rusak_berat'],
            'petugas_pemeriksa' => ['nullable', 'string', 'max:255'],
            'id_petugas' => ['nullable', 'exists:users,id'],
            'catatan' => ['nullable', 'string'],
        ]);

        KondisiBarang::create($validated);

        return redirect()
            ->route('inventaris.kondisi.index')
            ->with('success', 'Pemeriksaan kondisi berhasil disimpan.');
    }

    /**
     * Tampilkan detail pemeriksaan kondisi
     */
    public function kondisiShow($id)
    {
        $kondisi = KondisiBarang::with(['aset', 'petugas'])->findOrFail($id);

        return view('modules.inventaris.kondisi.show', compact('kondisi'));
    }

    /**
     * Tampilkan form edit pemeriksaan kondisi
     */
    public function kondisiEdit($id)
    {
        $kondisi = KondisiBarang::findOrFail($id);
        $asets = Aset::orderBy('nama_aset')->get();
        $petugas = User::orderBy('name')->get();
        $kondisiOptions = [
            'baik' => 'Baik / Layak Pakai',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'rusak_berat' => 'Rusak Berat',
        ];

        return view('modules.inventaris.kondisi.edit', compact('kondisi', 'asets', 'petugas', 'kondisiOptions'));
    }

    /**
     * Update pemeriksaan kondisi
     */
    public function kondisiUpdate(Request $request, $id)
    {
        $kondisi = KondisiBarang::findOrFail($id);

        $validated = $request->validate([
            'aset_id' => ['required', 'exists:aset,aset_id'],
            'tanggal_pemeriksaan' => ['required', 'date'],
            'kondisi' => ['required', 'in:baik,perlu_perbaikan,rusak_berat'],
            'petugas_pemeriksa' => ['nullable', 'string', 'max:255'],
            'id_petugas' => ['nullable', 'exists:users,id'],
            'catatan' => ['nullable', 'string'],
        ]);

        $kondisi->update($validated);

        return redirect()
            ->route('inventaris.kondisi.index')
            ->with('success', 'Pemeriksaan kondisi berhasil diupdate.');
    }

    /**
     * Hapus pemeriksaan kondisi
     */
    public function kondisiDestroy($id)
    {
        $kondisi = KondisiBarang::findOrFail($id);
        $kondisi->delete();

        return redirect()
            ->route('inventaris.kondisi.index')
            ->with('success', 'Pemeriksaan kondisi berhasil dihapus.');
    }
}
