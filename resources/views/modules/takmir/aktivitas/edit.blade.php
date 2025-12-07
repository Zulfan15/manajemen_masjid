@extends('layouts.app')

@section('title', 'Edit Aktivitas Harian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Aktivitas Harian</h1>
            <p class="text-gray-600 mt-1">Ubah data aktivitas harian pengurus</p>
        </div>
        <a href="{{ route('takmir.aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Warning untuk pengurus (24 jam rule) -->
    @if(auth()->user()->hasRole('pengurus_takmir'))
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
            <div>
                <p class="text-sm font-medium text-yellow-800">Perhatian!</p>
                <p class="text-sm text-yellow-700">Aktivitas hanya dapat diedit dalam 24 jam setelah dibuat.</p>
                <p class="text-sm text-yellow-600 mt-1">
                    Dibuat: {{ $aktivita->created_at->format('d/m/Y H:i') }} 
                    ({{ $aktivita->created_at->diffForHumans() }})
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('takmir.aktivitas.update', $aktivita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Pilih Pengurus (Only for Admin) -->
            @if(auth()->user()->hasRole('admin_takmir') && $pengurusList)
            <div class="mb-6">
                <label for="takmir_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pengurus <span class="text-red-500">*</span>
                </label>
                <select name="takmir_id" id="takmir_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('takmir_id') border-red-500 @enderror">
                    <option value="">Pilih Pengurus</option>
                    @foreach($pengurusList as $pengurus)
                        <option value="{{ $pengurus->id }}" 
                                {{ (old('takmir_id') ?? $aktivita->takmir_id) == $pengurus->id ? 'selected' : '' }}>
                            {{ $pengurus->nama }} - {{ $pengurus->jabatan }}
                        </option>
                    @endforeach
                </select>
                @error('takmir_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <!-- Info untuk pengurus -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Aktivitas ini tercatat atas nama:</p>
                        <p class="text-sm text-blue-700">{{ $aktivita->takmir->nama }} - {{ $aktivita->takmir->jabatan }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tanggal -->
            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Aktivitas <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" id="tanggal" required
                       max="{{ date('Y-m-d') }}"
                       value="{{ old('tanggal', $aktivita->tanggal->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Tanggal tidak boleh lebih dari hari ini
                </p>
            </div>

            <!-- Jenis Aktivitas -->
            <div class="mb-6">
                <label for="jenis_aktivitas" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Aktivitas <span class="text-red-500">*</span>
                </label>
                <select name="jenis_aktivitas" id="jenis_aktivitas" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_aktivitas') border-red-500 @enderror">
                    <option value="">Pilih Jenis Aktivitas</option>
                    <option value="Ibadah" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Ibadah' ? 'selected' : '' }}>Ibadah</option>
                    <option value="Kebersihan" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="Administrasi" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                    <option value="Pengajaran" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Pengajaran' ? 'selected' : '' }}>Pengajaran</option>
                    <option value="Pembinaan" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                    <option value="Keuangan" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                    <option value="Sosial" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="Lainnya" {{ (old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis_aktivitas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Aktivitas <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                          placeholder="Jelaskan secara detail aktivitas yang dilakukan...">{{ old('deskripsi', $aktivita->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Minimal 10 karakter
                </p>
            </div>

            <!-- Durasi -->
            <div class="mb-6">
                <label for="durasi_jam" class="block text-sm font-medium text-gray-700 mb-2">
                    Durasi (jam)
                </label>
                <input type="number" name="durasi_jam" id="durasi_jam" 
                       min="0.5" max="24" step="0.5"
                       value="{{ old('durasi_jam', $aktivita->durasi_jam) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('durasi_jam') border-red-500 @enderror"
                       placeholder="Contoh: 2.5">
                @error('durasi_jam')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Opsional. Minimal 0.5 jam (30 menit), maksimal 24 jam
                </p>
            </div>

            <!-- Bukti Foto -->
            <div class="mb-6">
                <label for="bukti_foto" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Foto
                </label>
                
                <!-- Foto saat ini -->
                @if($aktivita->bukti_foto)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                    <img src="{{ $aktivita->bukti_foto_url }}" alt="Foto saat ini" class="max-w-xs rounded-lg border border-gray-300">
                </div>
                @endif
                
                <input type="file" name="bukti_foto" id="bukti_foto" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bukti_foto') border-red-500 @enderror">
                @error('bukti_foto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Opsional. Upload foto baru untuk mengganti foto lama. Format: JPG, PNG, JPEG. Maksimal 2MB
                </p>
                
                <!-- Preview -->
                <div id="preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview foto baru:</p>
                    <img id="preview-image" src="" alt="Preview" class="max-w-xs rounded-lg border border-gray-300">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Aktivitas
                </button>
                <a href="{{ route('takmir.aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('bukti_foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').classList.remove('hidden');
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview').classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
