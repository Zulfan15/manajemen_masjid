@extends('layouts.app')
@section('title', 'Absensi Kegiatan')
@section('content')
    <div class="container mx-auto">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-clipboard-check text-orange-600 mr-2"></i>Absensi Kegiatan
                    </h1>
                    <p class="text-lg text-gray-700">{{ $kegiatan->nama_kegiatan }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>{{ $kegiatan->tanggal_mulai->format('d F Y') }}
                        <i class="fas fa-clock ml-3 mr-1"></i>{{ date('H:i', strtotime($kegiatan->waktu_mulai)) }} WIB
                    </p>
                </div>
                <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 mb-1">Total Peserta</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $kegiatan->peserta->count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 mb-1">Hadir</p>
                    <p class="text-2xl font-bold text-green-700">{{ $kegiatan->absensi()->hadir()->count() }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-sm text-red-600 mb-1">Tidak Hadir</p>
                    <p class="text-2xl font-bold text-red-700">{{ $kegiatan->absensi()->tidakHadir()->count() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Belum Absen</p>
                    <p class="text-2xl font-bold text-gray-700">
                        {{ $kegiatan->peserta->count() - $kegiatan->absensi->count() }}
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i><strong>Panduan:</strong>
                </p>
                <ul class="text-sm text-yellow-700 ml-6 list-disc space-y-1">
                    <li>Klik pada status kehadiran untuk mengubah/mencatat absensi peserta</li>
                    <li>Absensi dapat diubah jika terjadi kesalahan</li>
                    <li>Pastikan semua peserta sudah tercatat sebelum menutup kegiatan</li>
                </ul>
            </div>

            <!-- Absensi Table -->
            @if ($kegiatan->peserta->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kehadiran
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Absen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
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
                                        @if ($peserta->no_hp)
                                            <p>{{ $peserta->no_hp }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($peserta->absensi)
                                            <span
                                                class="px-3 py-1 {{ $peserta->absensi->getStatusBadgeClass() }} text-sm rounded-full">
                                                {{ ucfirst(str_replace('_', ' ', $peserta->absensi->status_kehadiran)) }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                                Belum Absen
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if ($peserta->absensi)
                                            {{ $peserta->absensi->waktu_absen->format('d M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <button
                                            onclick="openAbsensiModal({{ $peserta->id }}, '{{ $peserta->nama_peserta }}', '{{ $peserta->absensi->status_kehadiran ?? '' }}')"
                                            class="px-3 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ $peserta->absensi ? 'Ubah' : 'Catat' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-users text-5xl mb-3 text-gray-300"></i>
                    <p class="text-lg">Belum ada peserta yang terdaftar</p>
                    <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                        class="text-green-700 hover:underline mt-2 inline-block">
                        Lihat detail kegiatan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Absensi -->
    <div id="absensiModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Catat Absensi</h3>
                <button onclick="closeAbsensiModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="absensiForm" action="{{ route('kegiatan.absensi.store', $kegiatan->id) }}" method="POST">
                @csrf
                <input type="hidden" name="peserta_id" id="peserta_id">

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Nama Peserta:</p>
                    <p class="font-semibold text-gray-800" id="modal_nama_peserta"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kehadiran <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_kehadiran" value="hadir" required
                                class="mr-3 h-4 w-4 text-green-600">
                            <div>
                                <p class="font-medium text-gray-800">Hadir</p>
                                <p class="text-sm text-gray-500">Peserta hadir tepat waktu</p>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_kehadiran" value="tidak_hadir"
                                class="mr-3 h-4 w-4 text-red-600">
                            <div>
                                <p class="font-medium text-gray-800">Tidak Hadir</p>
                                <p class="text-sm text-gray-500">Peserta tidak hadir</p>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_kehadiran" value="izin"
                                class="mr-3 h-4 w-4 text-yellow-600">
                            <div>
                                <p class="font-medium text-gray-800">Izin</p>
                                <p class="text-sm text-gray-500">Peserta izin tidak hadir</p>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_kehadiran" value="sakit"
                                class="mr-3 h-4 w-4 text-orange-600">
                            <div>
                                <p class="font-medium text-gray-800">Sakit</p>
                                <p class="text-sm text-gray-500">Peserta sakit</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" onclick="closeAbsensiModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAbsensiModal(pesertaId, namaPeserta, currentStatus) {
            document.getElementById('peserta_id').value = pesertaId;
            document.getElementById('modal_nama_peserta').textContent = namaPeserta;

            // Set current status if exists
            if (currentStatus) {
                const radio = document.querySelector(`input[name="status_kehadiran"][value="${currentStatus}"]`);
                if (radio) radio.checked = true;
            }

            document.getElementById('absensiModal').classList.remove('hidden');
        }

        function closeAbsensiModal() {
            document.getElementById('absensiModal').classList.add('hidden');
            document.getElementById('absensiForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('absensiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAbsensiModal();
            }
        });
    </script>
@endsection
