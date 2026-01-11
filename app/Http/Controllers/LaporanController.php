<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        $tahun = $request->get('tahun', date('Y'));

        // PEMASUKAN PER BULAN (verified only)
        $pemasukanPerBulan = Pemasukan::verified()
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        // PENGELUARAN PER BULAN
        $pengeluaranPerBulan = Pengeluaran::selectRaw(
            'MONTH(tanggal) as bulan, SUM(jumlah) as total'
        )
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        // Chart arrays
        $pemasukanChart = array_fill(0, 12, 0);
        $pengeluaranChart = array_fill(0, 12, 0);

        foreach ($pemasukanPerBulan as $bulan => $total) {
            $pemasukanChart[$bulan - 1] = (int) $total;
        }

        foreach ($pengeluaranPerBulan as $bulan => $total) {
            $pengeluaranChart[$bulan - 1] = (int) $total;
        }

        // TOTAL
        $totalPemasukan = array_sum($pemasukanChart);
        $totalPengeluaran = array_sum($pengeluaranChart);
        $saldo = $totalPemasukan - $totalPengeluaran;

        // TABEL ANGGARAN BULANAN
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $anggaran = [];

        $saldoKumulatif = 0;
        foreach ($namaBulan as $i => $bulan) {
            $pemasukan = $pemasukanChart[$i];
            $pengeluaran = $pengeluaranChart[$i];
            $saldoBulan = $pemasukan - $pengeluaran;
            $saldoKumulatif += $saldoBulan;

            $anggaran[] = [
                'bulan' => $bulan,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldoBulan,
                'saldo_kumulatif' => $saldoKumulatif,
            ];
        }

        // Tahun-tahun yang tersedia
        $tahunTersedia = collect();

        $tahunPemasukan = Pemasukan::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->pluck('tahun');

        $tahunPengeluaran = Pengeluaran::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->pluck('tahun');

        $tahunTersedia = $tahunPemasukan->merge($tahunPengeluaran)
            ->unique()
            ->sortDesc();

        if ($tahunTersedia->isEmpty()) {
            $tahunTersedia = collect([date('Y')]);
        }

        // LAPORAN KEGIATAN
        $tahunKegiatan = Kegiatan::selectRaw(
            "YEAR({$this->kolomTanggalKegiatan}) as tahun"
        )
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('modules.laporan.index', [
            'tahun' => $tahun,
            'tahunTersedia' => $tahunTersedia,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'pemasukanChart' => $pemasukanChart,
            'pengeluaranChart' => $pengeluaranChart,
            'anggaran' => $anggaran,
            'tahunKegiatan' => $tahunKegiatan
        ]);
    }

    /**
     * ===============================
     * EXPORT LAPORAN PDF
     * ===============================
     */
    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan');

        // Get data
        $query = Pemasukan::verified()->whereYear('tanggal', $tahun);
        $queryPengeluaran = Pengeluaran::whereYear('tanggal', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
            $queryPengeluaran->whereMonth('tanggal', $bulan);
            $periode = Carbon::create($tahun, $bulan)->format('F Y');
        } else {
            $periode = "Tahun $tahun";
        }

        $pemasukan = $query->orderBy('tanggal')->get();
        $pengeluaran = $queryPengeluaran->orderBy('tanggal')->get();

        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $data = [
            'periode' => $periode,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'tanggalCetak' => Carbon::now()->format('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('modules.laporan.cetak_pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan-Keuangan-{$periode}.pdf");
    }

    /**
     * ===============================
     * EXPORT LAPORAN EXCEL
     * ===============================
     */
    public function exportExcel(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan');

        // Get data
        $query = Pemasukan::verified()->whereYear('tanggal', $tahun);
        $queryPengeluaran = Pengeluaran::with('kategori')->whereYear('tanggal', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
            $queryPengeluaran->whereMonth('tanggal', $bulan);
            $periode = Carbon::create($tahun, $bulan)->format('F-Y');
        } else {
            $periode = "Tahun-$tahun";
        }

        $pemasukan = $query->orderBy('tanggal')->get();
        $pengeluaran = $queryPengeluaran->orderBy('tanggal')->get();

        // Create CSV content
        $filename = "Laporan-Keuangan-{$periode}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pemasukan, $pengeluaran) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Pemasukan
            fputcsv($file, ['=== LAPORAN PEMASUKAN ===']);
            fputcsv($file, ['No', 'Tanggal', 'Jenis', 'Sumber', 'Jumlah', 'Keterangan']);

            $no = 1;
            foreach ($pemasukan as $item) {
                fputcsv($file, [
                    $no++,
                    $item->tanggal->format('d/m/Y'),
                    $item->jenis,
                    $item->sumber,
                    $item->jumlah,
                    $item->keterangan ?? '-',
                ]);
            }

            fputcsv($file, ['', '', '', 'TOTAL PEMASUKAN', $pemasukan->sum('jumlah'), '']);
            fputcsv($file, []);

            // Header Pengeluaran
            fputcsv($file, ['=== LAPORAN PENGELUARAN ===']);
            fputcsv($file, ['No', 'Tanggal', 'Kategori', 'Judul', 'Jumlah', 'Deskripsi']);

            $no = 1;
            foreach ($pengeluaran as $item) {
                fputcsv($file, [
                    $no++,
                    Carbon::parse($item->tanggal)->format('d/m/Y'),
                    $item->kategori->nama_kategori ?? '-',
                    $item->judul_pengeluaran,
                    $item->jumlah,
                    $item->deskripsi ?? '-',
                ]);
            }

            fputcsv($file, ['', '', '', 'TOTAL PENGELUARAN', $pengeluaran->sum('jumlah'), '']);
            fputcsv($file, []);

            // Summary
            fputcsv($file, ['=== RINGKASAN ===']);
            fputcsv($file, ['Total Pemasukan', $pemasukan->sum('jumlah')]);
            fputcsv($file, ['Total Pengeluaran', $pengeluaran->sum('jumlah')]);
            fputcsv($file, ['Saldo', $pemasukan->sum('jumlah') - $pengeluaran->sum('jumlah')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * ===============================
     * DATA KEUANGAN (AJAX)
     * ===============================
     */
    public function getDataKeuangan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Pemasukan
        $dataPemasukan = Pemasukan::verified()
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        // Pengeluaran
        $dataPengeluaran = Pengeluaran::selectRaw(
            'MONTH(tanggal) as bulan, SUM(jumlah) as total'
        )
            ->whereYear('tanggal', $tahun)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        $chartPemasukan = array_fill(0, 12, 0);
        $chartPengeluaran = array_fill(0, 12, 0);

        foreach ($dataPemasukan as $bulan => $total) {
            $chartPemasukan[$bulan - 1] = (int) $total;
        }

        foreach ($dataPengeluaran as $bulan => $total) {
            $chartPengeluaran[$bulan - 1] = (int) $total;
        }

        return response()->json([
            'tahun' => $tahun,
            'pemasukan' => $chartPemasukan,
            'pengeluaran' => $chartPengeluaran,
            'totalPemasukan' => array_sum($chartPemasukan),
            'totalPengeluaran' => array_sum($chartPengeluaran),
            'saldo' => array_sum($chartPemasukan) - array_sum($chartPengeluaran),
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

        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
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
