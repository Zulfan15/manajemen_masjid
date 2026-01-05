@extends('modules.zis.layout.template')
@section('title', 'Data Mustahiq')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark">Data Mustahiq</h4>
    <a href="{{ route('zis.mustahiq.create') }}" class="btn btn-success px-4 fw-bold shadow-sm">
        <i class="fas fa-user-plus me-2"></i> Tambah Baru
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
                        <th class="px-4 py-3">Nama & Kategori</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mustahiq as $row)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-bold text-dark">{{ $row->nama_lengkap }}</span>
                            <span class="badge bg-info text-dark ms-2">{{ $row->kategori }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $row->no_hp }}</td>
                        <td class="px-4 py-3">
                            @if($row->status_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('zis.mustahiq.edit', $row->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                
                                {{-- HANYA ADMIN YANG BISA LIHAT TOMBOL HAPUS --}}
                                @if(auth('zis')->check() && auth('zis')->user()->role === 'admin')
                                <form onsubmit="return confirm('Yakin hapus data ini?');" action="{{ route('zis.mustahiq.destroy', $row->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">{{ $mustahiq->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
