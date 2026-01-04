@extends('layouts.app')

@section('title', 'Detail Pemilihan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-vote-yea text-purple-600 mr-2"></i>{{ $pemilihan->judul }}
                </h1>
                <p class="text-gray-600">Detail dan kelola kandidat pemilihan</p>
            </div>
            <div class="flex gap-2">
                @can('takmir.update')
                <a href="{{ route('takmir.pemilihan.edit', $pemilihan->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @endcan
                <a href="{{ route('takmir.pemilihan.hasil', $pemilihan->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-chart-bar mr-2"></i>Lihat Hasil
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h3>
                    <p class="text-gray-800">{{ $pemilihan->deskripsi }}</p>
                </div>

                <!-- Periode -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Periode Pemilihan</h3>
                    <div class="flex items-center text-gray-800">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        <span>{{ $pemilihan->tanggal_mulai->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center text-gray-800 mt-1">
                        <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                        <span>{{ $pemilihan->tanggal_selesai->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <!-- Status & Stats -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Status & Statistik</h3>
                    <div class="flex items-center mb-2">
                        @if($pemilihan->status === 'draft')
                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium">
                                <i class="fas fa-file-alt mr-1"></i>Draft
                            </span>
                        @elseif($pemilihan->status === 'aktif')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                <i class="fas fa-flag-checkered mr-1"></i>Selesai
                            </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-600">
                        <div><i class="fas fa-users mr-2"></i>{{ $pemilihan->kandidat_count }} Kandidat</div>
                        <div><i class="fas fa-vote-yea mr-2"></i>{{ $pemilihan->votes_count }} Suara</div>
                        <div>
                            <i class="fas fa-eye mr-2"></i>
                            Hasil: {{ $pemilihan->tampilkan_hasil ? 'Ditampilkan' : 'Disembunyikan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kandidat Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-users text-purple-600 mr-2"></i>Daftar Kandidat
                </h2>
                @can('takmir.create')
                <button onclick="openAddKandidatModal()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Kandidat
                </button>
                @endcan
            </div>

            @if($pemilihan->kandidat->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($pemilihan->kandidat as $kandidat)
                <div class="border rounded-lg p-4 hover:shadow-lg transition duration-200">
                    <!-- Nomor Urut -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white text-center py-2 rounded-t-lg -mt-4 -mx-4 mb-4">
                        <div class="text-sm font-medium">Kandidat Nomor</div>
                        <div class="text-3xl font-bold">{{ $kandidat->nomor_urut }}</div>
                    </div>

                    <!-- Foto -->
                    <div class="flex justify-center mb-4">
                        <img src="{{ $kandidat->foto_url }}" 
                             alt="{{ $kandidat->takmir->nama }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-purple-100">
                    </div>

                    <!-- Info -->
                    <div class="text-center mb-4">
                        <h3 class="font-bold text-gray-800">{{ $kandidat->takmir->nama }}</h3>
                        <p class="text-sm text-gray-600">{{ $kandidat->takmir->jabatan }}</p>
                    </div>

                    <!-- Visi Misi -->
                    <div class="text-sm mb-4">
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700">Visi:</span>
                            <p class="text-gray-600 line-clamp-2">{{ $kandidat->visi }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Misi:</span>
                            <p class="text-gray-600 line-clamp-2">{{ $kandidat->misi }}</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $kandidat->votes_count }}</div>
                            <div class="text-sm text-gray-600">Suara</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        @can('takmir.update')
                        <button onclick="editKandidat({{ $kandidat->id }})" 
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg text-sm transition duration-200">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        @endcan
                        @can('takmir.delete')
                        <form action="{{ route('takmir.pemilihan.kandidat.destroy', [$pemilihan->id, $kandidat->id]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin hapus kandidat ini?');"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm transition duration-200">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600 text-lg mb-4">Belum ada kandidat yang ditambahkan</p>
                @can('takmir.create')
                <button onclick="openAddKandidatModal()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Kandidat Pertama
                </button>
                @endcan
            </div>
            @endif
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

<!-- Modal Tambah/Edit Kandidat -->
<div id="kandidatModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">
                Tambah Kandidat
            </h3>
            <form id="kandidatForm" action="" method="POST">
                @csrf
                <div id="methodField"></div>

                <!-- Pilih Takmir -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Pengurus <span class="text-red-500">*</span>
                    </label>
                    <select name="takmir_id" id="takmir_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">-- Pilih Pengurus --</option>
                        @foreach($takmirAktif as $takmir)
                        <option value="{{ $takmir->id }}">{{ $takmir->nama }} - {{ $takmir->jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nomor Urut -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Urut <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nomor_urut" id="nomor_urut" min="1" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>

                <!-- Visi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Visi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="visi" id="visi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2" required></textarea>
                </div>

                <!-- Misi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Misi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="misi" id="misi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2" required></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg">
                        Simpan
                    </button>
                    <button type="button" onclick="closeKandidatModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddKandidatModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Kandidat';
        document.getElementById('kandidatForm').action = '{{ route("takmir.pemilihan.kandidat.store", $pemilihan->id) }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('kandidatForm').reset();
        document.getElementById('kandidatModal').classList.remove('hidden');
    }

    function closeKandidatModal() {
        document.getElementById('kandidatModal').classList.add('hidden');
    }

    function editKandidat(id) {
        // Implementasi edit (untuk saat ini redirect ke halaman edit terpisah)
        alert('Fitur edit kandidat akan segera tersedia');
    }

    // Close modal on outside click
    document.getElementById('kandidatModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKandidatModal();
        }
    });
</script>
@endpush
@endsection
