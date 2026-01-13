@extends('layouts.app')

@section('title', 'Tambah Peserta Kurban')

@section('content')
<style>
    /* CSS dari kode lama untuk hilangkan spinner angka */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>

<div class="container mx-auto max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-plus text-green-700 mr-2"></i>Tambah Peserta Kurban
                </h1>
                <p class="text-gray-600 mt-2">Kurban: <strong>{{ $kurban->nomor_kurban }}</strong> ({{ ucfirst($kurban->jenis_hewan) }})</p>
            </div>
        </div>
    </div>

    @php
        $maxBagian = $kurban->jenis_hewan === 'sapi' ? 7 : 1;
        $terpakai = $kurban->pesertaKurbans->sum('jumlah_bagian');
        $sisa = $maxBagian - $terpakai;
        // Mencegah pembagian nol jika data error
        $hargaPerBagian = $maxBagian > 0 ? ($kurban->total_biaya / $maxBagian) : 0;
    @endphp

    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 text-sm flex justify-between items-center">
        <div>
            <i class="fas fa-info-circle mr-2"></i>Sisa kuota saat ini: <strong>{{ (float)$sisa }}</strong> bagian.
        </div>
        <div class="font-semibold">
            Harga per bagian: Rp {{ number_format($hargaPerBagian, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.peserta.store', $kurban) }}" class="space-y-6">
            @csrf
            {{-- Input hidden user_id jika diperlukan --}}
            <input type="hidden" name="user_id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Peserta <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_peserta" value="{{ old('nama_peserta') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nama lengkap peserta" required>
                    @error('nama_peserta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Identitas (KTP)</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nomor KTP/Identitas">
                    @error('nomor_identitas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nomor telepon/HP">
                    @error('nomor_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Peserta <span class="text-red-500">*</span></label>
                    <select name="tipe_peserta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="perorangan" {{ old('tipe_peserta') === 'perorangan' ? 'selected' : '' }}>Perorangan</option>
                        <option value="keluarga" {{ old('tipe_peserta') === 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                    </select>
                    @error('tipe_peserta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Alamat lengkap peserta">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Jiwa <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_jiwa" value="{{ old('jumlah_jiwa', 1) }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <small class="text-gray-500 mt-1 block">Jumlah jiwa jika tipe keluarga</small>
                    @error('jumlah_jiwa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bagian <span class="text-red-500">*</span></label>
                    
                    @if(in_array($kurban->jenis_hewan, ['kambing', 'domba']))
                        {{-- Logic Kambing: Fixed 1 --}}
                        <input type="number" name="jumlah_bagian" id="input-bagian" value="1" readonly class="w-full px-4 py-2 border border-gray-300 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed text-center">
                    @else
                        {{-- Logic Sapi: Plus Minus Button --}}
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-green-500 focus-within:border-transparent">
                            <button type="button" id="btn-minus" class="px-4 py-2 bg-gray-50 hover:bg-gray-200 text-gray-600 transition border-r border-gray-300 h-full">
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <input type="number" step="1" name="jumlah_bagian" id="input-bagian" 
                                   value="{{ old('jumlah_bagian', 1) }}" 
                                   min="1" max="{{ $sisa }}" 
                                   class="w-full py-2 text-center border-none focus:ring-0 appearance-none bg-white" required>
                                   
                            <button type="button" id="btn-plus" class="px-4 py-2 bg-gray-50 hover:bg-gray-200 text-gray-600 transition border-l border-gray-300 h-full">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-gray-500 mt-1 block text-center">Max input: <strong>{{ (float)$sisa }}</strong> bagian</small>
                    @endif
                    
                    @error('jumlah_bagian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Tagihan (Rp) <span class="text-red-500">*</span></label>
                    {{-- Input dibuat Readonly agar user tidak salah isi, dihitung via JS --}}
                    <input type="number" id="input-nominal" name="nominal_pembayaran" value="{{ old('nominal_pembayaran') }}" class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg font-bold text-gray-800 cursor-not-allowed" readonly required>
                    <small id="hint-nominal" class="text-green-600 text-xs mt-1 block font-bold">Otomatis dihitung berdasarkan jumlah bagian</small>
                    @error('nominal_pembayaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select name="status_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="belum_lunas" {{ old('status_pembayaran') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="cicilan" {{ old('status_pembayaran') === 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                        <option value="lunas" {{ old('status_pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status_pembayaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('kurban.show', $kurban) }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Peserta
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT DARI KODE PERTAMA UNTUK INTERAKSI --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBagian = document.getElementById('input-bagian');
        const inputNominal = document.getElementById('input-nominal');
        const hintNominal = document.getElementById('hint-nominal');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        
        // Data PHP ke JS
        const totalBiaya = {{ $kurban->total_biaya }};
        const maxBagian = {{ $maxBagian }}; 
        const sisaKuota = {{ $sisa }}; 
        // Cegah pembagian nol
        const hargaPerSatuBagian = maxBagian > 0 ? (totalBiaya / maxBagian) : 0;

        function updateFormLogic() {
            let bagian = parseFloat(inputBagian.value) || 0;

            // Guard Clause Kuota (Hanya jika Sapi / input enabled)
            if (!inputBagian.readOnly) {
                if (bagian > sisaKuota) {
                    alert('Maksimal hanya ' + sisaKuota + ' bagian!');
                    bagian = sisaKuota;
                    inputBagian.value = sisaKuota;
                }
                if (bagian < 1) {
                    bagian = 1;
                    inputBagian.value = 1;
                }
            }

            // Update UI Button State
            if(btnMinus) btnMinus.disabled = (bagian <= 1);
            if(btnPlus) btnPlus.disabled = (bagian >= sisaKuota);
            
            // Visual feedback button disabled
            if(btnMinus) btnMinus.style.opacity = (bagian <= 1) ? "0.5" : "1";
            if(btnPlus) btnPlus.style.opacity = (bagian >= sisaKuota) ? "0.5" : "1";

            // Hitung Harga
            let hargaPasti = Math.round(bagian * hargaPerSatuBagian);
            let rupiahFormat = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(hargaPasti);
            
            if(hintNominal) hintNominal.innerText = "Tagihan: " + rupiahFormat;
            if(inputNominal) inputNominal.value = hargaPasti;
        }

        // Event Listeners untuk Tombol
        if(btnMinus) {
            btnMinus.addEventListener('click', function() {
                let current = parseInt(inputBagian.value) || 0;
                if(current > 1) {
                    inputBagian.value = current - 1;
                    updateFormLogic();
                }
            });
        }

        if(btnPlus) {
            btnPlus.addEventListener('click', function() {
                let current = parseInt(inputBagian.value) || 0;
                if(current < sisaKuota) {
                    inputBagian.value = current + 1;
                    updateFormLogic();
                }
            });
        }

        if(inputBagian) {
            inputBagian.addEventListener('input', updateFormLogic);
            updateFormLogic(); // Init saat load
        }
    });
</script>
@endsection