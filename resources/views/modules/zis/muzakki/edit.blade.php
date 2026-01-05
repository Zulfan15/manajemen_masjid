@extends('modules.zis.layout.template')
@section('title', 'Edit Muzakki')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Data Muzakki</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('zis.muzakki.update', $muzakki->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Identitas Diri</h6>
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_lengkap" class="form-control" id="nama" value="{{ $muzakki->nama_lengkap }}" required>
                                <label for="nama">Nama Lengkap</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="nik" class="form-control" id="nik" value="{{ $muzakki->nik }}">
                                <label for="nik">NIK (16 Digit)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="no_hp" class="form-control" id="hp" value="{{ $muzakki->no_hp }}" required>
                                <label for="hp">Nomor HP</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Jenis Kelamin & Alamat</h6>
                            <div class="form-floating mb-3">
                                <select name="jenis_kelamin" class="form-select" id="jenis_kelamin" required>
                                    <option value="L" {{ $muzakki->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $muzakki->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea name="alamat" class="form-control" id="alamat" style="height: 132px" required>{{ $muzakki->alamat }}</textarea>
                                <label for="alamat">Alamat Domisili</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('zis.muzakki.index') }}" class="btn btn-light border px-4">
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
