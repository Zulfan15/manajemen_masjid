@extends('layouts.app')

@section('title', 'Edit Jamaah - ' . $jamaah->nama_lengkap)

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
                    <i class="fas fa-user-edit text-green-600 mr-2"></i>Edit Data Jamaah
                </h1>
                <p class="text-gray-600 mt-1">{{ $jamaah->nama_lengkap }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('jamaah.update', $jamaah) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Data Pribadi --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card text-green-600 mr-2"></i>Data Pribadi
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama Lengkap --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                                   required>
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" 
                                   name="tempat_lahir" 
                                   value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" 
                                   name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Status Pernikahan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pernikahan</label>
                            <select name="status_pernikahan" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="belum_menikah" {{ old('status_pernikahan', $jamaah->status_pernikahan) == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="menikah" {{ old('status_pernikahan', $jamaah->status_pernikahan) == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="duda" {{ old('status_pernikahan', $jamaah->status_pernikahan) == 'duda' ? 'selected' : '' }}>Duda</option>
                                <option value="janda" {{ old('status_pernikahan', $jamaah->status_pernikahan) == 'janda' ? 'selected' : '' }}>Janda</option>
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                            <input type="text" 
                                   name="pekerjaan" 
                                   value="{{ old('pekerjaan', $jamaah->pekerjaan) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $edu)
                                    <option value="{{ $edu }}" {{ old('pendidikan_terakhir', $jamaah->pendidikan_terakhir) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP/WhatsApp</label>
                            <input type="text" 
                                   name="no_hp" 
                                   value="{{ old('no_hp', $jamaah->no_hp) }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>Alamat
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" 
                                      rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('alamat', $jamaah->alamat) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT</label>
                            <input type="text" name="rt" value="{{ old('rt', $jamaah->rt) }}" maxlength="5"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW</label>
                            <input type="text" name="rw" value="{{ old('rw', $jamaah->rw) }}" maxlength="5"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa</label>
                            <input type="text" name="kelurahan" value="{{ old('kelurahan', $jamaah->kelurahan) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $jamaah->kecamatan) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Foto --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-camera text-green-600 mr-2"></i>Foto Profil
                    </h2>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-4 overflow-hidden" id="preview-container">
                            @if($jamaah->foto)
                                <img src="{{ Storage::url($jamaah->foto) }}" id="preview-image" class="w-32 h-32 rounded-full object-cover">
                            @else
                                <i class="fas fa-user text-4xl text-gray-400" id="placeholder-icon"></i>
                                <img src="" id="preview-image" class="w-32 h-32 rounded-full object-cover hidden">
                            @endif
                        </div>
                        <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden">
                        <label for="foto-input" class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-upload mr-1"></i> Ganti Foto
                        </label>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tags text-green-600 mr-2"></i>Kategori Jamaah
                    </h2>
                    
                    <div class="space-y-2">
                        @php $currentCategories = $jamaah->categories->pluck('id')->toArray(); @endphp
                        @foreach($categories as $cat)
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $cat->id }}"
                                   {{ in_array($cat->id, old('categories', $currentCategories)) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="ml-3 text-sm text-gray-700">{{ $cat->nama }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-toggle-on text-green-600 mr-2"></i>Status
                    </h2>
                    
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer">
                        <input type="checkbox" 
                               name="status_aktif" 
                               value="1"
                               {{ old('status_aktif', $jamaah->status_aktif) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                        <span class="ml-3 text-sm text-gray-700">Jamaah Aktif</span>
                    </label>
                </div>

                {{-- Catatan --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-green-600 mr-2"></i>Catatan
                    </h2>
                    <textarea name="catatan" 
                              rows="4"
                              placeholder="Catatan tambahan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('catatan', $jamaah->catatan) }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-3">
                    <a href="{{ route('jamaah.show', $jamaah) }}" 
                       class="flex-1 px-4 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('foto-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-image');
            const icon = document.getElementById('placeholder-icon');
            img.src = e.target.result;
            img.classList.remove('hidden');
            if (icon) icon.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
