@extends('layouts.app')

@section('title', 'Tambah Jamaah Baru')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('jamaah.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>Tambah Jamaah Baru
                </h1>
                <p class="text-gray-600 mt-1">Daftarkan jamaah baru ke dalam sistem</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('jamaah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                                   value="{{ old('nama_lengkap') }}"
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
                                   value="{{ old('tempat_lahir') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" 
                                   name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Status Pernikahan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pernikahan</label>
                            <select name="status_pernikahan" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="belum_menikah" {{ old('status_pernikahan') == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="menikah" {{ old('status_pernikahan') == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="duda" {{ old('status_pernikahan') == 'duda' ? 'selected' : '' }}>Duda</option>
                                <option value="janda" {{ old('status_pernikahan') == 'janda' ? 'selected' : '' }}>Janda</option>
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                            <input type="text" 
                                   name="pekerjaan" 
                                   value="{{ old('pekerjaan') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD/Sederajat</option>
                                <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP/Sederajat</option>
                                <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA/Sederajat</option>
                                <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP/WhatsApp</label>
                            <input type="text" 
                                   name="no_hp" 
                                   value="{{ old('no_hp') }}"
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
                        {{-- Alamat Detail --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" 
                                      rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('alamat') }}</textarea>
                        </div>

                        {{-- RT --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT</label>
                            <input type="text" 
                                   name="rt" 
                                   value="{{ old('rt') }}"
                                   maxlength="5"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- RW --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW</label>
                            <input type="text" 
                                   name="rw" 
                                   value="{{ old('rw') }}"
                                   maxlength="5"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Kelurahan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa</label>
                            <input type="text" 
                                   name="kelurahan" 
                                   value="{{ old('kelurahan') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" 
                                   name="kecamatan" 
                                   value="{{ old('kecamatan') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- Akun Login (Opsional) --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-key text-green-600 mr-2"></i>Akun Login (Opsional)
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">Buat akun login jika jamaah ingin mengakses sistem</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" 
                                   name="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Default: password123</p>
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
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-4" id="preview-container">
                            <i class="fas fa-user text-4xl text-gray-400" id="placeholder-icon"></i>
                            <img src="" id="preview-image" class="w-32 h-32 rounded-full object-cover hidden">
                        </div>
                        <input type="file" 
                               name="foto" 
                               id="foto-input"
                               accept="image/*"
                               class="hidden">
                        <label for="foto-input" 
                               class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-upload mr-1"></i> Pilih Foto
                        </label>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tags text-green-600 mr-2"></i>Kategori Jamaah
                    </h2>
                    
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $cat->id }}"
                                   {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="ml-3 text-sm text-gray-700">{{ $cat->nama }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* Jika tidak dipilih, default: Umum</p>
                </div>

                {{-- Catatan --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-green-600 mr-2"></i>Catatan
                    </h2>
                    <textarea name="catatan" 
                              rows="4"
                              placeholder="Catatan tambahan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('catatan') }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-3">
                    <a href="{{ route('jamaah.index') }}" 
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
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-image').classList.remove('hidden');
            document.getElementById('placeholder-icon').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
