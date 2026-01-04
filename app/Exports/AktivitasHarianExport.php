<?php

namespace App\Exports;

use App\Models\AktivitasHarian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AktivitasHarianExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AktivitasHarian::with('takmir')->orderBy('tanggal', 'desc')->get();
    }

    /**
     * Define headers for Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Judul',
            'Kategori',
            'Deskripsi',
            'Penanggung Jawab',
            'Lokasi',
            'Jumlah Peserta',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($aktivitas): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $aktivitas->tanggal->format('d/m/Y'),
            $aktivitas->judul,
            $aktivitas->kategori,
            $aktivitas->deskripsi,
            $aktivitas->takmir ? $aktivitas->takmir->nama : '-',
            $aktivitas->lokasi ?? '-',
            $aktivitas->jumlah_peserta ?? '-',
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
