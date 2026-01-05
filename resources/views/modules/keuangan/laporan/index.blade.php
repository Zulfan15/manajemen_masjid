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

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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

    <!-- Error Alert -->
    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
        <strong>❌ Error!</strong> 
        <div id="error-message" class="mt-2"></div>
        <button type="button" class="btn-close" onclick="hideError()"></button>
    </div>

    <!-- Info Alert (untuk data kosong) -->
    <div id="info-alert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none;">
        <strong>ℹ️ Informasi</strong> 
        <div id="info-message" class="mt-2"></div>
        <button type="button" class="btn-close" onclick="hideInfo()"></button>
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
            <div style="position: relative; height: 400px;">
                <canvas id="rekapChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="card shadow-sm" id="export-container" style="display: none;">
        <div class="card-body">
            <h5 class="card-title mb-3">Export Data</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <button class="btn btn-danger w-100 btn-lg" onclick="exportPdf()" id="btn-export-pdf">
                        <i class="bi bi-file-pdf-fill"></i> Export PDF
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success w-100 btn-lg" onclick="exportExcel()" id="btn-export-excel">
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

#error-message, #info-message {
    font-family: monospace;
    font-size: 0.9rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<script>
let chart = null;

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Page loaded, auto-loading report...');
    loadRekap();
});

function loadRekap() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    console.log('🔄 Loading rekap...', { startDate, endDate });
    
    // Validasi input
    if (!startDate || !endDate) {
        showError('Mohon pilih tanggal mulai dan tanggal akhir');
        return;
    }
    
    // Validasi tanggal
    if (new Date(startDate) > new Date(endDate)) {
        showError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    // Show loading, hide everything else
    document.getElementById('loading-indicator').style.display = 'block';
    hideError();
    hideInfo();
    document.getElementById('stats-container').style.display = 'none';
    document.getElementById('chart-container').style.display = 'none';
    document.getElementById('export-container').style.display = 'none';
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('🔐 CSRF Token:', csrfToken ? 'Found' : 'NOT FOUND');
    
    // Fetch data dari API
    const url = `/keuangan/laporan/rekap?start_date=${startDate}&end_date=${endDate}`;
    console.log('📡 Fetching:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken || '',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('📥 Response received:', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok
        });
        
        return response.clone().text().then(text => {
            console.log('📄 Raw response:', text.substring(0, 500));
            
            try {
                const data = JSON.parse(text);
                console.log('✅ Parsed JSON:', data);
                
                if (!response.ok) {
                    throw new Error(data.message || data.error || `HTTP ${response.status}`);
                }
                
                return data;
            } catch (e) {
                console.error('❌ JSON Parse Error:', e);
                throw new Error('Response bukan JSON valid. Mungkin ada error di server.');
            }
        });
    })
    .then(data => {
        console.log('✅ Data processed:', data);
        
        // Hide loading
        document.getElementById('loading-indicator').style.display = 'none';
        
        // Cek apakah ada error di response
        if (data.success === false) {
            showError(data.error || data.message || 'Terjadi kesalahan');
            
            // Tampilkan debug info jika ada
            if (data.debug_info) {
                console.error('🐛 Debug Info:', data.debug_info);
            }
            return;
        }
        
        // Cek apakah tidak ada data
        if (data.count === 0) {
            showInfo('Tidak ada data pemasukan untuk periode yang dipilih. Silakan pilih periode lain.');
            // Tetap tampilkan stats dengan nilai 0
        }
        
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
        
        console.log('✅ UI updated successfully');
    })
    .catch(error => {
        console.error('❌ Error caught:', error);
        document.getElementById('loading-indicator').style.display = 'none';
        
        showError(`
            <strong>Error:</strong> ${error.message}<br>
            <small class="text-muted">Silakan cek console browser (F12) untuk detail lebih lanjut.</small>
        `);
    });
}

