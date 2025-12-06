@extends('layouts.app')
@section('title', 'Generate Sertifikat')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-certificate text-green-700 mr-2"></i>Generate Sertifikat
                </h1>
                <p class="text-gray-600 mt-2">Buat dan kelola sertifikat untuk peserta kegiatan</p>
            </div>

            @if (auth()->user()->isSuperAdmin())
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
                </div>
            @endif

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-4">
                    <button class="border-b-2 border-green-700 text-green-700 py-2 px-4 font-medium"
                        onclick="showTab('generate')">
                        <i class="fas fa-plus-circle mr-2"></i>Generate Baru
                    </button>
                    <button class="border-b-2 border-transparent text-gray-600 py-2 px-4 hover:text-gray-800"
                        onclick="showTab('history')">
                        <i class="fas fa-history mr-2"></i>Riwayat Sertifikat
                    </button>
                    <button class="border-b-2 border-transparent text-gray-600 py-2 px-4 hover:text-gray-800"
                        onclick="showTab('template')">
                        <i class="fas fa-file-image mr-2"></i>Template
                    </button>
                </nav>
            </div>

            <!-- Tab: Generate Baru -->
            <div id="tab-generate">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Generator -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg border border-green-200">
                            <h3 class="font-semibold text-gray-800 mb-2">
                                <i class="fas fa-info-circle text-green-700 mr-2"></i>Panduan Generate Sertifikat
                            </h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Pilih kegiatan yang akan diterbitkan sertifikat</li>
                                <li>• Pilih template sertifikat yang sesuai</li>
                                <li>• Masukkan data peserta atau upload dari file</li>
                                <li>• Generate dan download sertifikat</li>
                            </ul>
                        </div>

                        <form action="#" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select name="activity" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">-- Pilih Kegiatan --</option>
                                    <option value="1">Kajian Rutin Jumat - 1 Des 2025</option>
                                    <option value="2">Pelatihan Tahsin - 15 Nov 2025</option>
                                    <option value="3">Workshop Parenting - 10 Nov 2025</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Template Sertifikat <span class="text-red-500">*</span>
                                </label>
                                <select name="template" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">-- Pilih Template --</option>
                                    <option value="1">Template Kajian (Hijau)</option>
                                    <option value="2">Template Workshop (Biru)</option>
                                    <option value="3">Template Pelatihan (Coklat)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Input Peserta
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="input_method" value="manual" checked class="mr-2"
                                            onchange="toggleInputMethod()">
                                        <span class="text-gray-700">Manual</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="input_method" value="upload" class="mr-2"
                                            onchange="toggleInputMethod()">
                                        <span class="text-gray-700">Upload Excel</span>
                                    </label>
                                </div>
                            </div>

                            <div id="manual-input">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Daftar Nama Peserta <span class="text-red-500">*</span>
                                </label>
                                <textarea name="participants" rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Masukkan nama peserta (satu nama per baris)&#10;&#10;Contoh:&#10;Ahmad Fauzi&#10;Siti Nurhaliza&#10;Budi Santoso"></textarea>
                            </div>

                            <div id="upload-input" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload File Excel
                                </label>
                                <input type="file" name="excel_file" accept=".xlsx,.xls"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">
                                    <a href="#" class="text-green-700 hover:underline">Download template Excel</a>
                                </p>
                            </div>

                            @if (!auth()->user()->isSuperAdmin())
                                <div class="flex gap-4 pt-4">
                                    <button type="button"
                                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-eye mr-2"></i>Preview
                                    </button>
                                    <button type="submit"
                                        class="flex-1 px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                                        <i class="fas fa-download mr-2"></i>Generate & Download
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Preview -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Sertifikat</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50">
                            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Preview akan muncul di sini</p>
                            <p class="text-sm text-gray-400 mt-2">Pilih template dan isi data untuk melihat preview</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Riwayat (Hidden by default) -->
            <div id="tab-history" class="hidden">
                <div class="mb-4">
                    <input type="text" placeholder="Cari riwayat sertifikat..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                    Generate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">Kajian Rutin Jumat</td>
                                <td class="px-6 py-4 text-sm text-gray-600">5 Des 2025</td>
                                <td class="px-6 py-4 text-sm text-gray-600">120 sertifikat</td>
                                <td class="px-6 py-4">
                                    <button class="text-green-600 hover:text-green-800" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Template (Hidden by default) -->
            <div id="tab-template" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer">
                        <div
                            class="bg-gradient-to-br from-green-100 to-green-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-green-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Kajian</h4>
                        <p class="text-sm text-gray-600">Template dengan tema hijau untuk kegiatan kajian</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer">
                        <div
                            class="bg-gradient-to-br from-blue-100 to-blue-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-blue-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Workshop</h4>
                        <p class="text-sm text-gray-600">Template dengan tema biru untuk workshop</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition cursor-pointer">
                        <div
                            class="bg-gradient-to-br from-amber-100 to-amber-200 h-40 rounded mb-3 flex items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-amber-700"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Template Pelatihan</h4>
                        <p class="text-sm text-gray-600">Template dengan tema coklat untuk pelatihan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all tabs
            document.getElementById('tab-generate').classList.add('hidden');
            document.getElementById('tab-history').classList.add('hidden');
            document.getElementById('tab-template').classList.add('hidden');

            // Show selected tab
            document.getElementById('tab-' + tab).classList.remove('hidden');
        }

        function toggleInputMethod() {
            const manual = document.querySelector('input[name="input_method"][value="manual"]').checked;
            document.getElementById('manual-input').classList.toggle('hidden', !manual);
            document.getElementById('upload-input').classList.toggle('hidden', manual);
        }
    </script>
@endsection
