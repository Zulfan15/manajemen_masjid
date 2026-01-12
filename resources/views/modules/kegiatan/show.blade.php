@extends('layouts.app')
@section('title', 'Detail Kegiatan')
@section('content')
    <div class="container mx-auto">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 {{ $kegiatan->getStatusBadgeClass() }} text-sm rounded-full">
                            {{ ucfirst($kegiatan->status) }}
                        </span>
                        <span class="px-3 py-1 {{ $kegiatan->getJenisBadgeClass() }} text-sm rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan)) }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas {{ $kegiatan->getKategoriIcon() }} text-green-700 mr-2"></i>
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('kegiatan.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if (!auth()->user()->isSuperAdmin())
                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-4">
                    <button class="tab-button border-b-2 border-green-700 text-green-700 py-3 px-4 font-medium"
                        onclick="showTab('detail')">
                        <i class="fas fa-info-circle mr-2"></i>Detail
                    </button>
                    <button class="tab-button border-b-2 border-transparent text-gray-600 py-3 px-4 hover:text-gray-800"
                        onclick="showTab('peserta')">
                        <i class="fas fa-users mr-2"></i>Peserta ({{ $pesertaStats['total'] }})
                    </button>
                    @if (!auth()->user()->isSuperAdmin() && $kegiatan->status != 'dibatalkan')
                        <button class="tab-button border-b-2 border-transparent text-gray-600 py-3 px-4 hover:text-gray-800"
                            onclick="showTab('daftar')">
                            <i class="fas fa-user-plus mr-2"></i>Pendaftaran
                        </button>
                    @endif
                </nav>
            </div>

            <!-- Tab: Detail -->
            <div id="tab-detail">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Info -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi</h3>
                            <p class="text-gray-600">{{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar text-green-700 mr-2"></i>Tanggal & Waktu
                                </h4>
                                <p class="text-gray-600">{{ $kegiatan->tanggal_mulai->format('d F Y') }}</p>
                                @if ($kegiatan->tanggal_selesai)
                                    <p class="text-gray-600">s/d {{ $kegiatan->tanggal_selesai->format('d F Y') }}</p>
                                @endif
                                <p class="text-gray-600 mt-1">
                                    {{ date('H:i', strtotime($kegiatan->waktu_mulai)) }}
                                    @if ($kegiatan->waktu_selesai)
                                        - {{ date('H:i', strtotime($kegiatan->waktu_selesai)) }}
                                    @endif
                                    WIB
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>Lokasi
                                </h4>
                                <p class="text-gray-600">{{ $kegiatan->lokasi }}</p>
                            </div>
                        </div>

                        @if ($kegiatan->pic)
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-tie text-green-700 mr-2"></i>Penanggung Jawab
                                </h4>
                                <p class="text-gray-600">{{ $kegiatan->pic }}</p>
                                @if ($kegiatan->kontak_pic)
                                    <p class="text-gray-600">{{ $kegiatan->kontak_pic }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($kegiatan->catatan)
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note text-green-700 mr-2"></i>Catatan
                                </h4>
                                <p class="text-gray-600">{{ $kegiatan->catatan }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar Stats -->
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">Peserta</h4>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $kegiatan->jumlah_peserta }}
                                @if ($kegiatan->kuota_peserta)
                                    / {{ $kegiatan->kuota_peserta }}
                                @endif
                            </p>
                            <p class="text-sm text-blue-600">
                                @if ($kegiatan->kuota_peserta)
                                    Sisa: {{ $kegiatan->sisaKuota() }} tempat
                                @else
                                    Unlimited
                                @endif
                            </p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Kehadiran</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $pesertaStats['hadir'] }}</p>
                            <p class="text-sm text-green-600">orang hadir</p>
                        </div>

                        @if ($kegiatan->budget)
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-purple-800 mb-2">Budget</h4>
                                <p class="text-lg font-bold text-purple-600">Rp
                                    {{ number_format($kegiatan->budget, 0, ',', '.') }}</p>
                                @if ($kegiatan->realisasi_biaya)
                                    <p class="text-sm text-purple-600">Realisasi: Rp
                                        {{ number_format($kegiatan->realisasi_biaya, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="space-y-2">
                            @if ($kegiatan->butuh_pendaftaran)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>Butuh Pendaftaran
                                </p>
                            @endif
                            @if ($kegiatan->sertifikat_tersedia)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-certificate text-yellow-600 mr-2"></i>Sertifikat Tersedia
                                </p>
                            @endif
                            @if ($kegiatan->is_recurring)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-redo text-blue-600 mr-2"></i>Kegiatan Berulang
                                    @if ($kegiatan->recurring_type)
                                        ({{ ucfirst($kegiatan->recurring_type) }})
                                    @endif
                                </p>
                            @endif
                        </div>

                        @if (!auth()->user()->isSuperAdmin() && in_array($kegiatan->status, ['direncanakan', 'berlangsung']))
                            <div class="space-y-2">
                                <a href="{{ route('kegiatan.absensi', $kegiatan->id) }}"
                                    class="block w-full px-4 py-3 bg-orange-600 text-white text-center rounded-lg hover:bg-orange-700 transition">
                                    <i class="fas fa-clipboard-check mr-2"></i>Kelola Absensi
                                </a>
                                
                                <!-- Broadcast Notification -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" 
                                        class="w-full px-4 py-3 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700 transition">
                                        <i class="fas fa-bell mr-2"></i>Broadcast Notifikasi
                                    </button>
                                    <div x-show="open" @click.away="open = false" 
                                        class="absolute right-0 mt-2 w-full bg-white rounded-lg shadow-lg z-10 border">
                                        <form action="{{ route('kegiatan.broadcast', $kegiatan->id) }}" method="POST" class="p-3">
                                            @csrf
                                            <p class="text-sm text-gray-600 mb-3">Kirim notifikasi ke semua jamaah:</p>
                                            <select name="tipe" class="w-full px-3 py-2 border rounded-lg mb-3 text-sm">
                                                <option value="info">Info Kegiatan</option>
                                                <option value="reminder">Pengingat</option>
                                                <option value="pengumuman">Pengumuman</option>
                                            </select>
                                            <button type="submit" class="w-full px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                                                <i class="fas fa-paper-plane mr-1"></i>Kirim
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab: Peserta -->
            <div id="tab-peserta" class="hidden">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Peserta</h3>
                    <div class="text-sm text-gray-600">
                        <span class="mr-4"><i class="fas fa-users mr-1"></i>Total: {{ $pesertaStats['total'] }}</span>
                        <span class="mr-4 text-green-600"><i class="fas fa-check mr-1"></i>Hadir:
                            {{ $pesertaStats['hadir'] }}</span>
                    </div>
                </div>

                @if ($kegiatan->peserta->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Absensi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Daftar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($kegiatan->peserta as $index => $peserta)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800">{{ $peserta->nama_peserta }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if ($peserta->email)
                                                <p>{{ $peserta->email }}</p>
                                            @endif
                                            @if ($peserta->no_hp)
                                                <p>{{ $peserta->no_hp }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 {{ $peserta->getStatusBadgeClass() }} text-xs rounded">
                                                {{ ucfirst($peserta->status_pendaftaran) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($peserta->absensi)
                                                <span
                                                    class="px-2 py-1 {{ $peserta->absensi->getStatusBadgeClass() }} text-xs rounded">
                                                    {{ ucfirst(str_replace('_', ' ', $peserta->absensi->status_kehadiran)) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">Belum absen</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $peserta->tanggal_daftar->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-users text-5xl mb-3 text-gray-300"></i>
                        <p>Belum ada peserta yang mendaftar</p>
                    </div>
                @endif
            </div>

            <!-- Tab: Pendaftaran -->
            @if (!auth()->user()->isSuperAdmin() && $kegiatan->status != 'dibatalkan')
                <div id="tab-daftar" class="hidden">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Pendaftaran Peserta</h3>

                        @if ($kegiatan->isFull())
                            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Kuota peserta
                                    sudah penuh!</p>
                            </div>
                        @elseif($kegiatan->sudahDaftar(auth()->id()))
                            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i>Anda sudah terdaftar di
                                    kegiatan ini</p>
                            </div>
                        @else
                            <form action="{{ route('kegiatan.register', $kegiatan->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                    <input type="text" name="nama_peserta" value="{{ auth()->user()->name }}"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                                    <input type="text" name="no_hp" value="{{ auth()->user()->phone ?? '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                    <textarea name="alamat" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ auth()->user()->address ?? '' }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                                        (opsional)</label>
                                    <textarea name="keterangan" rows="2" placeholder="Pertanyaan, kebutuhan khusus, dll"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                                    <i class="fas fa-check mr-2"></i>Daftar Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all tabs
            document.getElementById('tab-detail').classList.add('hidden');
            document.getElementById('tab-peserta').classList.add('hidden');
            @if (!auth()->user()->isSuperAdmin())
                const daftarTab = document.getElementById('tab-daftar');
                if (daftarTab) daftarTab.classList.add('hidden');
            @endif

            // Show selected tab
            document.getElementById('tab-' + tab).classList.remove('hidden');

            // Update button styles
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(btn => {
                btn.classList.remove('border-green-700', 'text-green-700');
                btn.classList.add('border-transparent', 'text-gray-600');
            });
            event.target.closest('.tab-button').classList.remove('border-transparent', 'text-gray-600');
            event.target.closest('.tab-button').classList.add('border-green-700', 'text-green-700');
        }
    </script>
@endsection
