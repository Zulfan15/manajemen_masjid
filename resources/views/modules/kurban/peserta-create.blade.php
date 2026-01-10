@extends('layouts.app')

@section('title', 'Tambah Peserta Kurban')

@section('content')
<style>
    /* Hilangkan spinner bawaan */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>

<div class="container mx-auto max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-plus text-green-700 mr-2"></i>Tambah Peserta
        </h1>
        <p class="text-gray-600 mt-2">Kurban: <strong>{{ $kurban->nomor_kurban }}</strong> ({{ ucfirst($kurban->jenis_hewan) }})</p>
    </div>

    @php
        $maxBagian = $kurban->jenis_hewan === 'sapi' ? 7 : 1;
        $sisa = $maxBagian - $kurban->pesertaKurbans->sum('jumlah_bagian');
        $hargaPerBagian = $kurban->total_biaya / $maxBagian;
    @endphp

    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 text-sm">
        <p><i class="fas fa-info-circle mr-2"></i>Sisa kuota: <strong>{{ (int)$sisa }}</strong> bagian.</p>
        <p class="mt-1 ml-6">Harga per bagian: <strong>Rp {{ number_format($hargaPerBagian, 0, ',', '.') }}</strong></p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.peserta.store', $kurban) }}" class="space-y-6">
            @csrf
            <input type="hidden" name="user_id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Peserta *</label>
                    <input type="text" name="nama_peserta" value="{{ old('nama_peserta') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Identitas</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Peserta *</label>
                    <select name="tipe_peserta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500" required>
                        <option value="perorangan">Perorangan</option>
                        <option value="keluarga">Keluarga</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500">{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Jiwa *</label>
                    <input type="number" name="jumlah_jiwa" value="1" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bagian *</label>
                    
                    @if(in_array($kurban->jenis_hewan, ['kambing', 'domba']))
                        <input type="number" name="jumlah_bagian" id="input-bagian" value="1" readonly class="w-full px-4 py-2 border border-gray-300 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed text-center">
                    @else
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <button type="button" id="btn-minus" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 transition border-r border-gray-300">
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <input type="number" step="1" name="jumlah_bagian" id="input-bagian" 
                                   value="{{ old('jumlah_bagian', 1) }}" 
                                   min="1" max="{{ $sisa }}" 
                                   class="w-full py-2 text-center border-none focus:ring-0 appearance-none" required>
                                   
                            <button type="button" id="btn-plus" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 transition border-l border-gray-300">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-gray-500 mt-1 block text-center">Max input: <strong>{{ (int)$sisa }}</strong> bagian</small>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Tagihan (Rp)</label>
                    <input type="number" id="input-nominal" name="nominal_pembayaran" class="w-full px-4 py-2 border border-gray-300 bg-gray-100 font-bold text-gray-700 rounded-lg cursor-not-allowed" readonly>
                    <small id="hint-nominal" class="text-green-600 text-xs mt-1 block font-bold">Otomatis dihitung</small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pembayaran *</label>
                    <select name="status_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500" required>
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="cicilan">Cicilan</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
            </div>

            <div class="border-t pt-6 flex justify-end space-x-3">
                <a href="{{ route('kurban.show', $kurban) }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</a>
                <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBagian = document.getElementById('input-bagian');
        const inputNominal = document.getElementById('input-nominal');
        const hintNominal = document.getElementById('hint-nominal');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        
        // Data PHP
        const totalBiaya = {{ $kurban->total_biaya }};
        const maxBagian = {{ $maxBagian }}; 
        const sisaKuota = {{ $sisa }}; 
        const hargaPerSatuBagian = totalBiaya / maxBagian;

        function updateFormLogic() {
            let bagian = parseInt(inputBagian.value) || 0;

            // Guard Clause
            if (bagian > sisaKuota) {
                alert('Maksimal hanya ' + sisaKuota + ' bagian!');
                bagian = sisaKuota;
                inputBagian.value = sisaKuota;
            }
            if (bagian < 1) {
                bagian = 1;
                inputBagian.value = 1;
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
            
            hintNominal.innerText = "Tagihan: " + rupiahFormat;
            inputNominal.value = hargaPasti;
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
            updateFormLogic(); // Init
        }
    });
</script>
@endsection