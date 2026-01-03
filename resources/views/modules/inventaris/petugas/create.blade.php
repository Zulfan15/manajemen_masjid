@extends('layouts.app')
@section('title', 'Tambah Petugas Baru')

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumbs --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <a class="text-emerald-800 text-base font-medium leading-normal"
               href="{{ route('inventaris.petugas.index') }}">
                Petugas
            </a>
            <span class="text-gray-400 text-base font-medium leading-normal">/</span>
            <span class="text-gray-800 text-base font-medium leading-normal">Tambah Petugas Baru</span>
        </div>

        {{-- Page Heading --}}
        <div class="flex flex-wrap justify-between gap-3 mb-8">
            <h1 class="text-gray-900 text-4xl font-black leading-tight tracking-tight">
                Tambah Petugas Baru
            </h1>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-xl p-8 border border-gray-200">
            {{-- UI-only: action sementara arahkan ke # --}}
            <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                @csrf

                {{-- Nama Petugas (col-span-2) --}}
                <div class="flex flex-col col-span-1 md:col-span-2">
                    <label class="text-gray-800 text-base font-medium leading-normal pb-2" for="nama-petugas">
                        Nama Petugas
                    </label>
                    <input
                        id="nama-petugas"
                        name="name"
                        type="text"
                        placeholder="Masukkan nama lengkap"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                               text-gray-800 focus:outline-0 focus:ring-2 focus:ring-emerald-800/30
                               border border-gray-300 bg-[#f6f8f7] h-12 placeholder:text-gray-400 px-4
                               text-base font-normal leading-normal"
                    />
                </div>

                {{-- Username --}}
                <div class="flex flex-col">
                    <label class="text-gray-800 text-base font-medium leading-normal pb-2" for="username">
                        Username
                    </label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Masukkan username"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                               text-gray-800 focus:outline-0 focus:ring-2 focus:ring-emerald-800/30
                               border border-gray-300 bg-[#f6f8f7] h-12 placeholder:text-gray-400 px-4
                               text-base font-normal leading-normal"
                    />
                </div>

                {{-- Password + icon (UI only) --}}
                <div class="flex flex-col">
                    <label class="text-gray-800 text-base font-medium leading-normal pb-2" for="password">
                        Password
                    </label>

                    <div class="relative flex w-full flex-1 items-stretch">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                                   text-gray-800 focus:outline-0 focus:ring-2 focus:ring-emerald-800/30
                                   border border-gray-300 bg-[#f6f8f7] h-12 placeholder:text-gray-400 px-4
                                   text-base font-normal leading-normal pr-12"
                        />

                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-emerald-800"
                            onclick="togglePassword()"
                            aria-label="Toggle password"
                        >
                            <i id="pwIcon" class="fa-regular fa-eye-slash text-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- Role --}}
                <div class="flex flex-col">
                    <label class="text-gray-800 text-base font-medium leading-normal pb-2" for="role">
                        Role
                    </label>
                    <select
                        id="role"
                        name="role"
                        class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                               text-gray-800 focus:outline-0 focus:ring-2 focus:ring-emerald-800/30
                               border border-gray-300 bg-[#f6f8f7] h-12 px-4 text-base font-normal leading-normal"
                    >
                        <option value="">Pilih Role</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="flex flex-col">
                    <label class="text-gray-800 text-base font-medium leading-normal pb-2" for="status">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                               text-gray-800 focus:outline-0 focus:ring-2 focus:ring-emerald-800/30
                               border border-gray-300 bg-[#f6f8f7] h-12 px-4 text-base font-normal leading-normal"
                    >
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-span-1 md:col-span-2 flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                    <a
                        href="{{ route('inventaris.petugas.index') }}"
                        class="rounded-lg h-12 px-6 inline-flex items-center justify-center
                               text-base font-bold bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors"
                    >
                        Batal
                    </a>

                    {{-- UI-only dulu: biar tidak bikin data, tetap tombol tampil --}}
                    <button
                        type="button"
                        class="rounded-lg h-12 px-6 text-base font-bold bg-emerald-800 text-white
                               hover:bg-emerald-800/90 transition-colors cursor-not-allowed opacity-90"
                        title="Simpan akan diaktifkan setelah tahap CRUD"
                    >
                        Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- script kecil untuk toggle password (UI only) --}}
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('pwIcon');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection
