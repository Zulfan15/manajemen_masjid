@extends('modules.zis.layout.template')
@section('title', 'Laporan Keuangan')

@section('content')
<div class="card shadow-sm border-0 mb-4 px-3 no-print">
    <div class="card-body">
        <form action="{{ route('zis.laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" class="form-control" value="{{ $tgl_mulai }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" class="form-control" value="{{ $tgl_selesai }}">
            </div>
            <div class="col-md-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary w-200"><i class="fas fa-filter me-1"></i> Filter Data</button>
                <button type="button" onclick="window.print()" class="btn btn-secondary w-200"><i class="fas fa-print me-1"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 px-3">
    <div class="card-header bg-white py-4 text-center">
        <h4 class="fw-bold mb-0">LAPORAN KEUANGAN ZIS</h4>
        <small class="text-muted">Periode: {{ date('d-m-Y', strtotime($tgl_mulai)) }} s/d {{ date('d-m-Y', strtotime($tgl_selesai)) }}</small>
    </div>
    <div class="card-body">
        
        <h6 class="fw-bold text-success border-bottom pb-2 mb-3">A. Pemasukan (Zakat/Infaq/Sedekah)</h6>
        <table class="table table-sm table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Tgl</th>
                    <th>Kode</th>
                    <th>Muzakki</th>
                    <th>Jenis</th>
                    <th class="text-end">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasukan as $row)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($row->tanggal_transaksi)) }}</td>
                    <td>{{ $row->kode_transaksi }}</td>
                    <td>{{ $row->muzakki->nama_lengkap }}</td>
                    <td>{{ strtoupper($row->jenis_transaksi) }}</td>
                    <td class="text-end">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="fw-bold bg-success text-white">
                    <td colspan="4" class="text-end">TOTAL PEMASUKAN</td>
                    <td class="text-end">Rp {{ number_format($total_masuk, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">B. Penyaluran Dana</h6>
        <table class="table table-sm table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Tgl</th>
                    <th>Kode</th>
                    <th>Mustahiq</th>
                    <th>Keperluan</th>
                    <th class="text-end">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $row)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($row->tanggal_penyaluran)) }}</td>
                    <td>{{ $row->kode_penyaluran }}</td>
                    <td>{{ $row->mustahiq->nama_lengkap }}</td>
                    <td>{{ $row->jenis_bantuan }}</td>
                    <td class="text-end">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="fw-bold bg-danger text-white">
                    <td colspan="4" class="text-end">TOTAL PENYALURAN</td>
                    <td class="text-end">Rp {{ number_format($total_keluar, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="alert alert-secondary text-center">
            <h5 class="mb-0 fw-bold">Saldo Akhir Periode: Rp {{ number_format($total_masuk - $total_keluar, 0, ',', '.') }}</h5>
        </div>

    </div>
</div>

<style>
    @media print {
        .no-print, .sidebar, .navbar { display: none !important; }
        .main-content { margin-left: 0 !important; }
        body { background-color: white; }
    }
</style>
@endsection
