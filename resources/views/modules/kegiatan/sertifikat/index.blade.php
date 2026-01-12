@extends('layouts.app')
@section('title', 'Generate Sertifikat')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-certificate text-green-700 mr-2"></i>Generate Sertifikat
                </h1>
                <p class="text-gray-600 mt-2">Buat dan kelola sertifikat untuk peserta kegiatan</p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Sertifikat</p>
                            <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-certificate text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold">{{ $stats['bulan_ini'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Download</p>
                            <h3 class="text-2xl font-bold">{{ number_format($stats['total_download']) }}</h3>
                        </div>
                        <i class="fas fa-download text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-4">
                    <button id="btn-generate" class="border-b-2 border-green-700 text-green-700 py-2 px-4 font-medium"
                        onclick="showTab('generate')">
                        <i class="fas fa-plus-circle mr-2"></i>Generate Baru
                    </button>
                    <button id="btn-history" class="border-b-2 border-transparent text-gray-600 py-2 px-4 hover:text-gray-800"
                        onclick="showTab('history')">
                        <i class="fas fa-history mr-2"></i>Riwayat Sertifikat
                    </button>
                    <button id="btn-template" class="border-b-2 border-transparent text-gray-600 py-2 px-4 hover:text-gray-800"
                        onclick="showTab('template')">
                        <i class="fas fa-file-image mr-2"></i>Template
                    </button>
                </nav>
            </div>

            <!-- Tab: Generate Baru -->
            <div id="tab-generate">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Generator -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg border border-green-200">
                            <h3 class="font-semibold text-gray-800 mb-2">
                                <i class="fas fa-info-circle text-green-700 mr-2"></i>Panduan Generate Sertifikat
                            </h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Pilih kegiatan yang akan diterbitkan sertifikat</li>
                                <li>• Pilih template sertifikat yang sesuai</li>
                                <li>• Masukkan data peserta atau upload dari file</li>
                                <li>• Generate dan download sertifikat</li>
                            </ul>
                        </div>

                        <form action="{{ route('kegiatan.sertifikat.generate') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4" id="form-generate">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select name="kegiatan_id" id="kegiatan_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    onchange="loadPeserta(); updatePreview();">
                                    <option value="">-- Pilih Kegiatan --</option>
                                    @foreach ($kegiatans as $kegiatan)
                                        <option value="{{ $kegiatan->id }}" 
                                            data-nama="{{ $kegiatan->nama_kegiatan }}"
                                            data-tanggal="{{ $kegiatan->tanggal_mulai->format('d M Y') }}"
                                            data-jenis="{{ $kegiatan->jenis_kegiatan }}">
                                            {{ $kegiatan->nama_kegiatan }} - {{ $kegiatan->tanggal_mulai->format('d M Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Template Sertifikat <span class="text-red-500">*</span>
                                </label>
                                <select name="template" id="template" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    onchange="updatePreview()">
                                    <option value="">-- Pilih Template --</option>
                                    <option value="kajian">Template Kajian (Hijau)</option>
                                    <option value="workshop">Template Workshop (Biru)</option>
                                    <option value="pelatihan">Template Pelatihan (Coklat)</option>
                                    <option value="default">Template Default</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Input Peserta
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="input_method" value="manual" class="mr-2"
                                            onchange="toggleInputMethod()">
                                        <span class="text-gray-700">Manual</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="input_method" value="upload" class="mr-2"
                                            onchange="toggleInputMethod()">
                                        <span class="text-gray-700">Upload Excel</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="input_method" value="from_peserta" class="mr-2" checked
                                            onchange="toggleInputMethod()">
                                        <span class="text-gray-700">Dari Peserta</span>
                                    </label>
                                </div>
                            </div>

                            <div id="manual-input" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Daftar Nama Peserta <span class="text-red-500">*</span>
                                </label>
                                <textarea name="participants" rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Masukkan nama peserta (satu nama per baris)&#10;&#10;Contoh:&#10;Ahmad Fauzi&#10;Siti Nurhaliza&#10;Budi Santoso"></textarea>
                            </div>

                            <div id="upload-input" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload File Excel
                                </label>
                                <input type="file" name="excel_file" accept=".xlsx,.xls"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">
                                    Format: Excel dengan kolom "Nama Peserta"
                                </p>
                            </div>

                            <div id="from-peserta-input">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Sertifikat akan digenerate otomatis untuk semua peserta yang hadir pada kegiatan yang dipilih.
                                    </p>
                                </div>
                                
                                <!-- Daftar Peserta -->
                                <div id="peserta-list" class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium text-gray-700">
                                            <i class="fas fa-users text-green-600 mr-2"></i>
                                            Pilih Peserta (<span id="peserta-count">0</span> terdaftar)
                                        </h4>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="selectAllPeserta()" class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                                <i class="fas fa-check-double mr-1"></i>Pilih Semua
                                            </button>
                                            <button type="button" onclick="deselectAllPeserta()" class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                                                <i class="fas fa-times mr-1"></i>Batal Pilih
                                            </button>
                                        </div>
                                    </div>
                                    <div id="peserta-container" class="space-y-2 max-h-60 overflow-y-auto">
                                        <p class="text-gray-500 text-sm text-center py-4">
                                            <i class="fas fa-arrow-up mr-2"></i>Pilih kegiatan terlebih dahulu
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-sm text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span id="selected-count">0</span> peserta dipilih
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Penandatangan
                                    </label>
                                    <input type="text" name="ttd_pejabat" id="ttd_pejabat"
                                        placeholder="Contoh: Ustadz Ahmad Fauzi, Lc."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        onchange="updatePreview()">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jabatan
                                    </label>
                                    <input type="text" name="jabatan_pejabat" id="jabatan_pejabat"
                                        placeholder="Contoh: Ketua Takmir Masjid"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        onchange="updatePreview()">
                                </div>
                            </div>

                            @can('kegiatan.create')
                                <div class="flex gap-4 pt-4">
                                    <button type="submit"
                                        class="flex-1 px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                                        <i class="fas fa-certificate mr-2"></i>Generate Sertifikat
                                    </button>
                                </div>
                            @endcan
                        </form>
                    </div>

                    <!-- Preview -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Sertifikat</h3>
                        <div id="preview-container">
                            <div id="preview-empty" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50">
                                <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Preview akan muncul di sini</p>
                                <p class="text-sm text-gray-400 mt-2">Pilih template dan isi data untuk melihat preview</p>
                            </div>
                            
                            <!-- Preview Sertifikat Aktif -->
                            <div id="preview-active" class="hidden">
                                <div id="sertifikat-preview" class="rounded-lg shadow-lg overflow-hidden" style="aspect-ratio: 1.414;">
                                    <!-- Template akan di-render di sini -->
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-3">
                                    *Preview ini hanya simulasi, hasil akhir akan berbeda
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Riwayat (Hidden by default) -->
            <div id="tab-history" class="hidden">
                <form method="GET" class="mb-4">
                    <input type="hidden" name="tab" value="history">
                    <div class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari riwayat sertifikat..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <select name="kegiatan_id" class="px-4 py-2 border border-gray-300 rounded-lg"
                            onchange="this.form.submit()">
                            <option value="">Semua Kegiatan</option>
                            @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}"
                                    {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                    {{ $kegiatan->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Download</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sertifikats as $sertifikat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $sertifikat->nomor_sertifikat }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-800">{{ $sertifikat->nama_peserta }}</p>
                                        <p class="text-xs text-gray-500">{{ $sertifikat->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ \Illuminate\Support\Str::limit($sertifikat->nama_kegiatan, 30) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="{{ $sertifikat->getTemplateBadgeClass() }} text-xs px-2 py-1 rounded">
                                            {!! $sertifikat->getTemplateIcon() !!} {{ ucfirst($sertifikat->template) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <i class="fas fa-download mr-1"></i>{{ $sertifikat->download_count }}x
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('kegiatan.sertifikat.download', $sertifikat) }}"
                                                class="text-green-600 hover:text-green-800" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @can('kegiatan.delete')
                                                <form action="{{ route('kegiatan.sertifikat.destroy', $sertifikat) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                        <i class="fas fa-certificate text-6xl mb-4 text-gray-300"></i>
                                        <h3 class="text-xl font-semibold mb-2">Belum Ada Sertifikat</h3>
                                        <p>Generate sertifikat untuk peserta kegiatan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sertifikats->hasPages())
                    <div class="mt-6">
                        {{ $sertifikats->appends(['tab' => 'history'])->links() }}
                    </div>
                @endif
            </div>

            <!-- Tab: Template (Hidden by default) -->
            <div id="tab-template" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="selectTemplate('kajian')">
                        <div class="bg-gradient-to-br from-green-100 to-green-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-green-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Kajian</h4>
                        <p class="text-sm text-gray-600">Template dengan tema hijau untuk kegiatan kajian</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="selectTemplate('workshop')">
                        <div class="bg-gradient-to-br from-blue-100 to-blue-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-blue-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Workshop</h4>
                        <p class="text-sm text-gray-600">Template dengan tema biru untuk workshop</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="selectTemplate('pelatihan')">
                        <div class="bg-gradient-to-br from-amber-100 to-amber-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-amber-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Pelatihan</h4>
                        <p class="text-sm text-gray-600">Template dengan tema coklat untuk pelatihan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedPeserta = [];
        
        // Tab switching
        function showTab(tab) {
            // Hide all tabs
            document.getElementById('tab-generate').classList.add('hidden');
            document.getElementById('tab-history').classList.add('hidden');
            document.getElementById('tab-template').classList.add('hidden');

            // Show selected tab
            document.getElementById('tab-' + tab).classList.remove('hidden');

            // Update tab buttons
            ['generate', 'history', 'template'].forEach(t => {
                const btn = document.getElementById('btn-' + t);
                if (t === tab) {
                    btn.classList.remove('border-transparent', 'text-gray-600');
                    btn.classList.add('border-green-700', 'text-green-700');
                } else {
                    btn.classList.remove('border-green-700', 'text-green-700');
                    btn.classList.add('border-transparent', 'text-gray-600');
                }
            });
        }

        // Input method toggle
        function toggleInputMethod() {
            const manual = document.querySelector('input[name="input_method"][value="manual"]').checked;
            const upload = document.querySelector('input[name="input_method"][value="upload"]').checked;
            const fromPeserta = document.querySelector('input[name="input_method"][value="from_peserta"]').checked;

            document.getElementById('manual-input').classList.toggle('hidden', !manual);
            document.getElementById('upload-input').classList.toggle('hidden', !upload);
            document.getElementById('from-peserta-input').classList.toggle('hidden', !fromPeserta);
        }

        // Load peserta berdasarkan kegiatan
        function loadPeserta() {
            const kegiatanId = document.getElementById('kegiatan_id').value;
            const container = document.getElementById('peserta-container');
            
            if (!kegiatanId) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4"><i class="fas fa-arrow-up mr-2"></i>Pilih kegiatan terlebih dahulu</p>';
                document.getElementById('peserta-count').textContent = '0';
                return;
            }
            
            container.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-green-600 text-2xl"></i><p class="text-sm text-gray-500 mt-2">Memuat peserta...</p></div>';
            
            // Fetch peserta dari API
            fetch(`{{ url('/kegiatan/sertifikat/peserta') }}?kegiatan_id=${kegiatanId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.peserta && data.peserta.length > 0) {
                        let html = '';
                        data.peserta.forEach((p, i) => {
                            html += `
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input type="checkbox" name="peserta_ids[]" value="${p.id}" 
                                        class="peserta-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        onchange="updateSelectedCount()" checked>
                                    <div class="ml-3 flex-1">
                                        <span class="font-medium text-gray-800">${p.nama}</span>
                                        <span class="text-xs text-gray-500 ml-2">(${p.email || 'No email'})</span>
                                    </div>
                                </label>
                            `;
                        });
                        container.innerHTML = html;
                        document.getElementById('peserta-count').textContent = data.peserta.length;
                        updateSelectedCount();
                    } else {
                        container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4"><i class="fas fa-users-slash mr-2"></i>Tidak ada peserta yang hadir pada kegiatan ini</p>';
                        document.getElementById('peserta-count').textContent = '0';
                    }
                })
                .catch(error => {
                    container.innerHTML = '<p class="text-red-500 text-sm text-center py-4"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat peserta</p>';
                    console.error('Error:', error);
                });
        }
        
        function selectAllPeserta() {
            document.querySelectorAll('.peserta-checkbox').forEach(cb => cb.checked = true);
            updateSelectedCount();
        }
        
        function deselectAllPeserta() {
            document.querySelectorAll('.peserta-checkbox').forEach(cb => cb.checked = false);
            updateSelectedCount();
        }
        
        function updateSelectedCount() {
            const count = document.querySelectorAll('.peserta-checkbox:checked').length;
            document.getElementById('selected-count').textContent = count;
        }
        
        // Update preview sertifikat
        function updatePreview() {
            const template = document.getElementById('template').value;
            const kegiatan = document.getElementById('kegiatan_id');
            const selectedOption = kegiatan.options[kegiatan.selectedIndex];
            
            if (!template) {
                document.getElementById('preview-empty').classList.remove('hidden');
                document.getElementById('preview-active').classList.add('hidden');
                return;
            }
            
            document.getElementById('preview-empty').classList.add('hidden');
            document.getElementById('preview-active').classList.remove('hidden');
            
            const namaKegiatan = selectedOption?.getAttribute('data-nama') || 'Nama Kegiatan';
            const jenisKegiatan = selectedOption?.getAttribute('data-jenis') || 'Kajian';
            
            // Template colors
            const colors = {
                'kajian': { bg: 'from-green-600 to-green-700', border: 'border-green-500', icon: 'text-green-100' },
                'workshop': { bg: 'from-blue-600 to-blue-700', border: 'border-blue-500', icon: 'text-blue-100' },
                'pelatihan': { bg: 'from-amber-600 to-amber-700', border: 'border-amber-500', icon: 'text-amber-100' },
                'default': { bg: 'from-gray-600 to-gray-700', border: 'border-gray-500', icon: 'text-gray-100' }
            };
            
            const c = colors[template] || colors['default'];
            
            document.getElementById('sertifikat-preview').innerHTML = `
                <div class="bg-gradient-to-br ${c.bg} p-6 h-full flex flex-col items-center justify-center text-white relative border-8 ${c.border}">
                    <div class="absolute top-4 left-4 opacity-20">
                        <i class="fas fa-mosque text-6xl ${c.icon}"></i>
                    </div>
                    <div class="text-center z-10">
                        <i class="fas fa-mosque text-4xl mb-3 opacity-80"></i>
                        <h2 class="text-2xl font-bold mb-1">SERTIFIKAT</h2>
                        <p class="text-sm opacity-90 mb-4">Diberikan Kepada</p>
                        <div class="bg-white/20 px-6 py-3 rounded-lg mb-4">
                            <h3 class="text-xl font-bold">NAMA PESERTA</h3>
                        </div>
                        <p class="text-sm opacity-90 mb-1">Telah mengikuti kegiatan</p>
                        <p class="font-semibold text-lg">${namaKegiatan}</p>
                        <p class="text-xs opacity-75 mt-4">Template: ${template.toUpperCase()}</p>
                    </div>
                </div>
            `;
        }
        
        // Select template dari tab Template
        function selectTemplate(template) {
            document.getElementById('template').value = template;
            showTab('generate');
            updatePreview();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            toggleInputMethod();
            
            // Show history tab if there's search parameter
            @if (request('tab') == 'history' || request('kegiatan_id') || (request('search') && $sertifikats->count() > 0))
                showTab('history');
            @endif
        });
    </script>
@endsection
