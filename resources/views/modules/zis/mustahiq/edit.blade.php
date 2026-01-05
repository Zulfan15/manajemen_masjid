@extends('modules.zis.layout.template')
@section('title', 'Edit Mustahiq')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Data Mustahiq</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('zis.mustahiq.update', $mustahiq->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Identitas Diri</h6>
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_lengkap" class="form-control" id="nama" value="{{ $mustahiq->nama_lengkap }}" required>
                                <label for="nama">Nama Lengkap</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="nik" class="form-control" id="nik" value="{{ $mustahiq->nik }}">
                                <label for="nik">NIK (16 Digit)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="no_hp" class="form-control" id="hp" value="{{ $mustahiq->no_hp }}" required>
                                <label for="hp">Nomor HP</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Detail Status</h6>
                            <div class="form-floating mb-3">
                                <select name="kategori" class="form-select" id="kategori" required>
                                    <option value="Fakir" {{ $mustahiq->kategori == 'Fakir' ? 'selected' : '' }}>Fakir</option>
                                    <option value="Miskin" {{ $mustahiq->kategori == 'Miskin' ? 'selected' : '' }}>Miskin</option>
                                    <option value="Amil" {{ $mustahiq->kategori == 'Amil' ? 'selected' : '' }}>Amil</option>
                                    <option value="Muallaf" {{ $mustahiq->kategori == 'Muallaf' ? 'selected' : '' }}>Muallaf</option>
                                    <option value="Fisabilillah" {{ $mustahiq->kategori == 'Fisabilillah' ? 'selected' : '' }}>Fisabilillah</option>
                                    <option value="Ibnu Sabil" {{ $mustahiq->kategori == 'Ibnu Sabil' ? 'selected' : '' }}>Ibnu Sabil</option>
                                    <option value="Gharimin" {{ $mustahiq->kategori == 'Gharimin' ? 'selected' : '' }}>Gharimin</option>
                                    <option value="Riqab" {{ $mustahiq->kategori == 'Riqab' ? 'selected' : '' }}>Riqab</option>
                                </select>
                                <label for="kategori">Kategori Asnaf</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="status_aktif" class="form-select" id="status">
                                    <option value="1" {{ $mustahiq->status_aktif == 1 ? 'selected' : '' }}>Aktif (Layak Terima)</option>
                                    <option value="0" {{ $mustahiq->status_aktif == 0 ? 'selected' : '' }}>Non-Aktif (Stop Penyaluran)</option>
                                </select>
                                <label for="status">Status Keaktifan</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea name="alamat" class="form-control" id="alamat" style="height: 100px" required>{{ $mustahiq->alamat }}</textarea>
                                <label for="alamat">Alamat Domisili</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('zis.mustahiq.index') }}" class="btn btn-light border px-4">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Update Data
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
