<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\TransaksiAset;
use App\Models\JadwalPerawatan;
use App\Models\KondisiBarang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarisController extends Controller
{
    public function index()
    {
        // --- KARTU STATISTIK DASHBOARD ---
        $totalAset = Aset::count();
        $totalJadwalPerawatan = JadwalPerawatan::count();
        $totalPerluPerbaikan = KondisiBarang::where('kondisi', 'perlu_perbaikan')->count();

        $now = Carbon::now();
        $totalTransaksiBulanIni = TransaksiAset::whereMonth('tanggal_transaksi', $now->month)
            ->whereYear('tanggal_transaksi', $now->year)
            ->count();

        // Jumlah aset per kategori
        $asetPerKategori = Aset::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        // Aset terbaru (pakai primary key aset_id)
        $asetTerbaru = Aset::orderByDesc('aset_id')
            ->take(5)
            ->get();

        // Aktivitas transaksi terbaru
        $aktivitasTerbaru = TransaksiAset::with('aset', 'petugas')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.inventaris.index', [
            'totalAset'              => $totalAset,
            'totalJadwalPerawatan'   => $totalJadwalPerawatan,
            'totalPerluPerbaikan'    => $totalPerluPerbaikan,
            'totalTransaksiBulanIni' => $totalTransaksiBulanIni,
            'asetPerKategori'        => $asetPerKategori,
            'asetTerbaru'            => $asetTerbaru,
            'aktivitasTerbaru'       => $aktivitasTerbaru,
        ]);
    }

    /**
     * LIST DAFTAR ASET
     */
    public function asetIndex(Request $request)
    {
        $query = Aset::query();

        // search nama aset
        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }

        // filter kategori (kolomnya ADA di tabel aset)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // NOTE:
        // tabel `aset` TIDAK punya kolom `kondisi`, jadi filter ini
        // kita matikan dulu sampai nanti pakai join ke `kondisi_barang`.
        /*
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        */

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

    /**
     * DETAIL ASET
     */
    public function asetShow($id)
    {
        // pakai primary key aset_id (sudah di-set di model)
        $asset = Aset::findOrFail($id);

        return view('modules.inventaris.aset.show', compact('asset'));
    }

    /**
     * FORM TAMBAH ASET BARU
     */
    public function asetCreate()
    {
        return view('modules.inventaris.aset.create');
    }

    /**
     * LIST PETUGAS INVENTARIS
     */
    public function petugasIndex()
    {
        return view('modules.inventaris.petugas.index');
    }

    /**
     * FORM TAMBAH PETUGAS BARU
     */
    public function petugasCreate()
    {
        return view('modules.inventaris.petugas.create');
    }
}
