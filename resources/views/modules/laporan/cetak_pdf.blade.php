<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ $periode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #2d5016;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            color: #2d5016;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }

        .header p {
            font-size: 10px;
            color: #888;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background: #2d5016;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }

        .summary-box {
            border: 2px solid #2d5016;
            padding: 15px;
            margin-top: 20px;
        }

        .summary-box h3 {
            color: #2d5016;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-table {
            width: 50%;
            margin: 0 auto;
        }

        .summary-table td {
            padding: 8px 12px;
            border: none;
            border-bottom: 1px solid #ddd;
        }

        .summary-table .label {
            font-weight: bold;
            width: 60%;
        }

        .summary-table .value {
            text-align: right;
            font-size: 12px;
        }

        .positive {
            color: #2d5016;
        }

        .negative {
            color: #dc2626;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN MASJID</h1>
        <h2>Periode: {{ $periode }}</h2>
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    {{-- BAGIAN PEMASUKAN --}}
    <div class="section">
        <div class="section-title">
            <span>📥 PEMASUKAN</span>
        </div>

        @if($pemasukan->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 12%">Tanggal</th>
                        <th style="width: 15%">Jenis</th>
                        <th style="width: 20%">Sumber</th>
                        <th style="width: 18%" class="text-right">Jumlah</th>
                        <th style="width: 30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemasukan as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->sumber }}</td>
                            <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" class="text-right">TOTAL PEMASUKAN</td>
                        <td class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="no-data">Tidak ada data pemasukan pada periode ini.</p>
        @endif
    </div>

    {{-- BAGIAN PENGELUARAN --}}
    <div class="section">
        <div class="section-title">
            <span>📤 PENGELUARAN</span>
        </div>

        @if($pengeluaran->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 12%">Tanggal</th>
                        <th style="width: 15%">Kategori</th>
                        <th style="width: 20%">Judul</th>
                        <th style="width: 18%" class="text-right">Jumlah</th>
                        <th style="width: 30%">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengeluaran as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->judul_pengeluaran }}</td>
                            <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" class="text-right">TOTAL PENGELUARAN</td>
                        <td class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="no-data">Tidak ada data pengeluaran pada periode ini.</p>
        @endif
    </div>

    {{-- RINGKASAN --}}
    <div class="summary-box">
        <h3 class="text-center">RINGKASAN KEUANGAN</h3>
        <table class="summary-table">
            <tr>
                <td class="label">Total Pemasukan</td>
                <td class="value positive">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pengeluaran</td>
                <td class="value negative">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid #2d5016;">
                <td class="label" style="font-size: 14px;">SALDO</td>
                <td class="value {{ $saldo >= 0 ? 'positive' : 'negative' }}"
                    style="font-size: 14px; font-weight: bold;">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Manajemen Masjid</p>
        <p>{{ config('app.name', 'Masjid App') }} - {{ date('Y') }}</p>
    </div>
</body>

</html>