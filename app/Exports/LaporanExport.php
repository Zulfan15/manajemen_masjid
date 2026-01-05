<?php

namespace App\Exports;

use App\Models\Pemasukan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $filters;
    protected $rowNumber = 0;
    protected $total = 0;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Ambil data collection
     */
    public function collection()
    {
        $query = Pemasukan::query();
        
        if (isset($this->filters['start_date'])) {
            $query->whereDate('tanggal', '>=', $this->filters['start_date']);
        }
        
        if (isset($this->filters['end_date'])) {
            $query->whereDate('tanggal', '<=', $this->filters['end_date']);
        }
        
        $data = $query->orderBy('tanggal', 'asc')->get();
        $this->total = $data->sum('jumlah');
        
        return $data;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis',
            'Sumber',
            'Jumlah (Rp)',
            'Keterangan',
        ];
    }

    /**
     * Mapping data ke kolom
     */
    public function map($pemasukan): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            Carbon::parse($pemasukan->tanggal)->format('d/m/Y'),
            $pemasukan->jenis ?? '-',
            $pemasukan->sumber ?? '-',
            $pemasukan->jumlah,
            $pemasukan->keterangan ?? '-',
        ];
    }

    /**
     * Styling Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border untuk semua data
        $lastRow = $this->rowNumber + 1;
        $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Format currency untuk kolom Jumlah (E)
        $sheet->getStyle("E2:E{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Center alignment untuk kolom No dan Tanggal
        $sheet->getStyle("A2:B{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    /**
     * Nama sheet
     */
    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    /**
     * Event untuk menambah row total di akhir
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->rowNumber + 2;
                
                // Tambahkan row TOTAL
                $event->sheet->setCellValue("A{$lastRow}", '');
                $event->sheet->setCellValue("B{$lastRow}", '');
                $event->sheet->setCellValue("C{$lastRow}", '');
                $event->sheet->setCellValue("D{$lastRow}", 'TOTAL:');
                $event->sheet->setCellValue("E{$lastRow}", $this->total);
                $event->sheet->setCellValue("F{$lastRow}", '');
                
                // Style untuk row total
                $event->sheet->getStyle("A{$lastRow}:F{$lastRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E7E6E6'],
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '4472C4'],
                        ],
                    ],
                ]);
                
                $event->sheet->getStyle("E{$lastRow}")->getNumberFormat()
                    ->setFormatCode('#,##0');
                
                $event->sheet->getStyle("D{$lastRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}