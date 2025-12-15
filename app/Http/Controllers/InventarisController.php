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

        $asetTerbaru = Aset::orderByDesc('aset_id')
            ->take(5)
            ->get();

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
            $diff  = $start->diff(Carbon::now());
            $parts = [];
            if ($diff->y > 0) $parts[] = $diff->y . ' Tahun';
            if ($diff->m > 0) $parts[] = $diff->m . ' Bulan';
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

    public function petugasIndex()
    {
        return view('modules.inventaris.petugas.index');
    }

    public function petugasCreate()
    {
        return view('modules.inventaris.petugas.create');
    }
}
