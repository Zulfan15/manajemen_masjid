@extends('layouts.app')

@section('title', 'Edit Pemeriksaan Kondisi')

@section('content')
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('inventaris.index') }}" class="hover:text-orange-600">Inventaris</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('inventaris.kondisi.index') }}" class="hover:text-orange-600">Kondisi Barang</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-700">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-yellow-600 mr-3"></i>
                Edit Pemeriksaan Kondisi
            </h1>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('inventaris.kondisi.update', $kondisi->kondisi_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Aset -->
                    <div class="md:col-span-2">
                        <label for="aset_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="aset_id" id="aset_id" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('aset_id') border-red-500 @enderror">
                            <option value="">-- Pilih Aset --</option>
                            @foreach($asets as $aset)
                                <option value="{{ $aset->aset_id }}" {{ old('aset_id', $kondisi->aset_id) == $aset->aset_id ? 'selected' : '' }}>
                                    {{ $aset->nama_aset }} ({{ $aset->kategori ?? 'Tanpa Kategori' }})
                                </option>
                            @endforeach
                        </select>
                        @error('aset_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pemeriksaan -->
                    <div>
                        <label for="tanggal_pemeriksaan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Pemeriksaan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pemeriksaan" id="tanggal_pemeriksaan" required
                            value="{{ old('tanggal_pemeriksaan', $kondisi->tanggal_pemeriksaan) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('tanggal_pemeriksaan') border-red-500 @enderror">
                        @error('tanggal_pemeriksaan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <label for="kondisi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kondisi <span class="text-red-500">*</span>
                        </label>
                        <select name="kondisi" id="kondisi" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('kondisi') border-red-500 @enderror">
                            <option value="">-- Pilih Kondisi --</option>
                            @foreach($kondisiOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('kondisi', $kondisi->kondisi) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Petugas Pemeriksa (Teks) -->
                    <div>
                        <label for="petugas_pemeriksa" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Pemeriksa (Manual)
                        </label>
                        <input type="text" name="petugas_pemeriksa" id="petugas_pemeriksa"
                            value="{{ old('petugas_pemeriksa', $kondisi->petugas_pemeriksa) }}"
                            placeholder="Isi jika bukan user sistem"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('petugas_pemeriksa') border-red-500 @enderror">
                        @error('petugas_pemeriksa')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Petugas (User) -->
                    <div>
                        <label for="id_petugas" class="block text-sm font-semibold text-gray-700 mb-2">
                            Atau Pilih User Petugas
                        </label>
                        <select name="id_petugas" id="id_petugas"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('id_petugas') border-red-500 @enderror">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $user)
                                <option value="{{ $user->id }}" {{ old('id_petugas', $kondisi->id_petugas) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_petugas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan" rows="4"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('catatan') border-red-500 @enderror"
                            placeholder="Catatan hasil pemeriksaan...">{{ old('catatan', $kondisi->catatan) }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('inventaris.kondisi.index') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection