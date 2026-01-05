@extends('modules.zis.layout.template')
@section('title', 'Tambah User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-lock me-2"></i>Buat Akun Baru</h5>
            </div>
            <div class="card-body p-4">
                
                {{-- Menampilkan Error Validasi (Jika ada) --}}
                {{-- ERROR BIASANYA DISINI: @if harus ditutup @endif --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('zis.user.store') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="name" required placeholder="Nama" value="{{ old('name') }}">
                        <label for="name">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" required placeholder="Email" value="{{ old('email') }}">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="pass" required placeholder="Pass">
                        <label for="pass">Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select name="role" class="form-select" id="role" required>
                            <option value="amil">Amil (Staff)</option>
                            <option value="admin">Admin (Kepala)</option>
                        </select>
                        <label for="role">Level Akses (Role)</label>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('zis.user.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

