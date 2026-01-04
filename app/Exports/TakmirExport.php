<?php

namespace App\Exports;

use App\Models\Takmir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TakmirExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Takmir::orderBy('created_at', 'desc')->get();
    }

    /**
     * Define headers for Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Jabatan',
            'Email',
            'Telepon',
            'Alamat',
            'Periode Mulai',
            'Periode Akhir',
            'Status',
            'Keterangan',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($takmir): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $takmir->nama,
            $takmir->jabatan,
            $takmir->email ?? '-',
            $takmir->phone ?? '-',
            $takmir->alamat ?? '-',
            $takmir->periode_mulai->format('d/m/Y'),
            $takmir->periode_akhir->format('d/m/Y'),
            ucfirst($takmir->status),
            $takmir->keterangan ?? '-',
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
