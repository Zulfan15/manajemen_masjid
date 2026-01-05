@extends('modules.zis.layout.template')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark">Manajemen Akun</h4>
    <a href="{{ route('zis.user.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
        <i class="fas fa-user-plus me-2"></i> Tambah User Baru
    </a>
</div>

{{-- Cek Pesan Sukses --}}
@if ($message = session('success'))
<div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
    <i class="fas fa-check-circle me-2"></i> {{ $message }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Cek Pesan Error --}}
@if ($message = session('error'))
<div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
    <i class="fas fa-exclamation-triangle me-2"></i> {{ $message }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3">Nama Pengguna</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $row)
                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">{{ $row->name }}</td>
                    <td class="px-4 py-3">{{ $row->email }}</td>
                    <td class="px-4 py-3">
                        {{-- PERHATIKAN BAGIAN INI: @if HARUS DITUTUP @endif --}}
                        @if($row->role == 'admin')
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-secondary">Amil</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form onsubmit="return confirm('Hapus user ini?');" action="{{ route('zis.user.destroy', $row->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" {{ auth('zis')->user()->id == $row->id ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
