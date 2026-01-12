<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pertanggungjawaban Kurban {{ $kurban->nomor_kurban }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 16pt;
            margin-bottom: 5px;
            color: #2c5f2d;
        }
        h2 {
            font-size: 13pt;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #2c5f2d;
            border-bottom: 2px solid #2c5f2d;
            padding-bottom: 5px;
        }
        h3 {
            font-size: 11pt;
            margin-top: 15px;
            margin-bottom: 8px;
            color: #444;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 35%;
            font-weight: bold;
        }
        .data-table {
            font-size: 9pt;
        }
        .data-table th {
            background-color: #2c5f2d;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-box {
            background-color: #f0f8f0;
            border: 1px solid #2c5f2d;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .summary-box p {
            margin: 5px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            margin-top: 30px;
            font-size: 8pt;
            text-align: center;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin: 5px 0;
        }
        .progress-fill {
            height: 100%;
            background-color: #2c5f2d;
            text-align: center;
            color: white;
            font-size: 8pt;
            line-height: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <h1>LAPORAN PERTANGGUNGJAWABAN KURBAN</h1>
    <div class="header-info">
        <strong>{{ $kurban->nomor_kurban }}</strong> | 
        Dicetak: {{ $generatedAt->format('d F Y, H:i:s') }}
    </div>

    <!-- Informasi Hewan Kurban -->
    <h2>1. DATA HEWAN KURBAN</h2>
    <table class="info-table">
        <tr>
            <td>Nomor Kurban</td>
            <td>: {{ $kurban->nomor_kurban }}</td>
        </tr>
        <tr>
            <td>Jenis Hewan</td>
            <td>: {{ ucfirst($kurban->jenis_hewan) }} {{ $kurban->jenis_kelamin ? '(' . ucfirst($kurban->jenis_kelamin) . ')' : '' }}</td>
        </tr>
        <tr>
            <td>Nama Hewan</td>
            <td>: {{ $kurban->nama_hewan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Berat Badan</td>
            <td>: {{ number_format($kurban->berat_badan, 2) }} kg</td>
        </tr>
        <tr>
            <td>Kondisi Kesehatan</td>
            <td>: {{ ucfirst(str_replace('_', ' ', $kurban->kondisi_kesehatan)) }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: 
                @php
                    $statusClass = match($kurban->status) {
                        'disiapkan' => 'badge-info',
                        'siap_sembelih' => 'badge-warning',
                        'disembelih' => 'badge-success',
                        'selesai' => 'badge-success',
                        default => 'badge-info'
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ strtoupper(str_replace('_', ' ', $kurban->status)) }}</span>
            </td>
        </tr>
        <tr>
            <td>Tanggal Persiapan</td>
            <td>: {{ $kurban->tanggal_persiapan->format('d F Y') }}</td>
        </tr>
        @if($kurban->tanggal_penyembelihan)
        <tr>
            <td>Tanggal Penyembelihan</td>
            <td>: {{ $kurban->tanggal_penyembelihan->format('d F Y') }}</td>
        </tr>
        @endif
        @if($kurban->total_berat_daging)
        <tr>
            <td>Total Berat Daging</td>
            <td>: <strong>{{ number_format($kurban->total_berat_daging, 2) }} kg</strong></td>
        </tr>
        @endif
    </table>

    <!-- Data Keuangan -->
    <h2>2. DATA KEUANGAN</h2>
    <div class="summary-box">
        <table class="info-table">
            <tr>
                <td>Harga Hewan</td>
                <td>: Rp {{ number_format($kurban->harga_hewan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Operasional</td>
                <td>: Rp {{ number_format($kurban->biaya_operasional, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Biaya</strong></td>
                <td>: <strong>Rp {{ number_format($kurban->total_biaya, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Harga per Bagian (Locked)</td>
                <td>: <strong>Rp {{ number_format($kurban->harga_per_bagian, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Kuota Maksimal</td>
                <td>: {{ $kurban->max_kuota }} orang</td>
            </tr>
        </table>
    </div>

    <h3>Ringkasan Pembayaran</h3>
    <table class="info-table">
        <tr>
            <td>Total Peserta Terdaftar</td>
            <td>: {{ $financialSummary['total_peserta'] }} orang</td>
        </tr>
        <tr>
            <td>Kuota Terisi</td>
            <td>: {{ $financialSummary['kuota_terisi'] }} / {{ $kurban->max_kuota }} 
                ({{ number_format($financialSummary['persentase_kuota'], 2) }}%)
            </td>
        </tr>
        <tr>
            <td>Sisa Kuota</td>
            <td>: <strong>{{ $financialSummary['sisa_kuota'] }} slot</strong></td>
        </tr>
        <tr>
            <td>Total Pembayaran Masuk</td>
            <td>: <strong>Rp {{ number_format($financialSummary['total_pembayaran'], 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>- Lunas</td>
            <td>: Rp {{ number_format($financialSummary['total_lunas'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>- Cicilan</td>
            <td>: Rp {{ number_format($financialSummary['total_cicilan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Peserta Belum Lunas</td>
            <td>: {{ $financialSummary['total_belum_lunas'] }} orang</td>
        </tr>
    </table>

    <!-- Progress Kuota -->
    <h3>Progress Kuota</h3>
    <div class="progress-bar">
        <div class="progress-fill" style="width: {{ $financialSummary['persentase_kuota'] }}%">
            {{ number_format($financialSummary['persentase_kuota'], 1) }}% Terisi
        </div>
    </div>

    <!-- Data Shohibul Qurban -->
    <h2>3. DATA SHOHIBUL QURBAN (PESERTA)</h2>
    @if($kurban->pesertaKurbans->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama</th>
                <th style="width: 15%">Bin/Binti</th>
                <th style="width: 15%">No. HP</th>
                <th style="width: 10%">Tipe</th>
                <th style="width: 10%" class="text-right">Bagian</th>
                <th style="width: 15%" class="text-right">Pembayaran</th>
                <th style="width: 10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kurban->pesertaKurbans as $index => $peserta)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $peserta->nama_peserta }}</td>
                <td>{{ $peserta->bin_binti ?? '-' }}</td>
                <td>{{ $peserta->nomor_telepon }}</td>
                <td>{{ ucfirst($peserta->tipe_peserta) }}</td>
                <td class="text-right">{{ $peserta->jumlah_bagian }}</td>
                <td class="text-right">Rp {{ number_format($peserta->nominal_pembayaran, 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                        $statusClass = match($peserta->status_pembayaran) {
                            'lunas' => 'badge-success',
                            'cicilan' => 'badge-warning',
                            'belum_lunas' => 'badge-danger',
                            default => 'badge-info'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ strtoupper(str_replace('_', ' ', $peserta->status_pembayaran)) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 20px;">Belum ada peserta terdaftar.</p>
    @endif

    <!-- Data Distribusi Daging -->
    <h2>4. DATA DISTRIBUSI DAGING</h2>
    
    <h3>Ringkasan Distribusi</h3>
    <table class="info-table">
        <tr>
            <td>Total Distribusi</td>
            <td>: {{ $distributionSummary['total_distribusi'] }} penerima</td>
        </tr>
        @if($kurban->total_berat_daging)
        <tr>
            <td>Total Berat Daging</td>
            <td>: {{ number_format($kurban->total_berat_daging, 2) }} kg</td>
        </tr>
        <tr>
            <td>Daging Terdistribusi</td>
            <td>: {{ number_format($distributionSummary['total_berat_distribusi'], 2) }} kg 
                ({{ number_format($distributionSummary['persentase_distribusi'], 2) }}%)
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="2"><hr style="border: 0; border-top: 1px solid #ddd;"></td>
        </tr>
        <tr>
            <td>Shohibul Qurban (1/3)</td>
            <td>: {{ $distributionSummary['shohibul_count'] }} penerima 
                ({{ number_format($distributionSummary['shohibul_berat'], 2) }} kg)
            </td>
        </tr>
        <tr>
            <td>Fakir Miskin / Warga (1/3)</td>
            <td>: {{ $distributionSummary['fakir_count'] }} penerima 
                ({{ number_format($distributionSummary['fakir_berat'], 2) }} kg)
            </td>
        </tr>
        <tr>
            <td>Yayasan / Pihak Luar (1/3)</td>
            <td>: {{ $distributionSummary['yayasan_count'] }} penerima 
                ({{ number_format($distributionSummary['yayasan_berat'], 2) }} kg)
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr style="border: 0; border-top: 1px solid #ddd;"></td>
        </tr>
        <tr>
            <td>Status Distribusi:</td>
            <td></td>
        </tr>
        <tr>
            <td>- Sudah Didistribusi</td>
            <td>: {{ $distributionSummary['sudah_distribusi'] }} penerima</td>
        </tr>
        <tr>
            <td>- Sedang Disiapkan (Packing)</td>
            <td>: {{ $distributionSummary['sedang_disiapkan'] }} penerima</td>
        </tr>
        <tr>
            <td>- Belum Didistribusi</td>
            <td>: {{ $distributionSummary['belum_distribusi'] }} penerima</td>
        </tr>
    </table>

    <h3>Detail Distribusi per Penerima</h3>
    @if($kurban->distribusiKurbans->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Penerima</th>
                <th style="width: 15%">No. HP</th>
                <th style="width: 15%">Jenis Distribusi</th>
                <th style="width: 12%" class="text-right">Berat (kg)</th>
                <th style="width: 10%" class="text-center">Alokasi</th>
                <th style="width: 10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kurban->distribusiKurbans as $index => $distribusi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $distribusi->penerima_nama }}</td>
                <td>{{ $distribusi->penerima_nomor_telepon ?? '-' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $distribusi->jenis_distribusi)) }}</td>
                <td class="text-right">{{ number_format($distribusi->berat_daging, 2) }}</td>
                <td class="text-center">{{ number_format($distribusi->persentase_alokasi, 2) }}%</td>
                <td class="text-center">
                    @php
                        $statusClass = match($distribusi->status_distribusi) {
                            'sudah_didistribusi' => 'badge-success',
                            'sedang_disiapkan' => 'badge-warning',
                            'belum_didistribusi' => 'badge-danger',
                            default => 'badge-info'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ strtoupper(str_replace('_', ' ', $distribusi->status_distribusi)) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 20px;">Belum ada distribusi daging.</p>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak secara otomatis dari Sistem Manajemen Masjid</p>
        <p>&copy; {{ date('Y') }} - Semua data bersifat rahasia dan untuk keperluan pertanggungjawaban internal</p>
    </div>
</body>
</html>
