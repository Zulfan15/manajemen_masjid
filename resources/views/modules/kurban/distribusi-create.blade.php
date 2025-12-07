@extends('layouts.app')

@section('title', 'Tambah Distribusi Kurban')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-plus text-green-700 mr-2"></i>Tambah Distribusi Daging
                </h1>
                <p class="text-gray-600 mt-2">Kurban: <strong>{{ $kurban->nomor_kurban }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.distribusi.store', $kurban) }}" class="space-y-6">
            @csrf

            <!-- Row 1: Peserta & Penerima -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Peserta Kurban (Opsional)</label>
                <select name="peserta_kurban_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Peserta (Opsional) --</option>
                    @foreach($pesertaKurbans as $pesertaKurban)
                        <option value="{{ $pesertaKurban->id }}" {{ old('peserta_kurban_id') == $pesertaKurban->id ? 'selected' : '' }}>
                            {{ $pesertaKurban->nama_peserta }} ({{ number_format($pesertaKurban->jumlah_bagian, 2) }} bagian)
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500 mt-1 block">Pilih jika distribusi untuk salah satu peserta</small>
                @error('peserta_kurban_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penerima Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Data penerima daging kurban (bisa berbeda dengan peserta)</p>
            </div>

            <!-- Row 2: Nama & Identitas Penerima -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="penerima_nama" value="{{ old('penerima_nama') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nama lengkap penerima" required>
                    @error('penerima_nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon Penerima</label>
                    <input type="text" name="penerima_nomor_telepon" value="{{ old('penerima_nomor_telepon') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nomor telepon penerima">
                    @error('penerima_nomor_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat Penerima -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Penerima</label>
                <textarea name="penerima_alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Alamat lengkap penerima">{{ old('penerima_alamat') }}</textarea>
                @error('penerima_alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Jenis Distribusi & Berat Daging -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Distribusi <span class="text-red-500">*</span></label>
                    <select name="jenis_distribusi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="keluarga_peserta" {{ old('jenis_distribusi') === 'keluarga_peserta' ? 'selected' : '' }}>Keluarga Peserta</option>
                        <option value="fakir_miskin" {{ old('jenis_distribusi') === 'fakir_miskin' ? 'selected' : '' }}>Fakir Miskin</option>
                        <option value="saudara" {{ old('jenis_distribusi') === 'saudara' ? 'selected' : '' }}>Saudara</option>
                        <option value="kerabat" {{ old('jenis_distribusi') === 'kerabat' ? 'selected' : '' }}>Kerabat</option>
                        <option value="lainnya" {{ old('jenis_distribusi') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_distribusi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Daging (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="berat_daging" value="{{ old('berat_daging') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: 25.50" required>
                    @error('berat_daging')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Estimasi Harga -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estimasi Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="estimasi_harga" value="{{ old('estimasi_harga') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: 500000" required>
                <small class="text-gray-500 mt-1 block">Harga per kg x berat daging</small>
                @error('estimasi_harga')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('kurban.show', $kurban) }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Distribusi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
