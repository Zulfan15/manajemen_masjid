@extends('layouts.app')

@section('title', 'Donasi Saya')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-hand-holding-heart text-green-600 mr-2"></i>Donasi Saya
                    </h1>
                    <p class="text-gray-600 mt-2">Kirim donasi dan lihat riwayat pembayaran Anda</p>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p><i class="fas fa-times-circle mr-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Input Donasi --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-plus-circle text-green-600 mr-2"></i>Kirim Donasi Baru
                    </h2>

                    <form action="{{ route('jamaah.pemasukan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Kategori Donasi --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Donasi <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Donasi">💰 Donasi Umum</option>
                                <option value="Zakat">📿 Zakat (Fitrah/Mal)</option>
                                <option value="Infak">🕌 Infak</option>
                                <option value="Sedekah">❤️ Sedekah</option>
                            </select>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" min="1000" required placeholder="Minimal Rp 1.000"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('tanggal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran <span
                                    class="text-red-500">*</span></label>
                            <select name="metode_pembayaran" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih Metode --</option>
                                <option value="Transfer Bank">🏦 Transfer Bank</option>
                                <option value="E-Wallet">📱 E-Wallet (GoPay/OVO/Dana)</option>
                                <option value="Tunai">💵 Tunai</option>
                                <option value="QRIS">📲 QRIS</option>
                            </select>
                            @error('metode_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bukti Transfer --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer (Opsional)</label>
                            <input type="file" name="bukti_transfer" accept="image/*,.pdf"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                            @error('bukti_transfer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Donasi
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Donasi Anda akan diverifikasi oleh pengurus masjid sebelum masuk ke laporan keuangan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Donasi --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-history text-blue-600 mr-2"></i>Riwayat Donasi Saya
                        </h2>
                    </div>

                    @if($pemasukan->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($pemasukan as $item)
                                                <div class="p-6 hover:bg-gray-50 transition">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <span class="text-lg font-bold text-gray-800">
                                                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                                                </span>
                                                                @php
                                                                    $statusColors = [
                                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                                        'verified' => 'bg-green-100 text-green-800',
                                                                        'rejected' => 'bg-red-100 text-red-800',
                                                                    ];
                                                                    $statusIcons = [
                                                                        'pending' => 'fas fa-clock',
                                                                        'verified' => 'fas fa-check-circle',
                                                                        'rejected' => 'fas fa-times-circle',
                                                                    ];
                                                                    $statusLabels = [
                                                                        'pending' => 'Menunggu Verifikasi',
                                                                        'verified' => 'Terverifikasi',
                                                                        'rejected' => 'Ditolak',
                                                                    ];
                                                                @endphp
                                 <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                                    <i
                                                                        class="{{ $statusIcons[$item->status] ?? 'fas fa-question-circle' }} mr-1"></i>
                                                                    {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                                                </span>
                                                            </div>

                                                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                                                <div><i class="fas fa-tag mr-1"></i>{{ $item->jenis }}</div>
                                                                <div><i
                                                                        class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                                </div>
                                                                <div><i class="fas fa-credit-card mr-1"></i>{{ $item->sumber ?? '-' }}</div>
                                                                <div><i class="fas fa-clock mr-1"></i>{{ $item->created_at->diffForHumans() }}</div>
                                                            </div>

                                                            @if($item->keterangan)
                                                                <p class="text-sm text-gray-500 mt-2">
                                                                    <i class="fas fa-comment mr-1"></i>{{ $item->keterangan }}
                                                                </p>
                                                            @endif

                                                            @if($item->status === 'rejected' && $item->alasan_tolak)
                                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                    <strong>Alasan Penolakan:</strong> {{ $item->alasan_tolak }}
                                                                </div>
                                                            @endif

                                                            @if($item->status === 'verified' && $item->verified_at)
                                                                <div class="mt-2 text-xs text-green-600">
                                                                    <i class="fas fa-check mr-1"></i>
                                                                    Diverifikasi pada
                                                                    {{ \Carbon\Carbon::parse($item->verified_at)->format('d M Y H:i') }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <a href="{{ route('jamaah.pemasukan.detail', $item->id) }}"
                                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </a>
                                                    </div>
                                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            {{ $pemasukan->links() }}
                        </div>
                    @else
                        <div class="p-12 text-center text-gray-500">
                            <i class="fas fa-hand-holding-heart text-5xl mb-4 text-gray-300"></i>
                            <p class="text-lg">Anda belum memiliki riwayat donasi</p>
                            <p class="text-sm mt-2">Mulai berdonasi dengan mengisi form di samping</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection