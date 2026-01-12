@extends('layouts.app')

@section('title', 'Detail Kurban')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-details text-green-700 mr-2"></i>Detail Kurban
                </h1>
                <p class="text-gray-600 mt-2">Nomor: <strong>{{ $kurban->nomor_kurban }}</strong></p>
            </div>
            <div class="flex space-x-2">
                @if(auth()->user()->hasPermission('kurban.view'))
                    <a href="{{ route('kurban.report.download', $kurban) }}" target="_blank" class="bg-green-700 text-white px-4 py-3 rounded-lg hover:bg-green-800 transition flex items-center space-x-2">
                        <i class="fas fa-file-pdf"></i>
                        <span>Laporan PDF</span>
                    </a>
                @endif
                @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.update'))
                    <a href="{{ route('kurban.edit', $kurban) }}" class="bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                @endif
                <a href="{{ route('kurban.index') }}" class="bg-gray-400 text-white px-4 py-3 rounded-lg hover:bg-gray-500 transition flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mt-4">
                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong> - Anda hanya dapat melihat data, tidak dapat mengedit.</p>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if($message = session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ $message }}</span>
        </div>
    @endif

    <!-- Main Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card 1: Info Dasar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4 pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Dasar</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Jenis Hewan</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst($kurban->jenis_hewan) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Hewan</p>
                    <p class="font-semibold text-gray-800">{{ $kurban->nama_hewan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Berat Badan</p>
                    <p class="font-semibold text-gray-800">{{ number_format($kurban->berat_badan, 2) }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kondisi Kesehatan</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $kurban->kondisi_kesehatan)) }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4 pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Status & Timeline</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Status Saat Ini</p>
                    @php
                        $statusColors = [
                            'disiapkan' => 'bg-blue-100 text-blue-800',
                            'siap_sembelih' => 'bg-yellow-100 text-yellow-800',
                            'disembelih' => 'bg-purple-100 text-purple-800',
                            'didistribusi' => 'bg-orange-100 text-orange-800',
                            'selesai' => 'bg-green-100 text-green-800',
                        ];
                        $statusLabel = [
                            'disiapkan' => 'Disiapkan',
                            'siap_sembelih' => 'Siap Disembelih',
                            'disembelih' => 'Disembelih',
                            'didistribusi' => 'Didistribusi',
                            'selesai' => 'Selesai',
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 {{ $statusColors[$kurban->status] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium">
                        {{ $statusLabel[$kurban->status] ?? $kurban->status }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Persiapan</p>
                    <p class="font-semibold text-gray-800">{{ $kurban->tanggal_persiapan->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Penyembelihan</p>
                    <p class="font-semibold text-gray-800">{{ $kurban->tanggal_penyembelihan?->format('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Biaya -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4 pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Biaya</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Harga Belian</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($kurban->harga_hewan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Biaya Operasional</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($kurban->biaya_operasional, 0, ',', '.') }}</p>
                </div>
                <div class="pt-4 border-t">
                    <p class="text-sm text-gray-600">Total Biaya</p>
                    <p class="font-bold text-lg text-green-700">Rp {{ number_format($kurban->total_biaya, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kuota & Pembayaran Info -->
    @php
        $sisaKuota = $kurban->getSisaKuota();
        $kuotaTerisi = $kurban->getCurrentKuotaUsage();
        $persentase = $kurban->getKuotaPercentage();
        $progressClass = 'bg-green-500';
        if ($persentase >= 100) {
            $progressClass = 'bg-red-500';
        } elseif ($persentase >= 75) {
            $progressClass = 'bg-yellow-500';
        }
    @endphp
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="mb-4 pb-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-pie text-green-700 mr-2"></i>Progress Kuota & Pembayaran
            </h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kuota Section -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Kuota Peserta</span>
                    <span class="text-sm font-bold {{ $sisaKuota == 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $kuotaTerisi }} / {{ $kurban->max_kuota }} Terisi
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6 mb-2">
                    <div class="{{ $progressClass }} h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition-all" style="width: {{ min($persentase, 100) }}%">
                        {{ number_format($persentase, 1) }}%
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>
                        @if($sisaKuota == 0)
                            <span class="text-red-600 font-bold"><i class="fas fa-check-circle"></i> KUOTA PENUH</span>
                        @else
                            <span class="text-green-600"><i class="fas fa-user-plus"></i> Sisa {{ $sisaKuota }} Slot</span>
                        @endif
                    </span>
                    <span>Max {{ $kurban->max_kuota }} {{ $kurban->jenis_hewan == 'sapi' ? 'Orang' : 'Ekor' }}</span>
                </div>
            </div>
            
            <!-- Pembayaran Section -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga per Bagian (Terkunci)</span>
                    <span class="font-bold text-lg text-green-700">
                        <i class="fas fa-lock text-xs text-gray-400 mr-1"></i>
                        Rp {{ number_format($kurban->harga_per_bagian, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Pembayaran Masuk</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($kurban->totalPembayaran(), 0, ',', '.') }}</span>
                </div>
                @if($kurban->total_berat_daging)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Berat Daging</span>
                    <span class="font-bold text-purple-600">{{ number_format($kurban->total_berat_daging, 2) }} kg</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Peserta Kurban Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users text-green-700 mr-2"></i>Peserta Kurban
            </h3>
            @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.create'))
                <a href="{{ route('kurban.peserta.create', $kurban) }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Peserta</span>
                </a>
            @endif
        </div>

        @if($pesertaKurbans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Peserta</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tipe</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Bagian</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Pembayaran</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($pesertaKurbans as $peserta)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $peserta->nama_peserta }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                        {{ ucfirst($peserta->tipe_peserta) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ number_format($peserta->jumlah_bagian, 2) }} bagian</td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($peserta->nominal_pembayaran, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $pembayaranColors = [
                                            'belum_lunas' => 'bg-red-100 text-red-800',
                                            'cicilan' => 'bg-yellow-100 text-yellow-800',
                                            'lunas' => 'bg-green-100 text-green-800',
                                        ];
                                        $pembayaranLabel = [
                                            'belum_lunas' => 'Belum Lunas',
                                            'cicilan' => 'Cicilan',
                                            'lunas' => 'Lunas',
                                        ];
                                    @endphp
                                    <span class="inline-block px-2 py-1 {{ $pembayaranColors[$peserta->status_pembayaran] ?? 'bg-gray-100 text-gray-800' }} rounded text-xs font-medium">
                                        {{ $pembayaranLabel[$peserta->status_pembayaran] ?? $peserta->status_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.update'))
                                            <a href="{{ route('kurban.peserta.edit', [$kurban, $peserta]) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.delete'))
                                            <form method="POST" action="{{ route('kurban.peserta.destroy', [$kurban, $peserta]) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination for Peserta -->
            <div class="mt-4">
                {{ $pesertaKurbans->render() }}
            </div>
        @else
            <p class="text-center py-8 text-gray-500">Belum ada peserta kurban</p>
        @endif
    </div>

    <!-- Distribusi Kurban Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-boxes text-green-700 mr-2"></i>Distribusi Daging
            </h3>
            @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.create'))
                <a href="{{ route('kurban.distribusi.create', $kurban) }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Distribusi</span>
                </a>
            @endif
        </div>

        @if($distribusiKurbans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Penerima</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jenis Distribusi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Berat (kg)</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Harga</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($distribusiKurbans as $distribusi)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $distribusi->penerima_nama }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-medium">
                                        {{ $distribusi->getJenisDistribusiLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ number_format($distribusi->berat_daging, 2) }} kg</td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($distribusi->estimasi_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $distribusiColors = [
                                            'belum_didistribusi' => 'bg-red-100 text-red-800',
                                            'sedang_disiapkan' => 'bg-yellow-100 text-yellow-800',
                                            'sudah_didistribusi' => 'bg-green-100 text-green-800',
                                        ];
                                        $distribusiLabel = [
                                            'belum_didistribusi' => 'Belum Didistribusi',
                                            'sedang_disiapkan' => 'Sedang Disiapkan',
                                            'sudah_didistribusi' => 'Sudah Didistribusi',
                                        ];
                                    @endphp
                                    <span class="inline-block px-2 py-1 {{ $distribusiColors[$distribusi->status_distribusi] ?? 'bg-gray-100 text-gray-800' }} rounded text-xs font-medium">
                                        {{ $distribusiLabel[$distribusi->status_distribusi] ?? $distribusi->status_distribusi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.update'))
                                            <a href="{{ route('kurban.distribusi.edit', [$kurban, $distribusi]) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(!auth()->user()->isSuperAdmin() && auth()->user()->hasPermission('kurban.delete'))
                                            <form method="POST" action="{{ route('kurban.distribusi.destroy', [$kurban, $distribusi]) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination for Distribusi -->
            <div class="mt-4">
                {{ $distribusiKurbans->render() }}
            </div>
        @else
            <p class="text-center py-8 text-gray-500">Belum ada data distribusi</p>
        @endif
    </div>
</div>
@endsection
