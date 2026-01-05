@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-wallet text-green-600"></i> Pemasukan Saya
        </h1>
        <p class="text-gray-600 mt-2">Kelola donasi dan pemasukan Anda di sini</p>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Form Input Donasi -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-plus-circle text-green-600"></i> Input Donasi Baru
        </h2>

        <form action="{{ route('jamaah.pemasukan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Donasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Donasi <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">— Pilih Jenis —</option>
                        <option value="Donasi">💝 Donasi</option>
                        <option value="Zakat">🙏 Zakat</option>
                        <option value="Infak">💰 Infak</option>
                        <option value="Sedekah">🎁 Sedekah</option>
                        <option value="Sewa">🏢 Sewa</option>
                        <option value="Usaha">🏪 Usaha</option>
                        <option value="Lain-lain">📋 Lain-lain</option>
                    </select>
                    @error('kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Donasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Donasi (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" required min="1000" step="1000"
                           placeholder="Contoh: 50000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Donasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tanggal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="metode_pembayaran" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">— Pilih Metode —</option>
                        <option value="Tunai">💵 Tunai</option>
                        <option value="Transfer Bank">🏦 Transfer Bank</option>
                        <option value="E-Wallet (GoPay)">📱 E-Wallet (GoPay)</option>
                        <option value="E-Wallet (OVO)">📱 E-Wallet (OVO)</option>
                        <option value="E-Wallet (Dana)">📱 E-Wallet (Dana)</option>
                        <option value="QRIS">📲 QRIS</option>
                    </select>
                    @error('metode_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bukti Transfer (Optional) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Transfer/Pembayaran (Opsional)
                    </label>
                    <input type="file" name="bukti_transfer" accept="image/*,.pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 2MB)</p>
                    @error('bukti_transfer')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" rows="3" 
                              placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    @error('keterangan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="reset" 
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-paper-plane"></i> Kirim Donasi
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Pemasukan -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-history text-blue-600"></i> Riwayat Pemasukan
        </h2>

        @if(isset($pemasukan) && $pemasukan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pemasukan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->sumber ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status == 'verified')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                    </span>
                                @elseif($item->status == 'rejected')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('jamaah.pemasukan.detail', $item->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $pemasukan->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Belum ada riwayat pemasukan.</p>
                <p class="text-gray-400 text-sm">Silakan input donasi pertama Anda di atas.</p>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Semua donasi akan diverifikasi oleh pengurus keuangan</li>
                        <li>Upload bukti transfer untuk mempercepat proses verifikasi</li>
                        <li>Anda akan mendapatkan notifikasi setelah donasi diverifikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Format input jumlah dengan titik pemisah ribuan
    document.querySelector('input[name="jumlah"]').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });

    // Preview gambar bukti transfer
    document.querySelector('input[name="bukti_transfer"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.size > 2048000) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            this.value = '';
        }
    });
</script>
@endpush