@extends('layouts.app')

@section('title', 'Tambah Donasi - ' . $jamaah->nama_lengkap)

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('jamaah.show', $jamaah) }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-hand-holding-usd text-green-600 mr-2"></i>Catat Donasi Baru
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $jamaah->nama_lengkap }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-2xl">
            <form action="{{ route('jamaah.donasi.store', $jamaah) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-white rounded-lg shadow p-6 space-y-6">
                    {{-- Jumlah Donasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Donasi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="amount" value="{{ old('amount') }}" min="1"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg @error('amount') border-red-500 @enderror"
                                placeholder="0" required>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Donasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Donasi <span class="text-red-500">*</span>
                        </label>
                        <select name="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Jenis Donasi --</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Donasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Donasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="donation_date" value="{{ old('donation_date', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('donation_date') border-red-500 @enderror"
                            required>
                        @error('donation_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi
                            </option>
                            <option value="confirmed" {{ old('status', 'confirmed') == 'confirmed' ? 'selected' : '' }}>
                                Dikonfirmasi</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Pilih Metode --</option>
                            <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer
                                Bank</option>
                            <option value="ewallet" {{ old('metode_pembayaran') == 'ewallet' ? 'selected' : '' }}>E-Wallet
                            </option>
                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    {{-- Bukti Transfer --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer (Opsional)</label>
                        <input type="file" name="bukti_transfer" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan tentang donasi..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('keterangan') }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('jamaah.show', $jamaah) }}"
                            class="flex-1 px-4 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-save mr-1"></i> Simpan Donasi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection