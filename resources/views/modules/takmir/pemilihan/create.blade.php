@extends('layouts.app')

@section('title', 'Buat Pemilihan Baru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-plus-circle text-purple-600 mr-2"></i>Buat Pemilihan Baru
            </h1>
            <p class="text-gray-600">Buat pemilihan Ketua DKM atau jabatan lainnya</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('takmir.pemilihan.store') }}" method="POST">
                @csrf

                <!-- Judul -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Pemilihan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           id="judul" 
                           value="{{ old('judul') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('judul') border-red-500 @enderror"
                           placeholder="Contoh: Pemilihan Ketua DKM Periode 2026-2028"
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
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Jelaskan tujuan dan ketentuan pemilihan ini..."
                              required>{{ old('deskripsi') }}</textarea>
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
                               value="{{ old('tanggal_mulai') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('tanggal_mulai') border-red-500 @enderror"
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
                               value="{{ old('tanggal_selesai') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('tanggal_selesai') border-red-500 @enderror"
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Pilih "Draft" jika ingin menambahkan kandidat dulu, atau "Aktif" untuk langsung mulai pemilihan
                    </p>
                </div>

                <!-- Tampilkan Hasil -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="tampilkan_hasil" 
                               id="tampilkan_hasil"
                               value="1"
                               {{ old('tampilkan_hasil', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Tampilkan hasil secara real-time kepada pemilih
                        </span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1 ml-6">
                        Jika dicentang, pemilih dapat melihat hasil sementara selama pemilihan berlangsung
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Tips:</h3>
                            <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                                <li>Setelah membuat pemilihan, Anda perlu menambahkan kandidat</li>
                                <li>Pastikan periode pemilihan cukup untuk semua pemilih memberikan suara</li>
                                <li>Status "Draft" berguna untuk persiapan sebelum launching</li>
                                <li>Kandidat harus dari pengurus yang sudah terdaftar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pemilihan
                    </button>
                    <a href="{{ route('takmir.pemilihan.index') }}" 
                       class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('takmir.pemilihan.index') }}" 
               class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pemilihan
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validasi tanggal selesai harus setelah tanggal mulai
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

    // Set minimum tanggal mulai ke hari ini
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const hours = String(today.getHours()).padStart(2, '0');
    const minutes = String(today.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.getElementById('tanggal_mulai').min = minDateTime;
    document.getElementById('tanggal_selesai').min = minDateTime;
</script>
@endpush
@endsection
