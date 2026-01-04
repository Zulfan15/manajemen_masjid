<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Kegiatan;

class LaporanController extends Controller
{
    /**
     * Kolom tanggal kegiatan (SESUAI DATABASE)
     */
    private string $kolomTanggalKegiatan = 'tanggal_mulai';

    /**
     * Ambil tahun terbaru yang ADA data kegiatannya
     */
    private function getDefaultTahunKegiatan()
    {
        return Kegiatan::selectRaw(
                "YEAR({$this->kolomTanggalKegiatan}) as tahun"
            )
            ->orderByDesc('tahun')
            ->value('tahun') ?? date('Y');
    }

    /**
     * ===============================
     * HALAMAN LAPORAN
     * ===============================
     */
    public function index(Request $request)
    {
        /* ===============================
         * LAPORAN KEUANGAN
         * =============================== */
        $tahun = $request->get('tahun', date('Y'));

        // TOTAL PENGELUARAN
        $totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // PENGELUARAN PER BULAN
        $pengeluaranPerBulan = Pengeluaran::selectRaw(
                'MONTH(tanggal) as bulan, SUM(jumlah) as total'
            )
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        // CHART JAN–DES
        $pengeluaranChart = array_fill(0, 12, 0);
        foreach ($pengeluaranPerBulan as $bulan => $total) {
            $pengeluaranChart[$bulan - 1] = (int) $total;
        }

        // TABEL ANGGARAN
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        $anggaran = [];

        foreach ($namaBulan as $i => $bulan) {
            $pemasukan = 0; // KEPUTUSAN FINAL
            $pengeluaran = $pengeluaranChart[$i];

            $anggaran[] = [
                'bulan' => $bulan,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'sisa' => $pemasukan - $pengeluaran, // bisa negatif
            ];
        }

        /* ===============================
         * LAPORAN KEGIATAN
         * =============================== */
        $tahunKegiatan = Kegiatan::selectRaw(
                "YEAR({$this->kolomTanggalKegiatan}) as tahun"
            )
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('modules.laporan.index', [
            'tahun' => $tahun,
            'totalPengeluaran' => $totalPengeluaran,
            'pengeluaranChart' => $pengeluaranChart,
            'anggaran' => $anggaran,
            'tahunKegiatan' => $tahunKegiatan
        ]);
    }

    /**
     * ===============================
     * DATA KEUANGAN (AJAX)
     * ===============================
     */
    public function getDataKeuangan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $data = Pengeluaran::selectRaw(
                'MONTH(tanggal) as bulan, SUM(jumlah) as total'
            )
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        $chart = array_fill(0, 12, 0);
        foreach ($data as $bulan => $total) {
            $chart[$bulan - 1] = (int) $total;
        }

        return response()->json([
            'tahun' => $tahun,
            'chart' => $chart
        ]);
    }

    /**
     * ===============================
     * DATA KEGIATAN BULANAN
     * ===============================
     */
    public function getDataKegiatanBulanan(Request $request)
    {
        $tahun = $request->get('tahun', $this->getDefaultTahunKegiatan());

        $data = Kegiatan::selectRaw(
                "MONTH({$this->kolomTanggalKegiatan}) as bulan, COUNT(id) as total"
            )
            ->whereYear($this->kolomTanggalKegiatan, $tahun)
            ->groupByRaw("MONTH({$this->kolomTanggalKegiatan})")
            ->pluck('total', 'bulan');

        $chart = array_fill(0, 12, 0);
        foreach ($data as $bulan => $total) {
            $chart[$bulan - 1] = (int) $total;
        }

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        $tabel = [];

        foreach ($namaBulan as $i => $bulan) {
            $tabel[] = [
                'bulan' => $bulan,
                'jumlah' => $chart[$i],
            ];
        }

        return response()->json([
            'tahun' => $tahun,
            'chart' => $chart,
            'tabel' => $tabel
        ]);
    }
}