function updateChart(chartData) {
    console.log('📊 Updating chart with data:', chartData);
    
    const ctx = document.getElementById('rekapChart');
    if (!ctx) {
        console.error('❌ Canvas element not found');
        return;
    }
    
    // Destroy existing chart
    if (chart) {
        chart.destroy();
        chart = null;
    }
    
    const labels = Object.keys(chartData);
    const data = Object.values(chartData);
    
    console.log('📊 Chart labels:', labels);
    console.log('📊 Chart data:', data);
    
    if (labels.length === 0) {
        // Tampilkan pesan jika tidak ada data
        const context = ctx.getContext('2d');
        context.clearRect(0, 0, ctx.width, ctx.height);
        context.font = '16px Arial';
        context.fillStyle = '#999';
        context.textAlign = 'center';
        context.fillText('Tidak ada data untuk ditampilkan', ctx.width / 2, ctx.height / 2);
        console.log('ℹ️ No chart data available');
        return;
    }
    
    try {
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
        
        console.log('✅ Chart created successfully');
    } catch (error) {
        console.error('❌ Error creating chart:', error);
    }
}

function exportPdf() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Validasi
    if (!startDate || !endDate) {
        showError('Mohon pilih tanggal terlebih dahulu');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        showError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    console.log('📄 Exporting PDF...');
    
    // Disable button
    const btn = document.getElementById('btn-export-pdf');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    
    // Buat form hidden untuk submit
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '/keuangan/laporan/export-pdf';
    form.target = '_blank';
    
    // Tambahkan input hidden
    const startInput = document.createElement('input');
    startInput.type = 'hidden';
    startInput.name = 'start_date';
    startInput.value = startDate;
    form.appendChild(startInput);
    
    const endInput = document.createElement('input');
    endInput.type = 'hidden';
    endInput.name = 'end_date';
    endInput.value = endDate;
    form.appendChild(endInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Tampilkan notifikasi
    showSuccess('PDF sedang diproses... Download akan dimulai sebentar lagi.');
    
    // Enable button kembali setelah 2 detik
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2000);
}

function exportExcel() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Validasi
    if (!startDate || !endDate) {
        showError('Mohon pilih tanggal terlebih dahulu');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        showError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    console.log('📊 Exporting Excel...');
    
    // Disable button
    const btn = document.getElementById('btn-export-excel');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    
    // Buat form hidden untuk submit
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '/keuangan/laporan/export-excel';
    form.target = '_blank';
    
    // Tambahkan input hidden
    const startInput = document.createElement('input');
    startInput.type = 'hidden';
    startInput.name = 'start_date';
    startInput.value = startDate;
    form.appendChild(startInput);
    
    const endInput = document.createElement('input');
    endInput.type = 'hidden';
    endInput.name = 'end_date';
    endInput.value = endDate;
    form.appendChild(endInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Tampilkan notifikasi
    showSuccess('Excel sedang diproses... Download akan dimulai sebentar lagi.');
    
    // Enable button kembali setelah 2 detik
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2000);
}

function formatRupiah(amount) {
    return 'Rp ' + parseFloat(amount).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

function showError(message) {
    const alertDiv = document.getElementById('error-alert');
    const messageDiv = document.getElementById('error-message');
    messageDiv.innerHTML = message;
    alertDiv.style.display = 'block';
    
    // Scroll to error
    alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideError() {
    document.getElementById('error-alert').style.display = 'none';
}

function showInfo(message) {
    const alertDiv = document.getElementById('info-alert');
    const messageDiv = document.getElementById('info-message');
    messageDiv.textContent = message;
    alertDiv.style.display = 'block';
}

function hideInfo() {
    document.getElementById('info-alert').style.display = 'none';
}

function showSuccess(message) {
    // Buat alert success jika belum ada
    let successAlert = document.getElementById('success-alert');
    
    if (!successAlert) {
        successAlert = document.createElement('div');
        successAlert.id = 'success-alert';
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.style.position = 'fixed';
        successAlert.style.top = '20px';
        successAlert.style.right = '20px';
        successAlert.style.zIndex = '9999';
        successAlert.style.minWidth = '300px';
        successAlert.innerHTML = `
            <strong>✅ Sukses!</strong>
            <div id="success-message"></div>
            <button type="button" class="btn-close" onclick="hideSuccess()"></button>
        `;
        document.body.appendChild(successAlert);
    }
    
    document.getElementById('success-message').textContent = message;
    successAlert.style.display = 'block';
    
    // Auto hide setelah 5 detik
    setTimeout(() => {
        hideSuccess();
    }, 5000);
}

function hideSuccess() {
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        successAlert.style.display = 'none';
    }
}
</script>
@endsection