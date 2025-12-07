@extends('layouts.app')

@section('title', 'Edit Distribusi Kurban')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-edit text-green-700 mr-2"></i>Edit Distribusi Daging
                </h1>
                <p class="text-gray-600 mt-2">Penerima: <strong>{{ $distribusi->penerima_nama }}</strong> | Kurban: <strong>{{ $kurban->nomor_kurban }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.distribusi.update', [$kurban, $distribusi]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Peserta -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Peserta Kurban (Opsional)</label>
                <select name="peserta_kurban_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Peserta (Opsional) --</option>
                    @foreach($pesertaKurbans as $pesertaKurban)
                        <option value="{{ $pesertaKurban->id }}" {{ old('peserta_kurban_id', $distribusi->peserta_kurban_id) == $pesertaKurban->id ? 'selected' : '' }}>
                            {{ $pesertaKurban->nama_peserta }} ({{ number_format($pesertaKurban->jumlah_bagian, 2) }} bagian)
                        </option>
                    @endforeach
                </select>
                @error('peserta_kurban_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 2: Nama & Telepon Penerima -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="penerima_nama" value="{{ old('penerima_nama', $distribusi->penerima_nama) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('penerima_nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon Penerima</label>
                    <input type="text" name="penerima_nomor_telepon" value="{{ old('penerima_nomor_telepon', $distribusi->penerima_nomor_telepon) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('penerima_nomor_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat Penerima -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Penerima</label>
                <textarea name="penerima_alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('penerima_alamat', $distribusi->penerima_alamat) }}</textarea>
                @error('penerima_alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Jenis Distribusi & Berat Daging -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Distribusi <span class="text-red-500">*</span></label>
                    <select name="jenis_distribusi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="keluarga_peserta" {{ old('jenis_distribusi', $distribusi->jenis_distribusi) === 'keluarga_peserta' ? 'selected' : '' }}>Keluarga Peserta</option>
                        <option value="fakir_miskin" {{ old('jenis_distribusi', $distribusi->jenis_distribusi) === 'fakir_miskin' ? 'selected' : '' }}>Fakir Miskin</option>
                        <option value="saudara" {{ old('jenis_distribusi', $distribusi->jenis_distribusi) === 'saudara' ? 'selected' : '' }}>Saudara</option>
                        <option value="kerabat" {{ old('jenis_distribusi', $distribusi->jenis_distribusi) === 'kerabat' ? 'selected' : '' }}>Kerabat</option>
                        <option value="lainnya" {{ old('jenis_distribusi', $distribusi->jenis_distribusi) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_distribusi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Daging (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="berat_daging" value="{{ old('berat_daging', $distribusi->berat_daging) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('berat_daging')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Estimasi Harga & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estimasi Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="estimasi_harga" value="{{ old('estimasi_harga', $distribusi->estimasi_harga) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('estimasi_harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Distribusi <span class="text-red-500">*</span></label>
                    <select name="status_distribusi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="belum_didistribusi" {{ old('status_distribusi', $distribusi->status_distribusi) === 'belum_didistribusi' ? 'selected' : '' }}>Belum Didistribusi</option>
                        <option value="sedang_disiapkan" {{ old('status_distribusi', $distribusi->status_distribusi) === 'sedang_disiapkan' ? 'selected' : '' }}>Sedang Disiapkan</option>
                        <option value="sudah_didistribusi" {{ old('status_distribusi', $distribusi->status_distribusi) === 'sudah_didistribusi' ? 'selected' : '' }}>Sudah Didistribusi</option>
                    </select>
                    @error('status_distribusi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('catatan', $distribusi->catatan) }}</textarea>
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
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
