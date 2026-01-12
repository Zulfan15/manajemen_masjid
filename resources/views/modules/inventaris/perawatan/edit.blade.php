@extends('layouts.app')

@section('title', 'Edit Jadwal Perawatan')

@section('content')
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('inventaris.index') }}" class="hover:text-teal-600">Inventaris</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('inventaris.perawatan.index') }}" class="hover:text-teal-600">Jadwal Perawatan</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-700">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-yellow-600 mr-3"></i>
                Edit Jadwal Perawatan
            </h1>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('inventaris.perawatan.update', $jadwal->jadwal_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Aset -->
                    <div class="md:col-span-2">
                        <label for="aset_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="aset_id" id="aset_id" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('aset_id') border-red-500 @enderror">
                            <option value="">-- Pilih Aset --</option>
                            @foreach($asets as $aset)
                                <option value="{{ $aset->aset_id }}" {{ old('aset_id', $jadwal->aset_id) == $aset->aset_id ? 'selected' : '' }}>
                                    {{ $aset->nama_aset }} ({{ $aset->kategori ?? 'Tanpa Kategori' }})
                                </option>
                            @endforeach
                        </select>
                        @error('aset_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Jadwal -->
                    <div>
                        <label for="tanggal_jadwal" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Jadwal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_jadwal" id="tanggal_jadwal" required
                            value="{{ old('tanggal_jadwal', $jadwal->tanggal_jadwal) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('tanggal_jadwal') border-red-500 @enderror">
                        @error('tanggal_jadwal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Perawatan -->
                    <div>
                        <label for="jenis_perawatan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Perawatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_perawatan" id="jenis_perawatan" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('jenis_perawatan') border-red-500 @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jenisPerawatan as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis_perawatan', $jadwal->jenis_perawatan) == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                            @if(!in_array($jadwal->jenis_perawatan, $jenisPerawatan))
                                <option value="{{ $jadwal->jenis_perawatan }}" selected>{{ $jadwal->jenis_perawatan }}</option>
                            @endif
                        </select>
                        @error('jenis_perawatan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="dijadwalkan" {{ old('status', $jadwal->status) == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>
                                Selesai</option>
                            <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Petugas -->
                    <div>
                        <label for="id_petugas" class="block text-sm font-semibold text-gray-700 mb-2">
                            Petugas (Opsional)
                        </label>
                        <select name="id_petugas" id="id_petugas"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('id_petugas') border-red-500 @enderror">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $user)
                                <option value="{{ $user->id }}" {{ old('id_petugas', $jadwal->id_petugas) == $user->id ? 'selected' : '' }}>
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
                        <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="note" id="note" rows="4"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('note') border-red-500 @enderror"
                            placeholder="Catatan tambahan tentang perawatan...">{{ old('note', $jadwal->note) }}</textarea>
                        @error('note')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('inventaris.perawatan.index') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection