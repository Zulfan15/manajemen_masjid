@extends('modules.zis.layout.template')
@section('title', 'Penyaluran Dana')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Riwayat Penyaluran</h4>
        <small class="text-muted">Daftar penyaluran dana zakat kepada Mustahiq</small>
    </div>
    <a href="{{ route('zis.penyaluran.create') }}" class="btn btn-danger px-4 fw-bold shadow-sm">
        <i class="fas fa-paper-plane me-2"></i> Salurkan Dana
    </a>
</div>

@if ($message = session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="fas fa-check-circle me-2 fa-lg"></i>
        <div>{{ $message }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tanggal & Kode</th>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Mustahiq</th>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Keperluan</th>
                        <th class="px-4 py-3 text-end text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nominal</th>
                        <th class="px-4 py-3 text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penyaluran as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ date('d M Y', strtotime($item->tanggal_penyaluran)) }}</span>
                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $item->kode_penyaluran }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-weight: bold;">
                                    {{ substr($item->mustahiq->nama_lengkap, 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $item->mustahiq->nama_lengkap }}</span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill mt-1" style="width: fit-content; font-size: 0.65rem;">
                                        {{ $item->mustahiq->kategori }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-dark fw-medium">{{ $item->jenis_bantuan }}</span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <span class="fw-bold text-danger">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(auth('zis')->check() && auth('zis')->user()->role === 'admin')
                                <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penyaluran ini? Data keuangan akan berubah!');" action="{{ route('zis.penyaluran.destroy', $item->id) }}" method="POST">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-light text-muted border">
                                    <i class="fas fa-lock me-1"></i> Terkunci
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0 fw-bold">Belum ada data penyaluran</p>
                                <small>Silakan input penyaluran dana baru</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $penyaluran->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
