@extends('modules.zis.layout.template')
@section('title', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-success text-white overflow-hidden border-0" style="background: linear-gradient(120deg, #059669 0%, #047857 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-1">Assalamualaikum, {{ auth('zis')->user()->name }}! 👋</h2>
                    <p class="mb-0 opacity-75">Mari kelola amanah umat dengan profesional dan transparan.</p>
                </div>
                <i class="fas fa-mosque position-absolute" style="font-size: 15rem; color: rgba(255,255,255,0.1); right: -20px; bottom: -60px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted text-uppercase fw-bold small mb-1">Total Pemasukan</p>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                        <i class="fas fa-arrow-down fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center small">
                    @if($persentase_perubahan > 0)
                        <span class="badge bg-success bg-opacity-10 text-success me-2">+{{ number_format($persentase_perubahan, 1) }}%</span>
                    @elseif($persentase_perubahan < 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger me-2">{{ number_format($persentase_perubahan, 1) }}%</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary me-2">0%</span>
                    @endif
                    <span class="text-muted">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted text-uppercase fw-bold small mb-1">Total Penyaluran</p>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($total_penyaluran, 0, ',', '.') }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-paper-plane fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center small">
                    <span class="badge bg-danger bg-opacity-10 text-danger me-2">Aktif</span>
                    <span class="text-muted">dana tersalurkan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-success border-2 shadow-sm" style="background-color: #f0fdf4;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-success text-uppercase fw-bold small mb-1">Saldo Tersedia</p>
                        <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-success text-white shadow">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                </div>
                <div class="small text-success opacity-75 fw-semibold">
                    <i class="fas fa-check-circle me-1"></i> Siap disalurkan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="me-4 rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $total_muzakki }}</h4>
                    <p class="text-muted mb-0">Muzakki Terdaftar</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="me-4 rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $total_mustahiq }}</h4>
                    <p class="text-muted mb-0">Mustahiq Terdaftar</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
