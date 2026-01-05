<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Masjid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4472C4;
        }
        
        .header h1 {
            font-size: 20pt;
            color: #4472C4;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 16pt;
            color: #666;
            font-weight: normal;
            margin-bottom: 3px;
        }
        
        .header .period {
            font-size: 10pt;
            color: #999;
            margin-top: 5px;
        }
        
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #4472C4;
        }
        
        .info-section table {
            width: 100%;
        }
        
        .info-section td {
            padding: 3px 0;
            font-size: 10pt;
        }
        
        .info-section td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        table.data-table thead {
            background-color: #4472C4;
            color: white;
        }
        
        table.data-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
            border: 1px solid #ddd;
        }
        
        table.data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9.5pt;
        }
        
        table.data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            background-color: #e7e6e6 !important;
            font-weight: bold;
            font-size: 11pt;
        }
        
        .total-row td {
            border-top: 2px solid #4472C4;
            padding: 12px 8px !important;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
        
        /* Column widths */
        .col-no { width: 5%; }
        .col-date { width: 12%; }
        .col-type { width: 15%; }
        .col-source { width: 15%; }
        .col-amount { width: 15%; }
        .col-desc { width: 38%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🕌 MANAJEMEN MASJID</h1>
        <h2>Laporan Keuangan</h2>
        <div class="period">
            Periode: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} 
            s/d 
            {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
        </div>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td>: {{ auth()->user()->name ?? 'Admin' }}</td>
            </tr>
            <tr>
                <td>Total Transaksi</td>
                <td>: {{ number_format($pemasukan->count(), 0, ',', '.') }} transaksi</td>
            </tr>
            <tr>
                <td>Total Pemasukan</td>
                <td>: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($pemasukan->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-no text-center">No</th>
                    <th class="col-date">Tanggal</th>
                    <th class="col-type">Jenis</th>
                    <th class="col-source">Sumber</th>
                    <th class="col-amount text-right">Jumlah (Rp)</th>
                    <th class="col-desc">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasukan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->jenis ?? '-' }}</td>
                    <td>{{ $item->sumber ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
                
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL PEMASUKAN:</td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">
            Tidak ada data pemasukan untuk periode yang dipilih.
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Manajemen Masjid</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>