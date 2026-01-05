@extends('modules.zis.layout.template')
@section('title', 'Catat Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-hand-holding-usd me-2"></i>Catat Pemasukan ZIS</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('zis.transaksi.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor Transaksi</label>
                            <input type="text" name="kode_transaksi" class="form-control bg-light" value="{{ $nomorOtomatis }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Muzakki (Penyetor)</label>
                        <select name="muzakki_id" class="form-select" required>
                            <option value="">-- Cari Nama Muzakki --</option>
                            @foreach ($muzakki as $orang)
                                <option value="{{ $orang->id }}">
                                    {{ $orang->nama_lengkap }} - {{ $orang->nik ?? 'No NIK' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Nama tidak ada? <a href="{{ route('zis.muzakki.create') }}">Tambah Muzakki Baru</a> dulu.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Dana</label>
                            <select name="jenis_transaksi" class="form-select" required>
                                <option value="zakat fitrah">Zakat Fitrah</option>
                                <option value="zakat mal">Zakat Mal (Harta)</option>
                                <option value="infaq">Infaq</option>
                                <option value="sedekah">Sedekah</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control" placeholder="0" min="1000" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Doa (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Hamba Allah, tunai..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('zis.transaksi.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-check me-1"></i> Simpan Transaksi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

