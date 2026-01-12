<?php

namespace App\Exports;

use App\Models\Kurban;
use Barryvdh\DomPDF\Facade\Pdf;

class KurbanReportExport
{
    protected $kurban;

    public function __construct(Kurban $kurban)
    {
        $this->kurban = $kurban;
    }

    /**
     * Generate comprehensive Kurban report PDF
     * Includes: Financial data, Participant data, Distribution details
     */
    public function generatePDF()
    {
        // Load all related data
        $kurban = $this->kurban->load([
            'pesertaKurbans' => function($query) {
                $query->orderBy('created_at', 'asc');
            },
            'distribusiKurbans' => function($query) {
                $query->orderBy('jenis_distribusi', 'asc')
                      ->orderBy('created_at', 'asc');
            },
            'createdBy',
            'updatedBy'
        ]);

        // Calculate financial summary
        $financialSummary = $this->calculateFinancialSummary($kurban);

        // Calculate distribution summary
        $distributionSummary = $this->calculateDistributionSummary($kurban);

        // Generate PDF
        $pdf = Pdf::loadView('modules.kurban.reports.pdf-laporan', [
            'kurban' => $kurban,
            'financialSummary' => $financialSummary,
            'distributionSummary' => $distributionSummary,
            'generatedAt' => now(),
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Calculate financial summary
     */
    protected function calculateFinancialSummary(Kurban $kurban): array
    {
        $pesertaKurbans = $kurban->pesertaKurbans;

        $totalPeserta = $pesertaKurbans->count();
        $totalBagian = $pesertaKurbans->sum('jumlah_bagian');
        
        $totalPembayaran = $pesertaKurbans->sum('nominal_pembayaran');
        $totalLunas = $pesertaKurbans->where('status_pembayaran', 'lunas')->sum('nominal_pembayaran');
        $totalCicilan = $pesertaKurbans->where('status_pembayaran', 'cicilan')->sum('nominal_pembayaran');
        $totalBelumLunas = $pesertaKurbans->where('status_pembayaran', 'belum_lunas')->count();

        $sisaKuota = $kurban->getSisaKuota();
        $kuotaTerisi = $kurban->getCurrentKuotaUsage();
        $persentaseKuota = $kurban->getKuotaPercentage();

        return [
            'total_peserta' => $totalPeserta,
            'total_bagian' => $totalBagian,
            'total_pembayaran' => $totalPembayaran,
            'total_lunas' => $totalLunas,
            'total_cicilan' => $totalCicilan,
            'total_belum_lunas' => $totalBelumLunas,
            'sisa_kuota' => $sisaKuota,
            'kuota_terisi' => $kuotaTerisi,
            'persentase_kuota' => $persentaseKuota,
            'harga_per_bagian' => $kurban->harga_per_bagian,
        ];
    }

    /**
     * Calculate distribution summary
     */
    protected function calculateDistributionSummary(Kurban $kurban): array
    {
        $distribusiKurbans = $kurban->distribusiKurbans;

        $totalDistribusi = $distribusiKurbans->count();
        $totalBeratDistribusi = $distribusiKurbans->sum('berat_daging');
        
        $distribusiShohibul = $distribusiKurbans->where('jenis_distribusi', 'shohibul_qurban');
        $distribusiFakir = $distribusiKurbans->where('jenis_distribusi', 'fakir_miskin');
        $distribusiYayasan = $distribusiKurbans->where('jenis_distribusi', 'yayasan');

        $sudahDistribusi = $distribusiKurbans->where('status_distribusi', 'sudah_didistribusi')->count();
        $sedangDisiapkan = $distribusiKurbans->where('status_distribusi', 'sedang_disiapkan')->count();
        $belumDistribusi = $distribusiKurbans->where('status_distribusi', 'belum_didistribusi')->count();

        // Calculate percentage of total meat distributed
        $persentaseDistribusi = 0;
        if ($kurban->total_berat_daging > 0) {
            $persentaseDistribusi = round(($totalBeratDistribusi / $kurban->total_berat_daging) * 100, 2);
        }

        return [
            'total_distribusi' => $totalDistribusi,
            'total_berat_distribusi' => $totalBeratDistribusi,
            'persentase_distribusi' => $persentaseDistribusi,
            
            'shohibul_count' => $distribusiShohibul->count(),
            'shohibul_berat' => $distribusiShohibul->sum('berat_daging'),
            
            'fakir_count' => $distribusiFakir->count(),
            'fakir_berat' => $distribusiFakir->sum('berat_daging'),
            
            'yayasan_count' => $distribusiYayasan->count(),
            'yayasan_berat' => $distribusiYayasan->sum('berat_daging'),
            
            'sudah_distribusi' => $sudahDistribusi,
            'sedang_disiapkan' => $sedangDisiapkan,
            'belum_distribusi' => $belumDistribusi,
        ];
    }

    /**
     * Download PDF file
     */
    public function download(): \Illuminate\Http\Response
    {
        $pdf = $this->generatePDF();
        $filename = 'Laporan_Kurban_' . $this->kurban->nomor_kurban . '_' . date('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Stream PDF to browser
     */
    public function stream(): \Illuminate\Http\Response
    {
        $pdf = $this->generatePDF();
        $filename = 'Laporan_Kurban_' . $this->kurban->nomor_kurban . '.pdf';
        
        return $pdf->stream($filename);
    }
}
