@extends('layouts.app')

@section('title', 'Edit Pemilihan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-edit text-yellow-600 mr-2"></i>Edit Pemilihan
            </h1>
            <p class="text-gray-600">{{ $pemilihan->judul }}</p>
        </div>

        <!-- Warning jika sudah ada votes -->
        @if($pemilihan->votes_count > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Pemilihan ini sudah memiliki {{ $pemilihan->votes_count }} suara. 
                        Hati-hati saat mengubah data karena dapat mempengaruhi hasil pemilihan.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('takmir.pemilihan.update', $pemilihan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Pemilihan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           id="judul" 
                           value="{{ old('judul', $pemilihan->judul) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('judul') border-red-500 @enderror"
                           required>
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" 
                              id="deskripsi" 
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('deskripsi') border-red-500 @enderror"
                              required>{{ old('deskripsi', $pemilihan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="tanggal_mulai" 
                               id="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', $pemilihan->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('tanggal_mulai') border-red-500 @enderror"
                               required>
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="tanggal_selesai" 
                               id="tanggal_selesai" 
                               value="{{ old('tanggal_selesai', $pemilihan->tanggal_selesai->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('tanggal_selesai') border-red-500 @enderror"
                               required>
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="draft" {{ old('status', $pemilihan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ old('status', $pemilihan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status', $pemilihan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tampilkan Hasil -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="tampilkan_hasil" 
                               id="tampilkan_hasil"
                               value="1"
                               {{ old('tampilkan_hasil', $pemilihan->tampilkan_hasil) ? 'checked' : '' }}
                               class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Tampilkan hasil secara real-time kepada pemilih
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Pemilihan
                    </button>
                    <a href="{{ route('takmir.pemilihan.show', $pemilihan->id) }}" 
                       class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('takmir.pemilihan.show', $pemilihan->id) }}" 
               class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail Pemilihan
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validasi tanggal
    document.getElementById('tanggal_selesai').addEventListener('change', function() {
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = this.value;
        
        if (tanggalMulai && tanggalSelesai) {
            if (new Date(tanggalSelesai) <= new Date(tanggalMulai)) {
                alert('Tanggal selesai harus setelah tanggal mulai!');
                this.value = '';
            }
        }
    });
</script>
@endpush
@endsection
