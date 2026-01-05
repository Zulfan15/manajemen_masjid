@extends('modules.zis.layout.template')
@section('title', 'Tambah Mustahiq')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah Mustahiq Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('zis.mustahiq.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Identitas Diri</h6>
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_lengkap" class="form-control" id="nama" placeholder="Nama" required>
                                <label for="nama">Nama Lengkap (Sesuai KTP)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="nik" class="form-control" id="nik" placeholder="NIK">
                                <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" name="no_hp" class="form-control" id="hp" placeholder="08..." required>
                                <label for="hp">Nomor HP / WhatsApp</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3 fw-bold small">Kategori & Alamat</h6>
                            <div class="form-floating mb-3">
                                <select name="kategori" class="form-select" id="kategori" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    <option value="Fakir">Fakir (Tidak punya harta/usaha)</option>
                                    <option value="Miskin">Miskin (Punya harta tapi tak cukup)</option>
                                    <option value="Amil">Amil (Panitia Zakat)</option>
                                    <option value="Muallaf">Muallaf (Baru masuk Islam)</option>
                                    <option value="Fisabilillah">Fisabilillah (Pejuang Dakwah)</option>
                                    <option value="Ibnu Sabil">Ibnu Sabil (Musafir kehabisan bekal)</option>
                                    <option value="Gharimin">Gharimin (Berhutang untuk kebaikan)</option>
                                    <option value="Riqab">Riqab (Hamba Sahaya)</option>
                                </select>
                                <label for="kategori">Kategori Mustahiq</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea name="alamat" class="form-control" placeholder="Alamat" id="alamat" style="height: 132px" required></textarea>
                                <label for="alamat">Alamat Lengkap Domisili</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('zis.mustahiq.index') }}" class="btn btn-light border px-4">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
