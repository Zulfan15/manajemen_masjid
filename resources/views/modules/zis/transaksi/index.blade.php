@extends('modules.zis.layout.template')
@section('title', 'Pemasukan ZIS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark">Pemasukan ZIS</h4>
    <a href="{{ route('zis.transaksi.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
        <i class="fas fa-plus-circle me-2"></i> Transaksi Baru
    </a>
</div>

@if ($message = session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ $message }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-3 py-3">Tanggal</th>
                        <th class="px-3 py-3">Kode</th>
                        <th class="px-3 py-3">Muzakki</th>
                        <th class="px-3 py-3">Jenis</th>
                        <th class="px-3 py-3 text-end">Nominal</th>
                        <th class="px-3 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $item)
                    <tr>
                        <td class="px-3 py-3">{{ date('d M Y', strtotime($item->tanggal_transaksi)) }}</td>
                        <td class="px-3 py-3"><span class="badge bg-light text-dark border">{{ $item->kode_transaksi }}</span></td>
                        <td class="px-3 py-3 fw-bold">{{ $item->muzakki->nama_lengkap }}</td>
                        <td class="px-3 py-3">
                            <span class="badge bg-info text-dark">{{ strtoupper($item->jenis_transaksi) }}</span>
                        </td>
                        <td class="px-3 py-3 text-end fw-bold text-success">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-center">
                            {{-- LOGIKA PENGAMAN: CUMA ADMIN YANG BISA HAPUS --}}
                            @if(auth('zis')->check() && auth('zis')->user()->role === 'admin')
                            <form onsubmit="return confirm('Yakin hapus transaksi ini?');" action="{{ route('zis.transaksi.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @else
                            <span class="badge bg-light text-muted border"><i class="fas fa-lock me-1"></i> Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">{{ $transaksi->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
