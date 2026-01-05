@extends('modules.zis.layout.template')
@section('title', 'Catat Penyaluran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-paper-plane me-2"></i>Catat Penyaluran Dana</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('zis.penyaluran.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor Penyaluran</label>
                            <input type="text" name="kode_penyaluran" class="form-control bg-light" value="{{ $nomorOtomatis }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Penyaluran</label>
                            <input type="date" name="tanggal_penyaluran" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Mustahiq (Penerima)</label>
                        <select name="mustahiq_id" class="form-select" required>
                            <option value="">-- Cari Nama Mustahiq --</option>
                            @foreach ($mustahiq as $orang)
                                <option value="{{ $orang->id }}">
                                    {{ $orang->nama_lengkap }} ({{ $orang->kategori }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hanya menampilkan Mustahiq dengan status AKTIF.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Bantuan</label>
                            <select name="jenis_bantuan" class="form-select" required>
                                <option value="Uang Tunai">Uang Tunai</option>
                                <option value="Sembako (Beras/Minyak)">Sembako (Beras/Minyak)</option>
                                <option value="Beasiswa Pendidikan">Beasiswa Pendidikan</option>
                                <option value="Bantuan Kesehatan">Bantuan Kesehatan</option>
                                <option value="Modal Usaha">Modal Usaha</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nominal / Nilai (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control" placeholder="0" min="1000" required>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.8em">*Jika barang, perkirakan nilainya dalam Rupiah.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Penyerahan bantuan tahap 1..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('zis.penyaluran.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-paper-plane me-1"></i> Salurkan Sekarang
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
