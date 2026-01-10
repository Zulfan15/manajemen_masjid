<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kurban {{ $kurban->nomor_kurban }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; background-color: #eee; padding: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        
        .info-table td { border: none; padding: 3px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .ttd-area { margin-top: 50px; width: 100%; }
        .ttd-box { width: 30%; float: right; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DKM Masjid Al-Hidayah</h1> <p>Jl. Merdeka No. 123, Kota Bandung, Jawa Barat</p>
        <p>Telp: (022) 1234567 | Email: info@masjid-alhidayah.com</p>
    </div>

    <h2 style="text-align: center; margin-bottom: 20px;">LAPORAN PERTANGGUNGJAWABAN HEWAN KURBAN</h2>

    <div class="section-title">A. DATA HEWAN</div>
    <table class="info-table">
        <tr>
            <td width="20%">Nomor Kurban</td>
            <td width="30%">: <strong>{{ $kurban->nomor_kurban }}</strong></td>
            <td width="20%">Status</td>
            <td width="30%">: {{ ucfirst($kurban->status) }}</td>
        </tr>
        <tr>
            <td>Jenis Hewan</td>
            <td>: {{ ucfirst($kurban->jenis_hewan) }}</td>
            <td>Tanggal Sembelih</td>
            <td>: {{ $kurban->tanggal_penyembelihan ? $kurban->tanggal_penyembelihan->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Nama Hewan</td>
            <td>: {{ $kurban->nama_hewan }}</td>
            <td>Berat Badan</td>
            <td>: {{ number_format($kurban->berat_badan, 2) }} kg</td>
        </tr>
    </table>

    <div class="section-title">B. LAPORAN KEUANGAN</div>
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Harga Pembelian Hewan</td>
                <td class="text-right">Rp {{ number_format($kurban->harga_hewan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Operasional</td>
                <td class="text-right">Rp {{ number_format($kurban->biaya_operasional, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>TOTAL BIAYA</th>
                <th class="text-right">Rp {{ number_format($kurban->total_biaya, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <div class="section-title">C. DATA PESERTA (SHOHIBUL QURBAN)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peserta</th>
                <th>Tipe</th>
                <th class="text-center">Bagian</th>
                <th class="text-center">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesertaKurbans as $index => $peserta)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $peserta->nama_peserta }}</td>
                <td>{{ ucfirst($peserta->tipe_peserta) }}</td>
                <td class="text-center">{{ number_format($peserta->jumlah_bagian, 2) }}</td>
                <td class="text-center">{{ ucfirst(str_replace('_', ' ', $peserta->status_pembayaran)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D. DISTRIBUSI DAGING</div>
    <table>
        <thead>
            <tr>
                <th>Penerima</th>
                <th>Kategori</th>
                <th class="text-center">Berat (Kg)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBerat = 0; @endphp
            @foreach($distribusiKurbans as $dist)
            <tr>
                <td>{{ $dist->penerima_nama }}</td>
                <td>{{ $dist->getJenisDistribusiLabel() }}</td>
                <td class="text-center">{{ number_format($dist->berat_daging, 2) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $dist->status_distribusi)) }}</td>
            </tr>
            @php $totalBerat += $dist->berat_daging; @endphp
            @endforeach
            <tr>
                <th colspan="2" class="text-right">Total Terdistribusi</th>
                <th class="text-center">{{ number_format($totalBerat, 2) }} Kg</th>
                <th></th>
            </tr>
        </tbody>
    </table>

    <div class="ttd-area">
        <div class="ttd-box">
            <p>Bandung, {{ now()->format('d M Y') }}</p>
            <p>Ketua Panitia,</p>
            <br><br><br>
            <p><strong>( _________________ )</strong></p>
        </div>
    </div>

</body>
</html>