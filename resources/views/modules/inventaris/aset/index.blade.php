@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-2">Daftar Aset</h4>
    <p class="text-muted">Kelola data aset inventaris masjid.</p>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
            ← Kembali ke Dashboard Inventaris
        </a>

        <a href="{{ route('inventaris.aset.create') }}" class="btn btn-success">
            + Tambah Aset Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        <tr>
                            <td>{{ $asset->kode_aset }}</td>
                            <td>{{ $asset->nama_aset }}</td>
                            <td>{{ $asset->kategori }}</td>
                            <td>{{ $asset->lokasi }}</td>
                            <td>
                                <span class="badge bg-{{ $asset->kondisi == 'Baik' ? 'success' : 'warning' }}">
                                    {{ $asset->kondisi }}
                                </span>
                            </td>
                            <td>{{ $asset->jumlah }}</td>
                            <td>
                                <span class="badge bg-{{ $asset->status == 'Aktif' ? 'primary' : 'danger' }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td>
                                {{-- DETAIL: WAJIB KIRIM ID --}}
                                <a href="{{ route('inventaris.aset.show', $asset->kode_aset) }}"
                                class="btn btn-sm btn-info">
                                    👁
                                </a>

                                {{-- EDIT & DELETE nanti kita isi --}}
                                <button type="button" class="btn btn-sm btn-warning" disabled>✏</button>
                                <button type="button" class="btn btn-sm btn-danger" disabled>🗑</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada data aset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $assets->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
