@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📊 Laporan Keuangan</h2>
            <p class="text-muted mb-0">Analisis dan rekap keuangan masjid</p>
        </div>
        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Filter Periode</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary w-100" onclick="loadRekap()">
                        <i class="bi bi-bar-chart-fill"></i> Lihat Rekap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat data...</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4" id="stats-container" style="display: none;">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Pemasukan</h6>
                            <h3 class="mb-0 fw-bold" id="total-amount">Rp 0</h3>
                        </div>
                        <div class="fs-1 opacity-50">💰</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Jumlah Transaksi</h6>
                            <h3 class="mb-0 fw-bold" id="total-count">0</h3>
                        </div>
                        <div class="fs-1 opacity-50">📊</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Rata-rata Transaksi</h6>
                            <h3 class="mb-0 fw-bold" id="average-amount">Rp 0</h3>
                        </div>
                        <div class="fs-1 opacity-50">📈</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="card shadow-sm mb-4" id="chart-container" style="display: none;">
        <div class="card-body">
            <h5 class="card-title mb-3">Grafik Pemasukan per Bulan</h5>
            <canvas id="rekapChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="card shadow-sm" id="export-container" style="display: none;">
        <div class="card-body">
            <h5 class="card-title mb-3">Export Data</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <button class="btn btn-danger w-100 btn-lg" onclick="exportPdf()">
                        <i class="bi bi-file-pdf-fill"></i> Export PDF
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success w-100 btn-lg" onclick="exportExcel()">
                        <i class="bi bi-file-excel-fill"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}
</style>

<script>
let chart = null;

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Auto load dengan tanggal default
    loadRekap();
});

function loadRekap() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate) {
        alert('Mohon pilih tanggal mulai dan tanggal akhir');
        return;
    }
    
    // Show loading
    document.getElementById('loading-indicator').style.display = 'block';
    document.getElementById('stats-container').style.display = 'none';
    document.getElementById('chart-container').style.display = 'none';
    document.getElementById('export-container').style.display = 'none';
    
    // URL DIPERBAIKI: /keuangan/laporan/rekap
    fetch(`/keuangan/laporan/rekap?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            // Hide loading
            document.getElementById('loading-indicator').style.display = 'none';
            
            // Update stats
            document.getElementById('total-amount').textContent = formatRupiah(data.total);
            document.getElementById('total-count').textContent = data.count;
            document.getElementById('average-amount').textContent = formatRupiah(data.average);
            
            // Show containers
            document.getElementById('stats-container').style.display = 'flex';
            document.getElementById('chart-container').style.display = 'block';
            document.getElementById('export-container').style.display = 'block';
            
            // Update chart
            updateChart(data.chartData);
        })
        .catch(error => {
            document.getElementById('loading-indicator').style.display = 'none';
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data');
        });
}

function updateChart(chartData) {
    const ctx = document.getElementById('rekapChart').getContext('2d');
    
    if (chart) {
        chart.destroy();
    }
    
    const labels = Object.keys(chartData);
    const data = Object.values(chartData);
    
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.map(label => {
                const [year, month] = label.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            }),
            datasets: [{
                label: 'Pemasukan',
                data: data,
                backgroundColor: 'rgba(102, 126, 234, 0.6)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pemasukan: ' + formatRupiah(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}

function exportPdf() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    // URL DIPERBAIKI: /keuangan/laporan/export-pdf
    window.location.href = `/keuangan/laporan/export-pdf?start_date=${startDate}&end_date=${endDate}`;
}

function exportExcel() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    // URL DIPERBAIKI: /keuangan/laporan/export-excel
    window.location.href = `/keuangan/laporan/export-excel?start_date=${startDate}&end_date=${endDate}`;
}

function formatRupiah(amount) {
    return 'Rp ' + parseFloat(amount).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}
</script>
@endsection