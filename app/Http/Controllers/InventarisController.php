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

        $asetTerbaru = Aset::orderByDesc('created_at')
            ->take(5)
            ->get();

        $aktivitasTerbaru = TransaksiAset::with('aset', 'petugas')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.inventaris.index', [
            'totalAset'             => $totalAset,
            'totalJadwalPerawatan'  => $totalJadwalPerawatan,
            'totalPerluPerbaikan'   => $totalPerluPerbaikan,
            'totalTransaksiBulanIni' => $totalTransaksiBulanIni,
            'asetPerKategori'       => $asetPerKategori,
            'asetTerbaru'           => $asetTerbaru,
            'aktivitasTerbaru'      => $aktivitasTerbaru,
        ]);
    }
}
