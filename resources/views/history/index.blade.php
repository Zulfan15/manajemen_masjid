@extends('layouts.app')

@section('title', 'History Data Terhapus')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-history text-primary"></i> History Data Terhapus</h2>
            <p class="text-muted">Kelola data pemasukan yang sudah dihapus</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TABLE CARD -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-trash-restore"></i> Data Terhapus
                        <span class="badge bg-light text-dark ms-2">{{ $deletedData->total() }}</span>
                    </h5>
                </div>
                
                @if($deletedData->count() > 0)
                <div class="col-md-6 text-end">
                    <form action="{{ route('history.restoreAll') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Kembalikan semua data ({{ $deletedData->total() }} item)?')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-undo"></i> Restore Semua
                        </button>
                    </form>
                    
                    <form action="{{ route('history.resetAll') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('⚠️ HAPUS PERMANEN SEMUA DATA?\n\nData tidak bisa dikembalikan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus Semua
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($deletedData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Sumber</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                                <th>Dihapus Oleh</th>
                                <th>Dihapus Pada</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deletedData as $index => $data)
                                <tr>
                                    <td class="text-center">{{ $deletedData->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $data->jenis }}</span>
                                    </td>
                                    <td>{{ $data->sumber ?? '-' }}</td>
                                    <td class="text-end">
                                        <strong class="text-success">
                                            Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($data->status == 'verified')
                                            <span class="badge bg-success">✓ Verified</span>
                                        @elseif($data->status == 'rejected')
                                            <span class="badge bg-danger">✗ Rejected</span>
                                        @else
                                            <span class="badge bg-warning">⏳ Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->user)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle me-2" 
                                                     style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;">
                                                    <small>{{ strtoupper(substr($data->user->name, 0, 1)) }}</small>
                                                </div>
                                                <div>
                                                    <div class="fw-bold small">{{ $data->user->name }}</div>
                                                    <small class="text-muted">{{ $data->user->username }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $data->deleted_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $data->deleted_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Restore Button -->
                                            <form action="{{ route('history.restore', $data->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Kembalikan data ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Restore">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete Permanent Button -->
                                            <form action="{{ route('history.forceDelete', $data->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('⚠️ HAPUS PERMANEN?\n\nTidak bisa dikembalikan!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus Permanen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Menampilkan {{ $deletedData->firstItem() }} - {{ $deletedData->lastItem() }} 
                                dari {{ $deletedData->total() }} data
                            </small>
                        </div>
                        <div class="col-md-6">
                            {{ $deletedData->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Data Terhapus</h5>
                    <p class="text-muted">Semua data masih aktif atau sudah dihapus permanen</p>
                    <a href="{{ route('pemasukan.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Pemasukan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto hide alerts
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush

@endsection