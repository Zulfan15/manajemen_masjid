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

        // Aset terbaru (pakai kolom yang ADA, misal kode_aset)
        // Kalau kamu punya kolom lain yang lebih cocok (misal created_at),
        // ganti 'kode_aset' di sini dengan nama kolom itu.
        $asetTerbaru = Aset::orderByDesc('kode_aset')
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
    public function asetIndex()
    {
        // Ambil data beneran dari tabel `aset`
        $assets = Aset::select([
            'kode_aset',
            'nama_aset',
            'kategori',
            'lokasi',
            'kondisi',
            'jumlah',
            'status',
        ])
            ->orderByDesc('kode_aset')   // <-- ganti dari id ke kode_aset
            ->paginate(10);

        return view('modules.inventaris.aset.index', compact('assets'));
    }

    /**
     * DETAIL ASET
     */
    public function asetShow($kode_aset)
    {
        // cari berdasarkan kode_aset karena tidak ada kolom id
        $asset = Aset::where('kode_aset', $kode_aset)->firstOrFail();

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
