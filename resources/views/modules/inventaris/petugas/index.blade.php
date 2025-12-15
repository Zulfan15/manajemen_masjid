@extends('layouts.app')
@section('title', 'Daftar Petugas')

@section('content')
@php
  // warna primary mockup
  $primary = 'emerald-800';
@endphp

<div class="p-6">
    {{-- Heading + action --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex min-w-72 flex-col gap-1">
            <p class="text-2xl font-bold leading-tight tracking-tight text-gray-900">Daftar Petugas</p>
            <p class="text-sm font-normal text-gray-500">Manage and view all staff accounts in the system.</p>
        </div>

        <a href="{{ route('inventaris.petugas.create') }}"
           class="flex h-10 min-w-[84px] items-center justify-center gap-2 overflow-hidden rounded-lg bg-emerald-800 px-4 text-sm font-bold text-white shadow-sm hover:bg-emerald-800/90">
            <i class="fa-solid fa-plus text-sm"></i>
            <span class="truncate">Tambah Petugas Baru</span>
        </a>
    </div>

    {{-- SearchBar (tanpa dropdown status) --}}
    <div class="mt-6">
        <form method="GET" action="{{ route('inventaris.petugas.index') }}">
            <label class="flex h-12 w-full flex-col min-w-40">
                <div class="flex h-full w-full items-stretch rounded-lg border border-gray-200/80 bg-white">
                    <div class="flex items-center justify-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input name="search" value="{{ request('search') }}"
                           class="h-full min-w-0 flex-1 rounded-r-lg border-none bg-transparent px-3 text-sm font-normal text-gray-900 placeholder:text-gray-400 focus:outline-0 focus:ring-0"
                           placeholder="Search by name, username, or role..." />
                </div>
            </label>
        </form>
    </div>

    {{-- Table --}}
    <div class="mt-4">
        <div class="overflow-hidden rounded-lg border border-gray-200/50 bg-white">
            <table class="min-w-full divide-y divide-gray-200/50">
                <thead class="bg-[#f6f8f7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Foto/Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Petugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status Akun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200/50">
                    @forelse($petugas as $p)
                        @php
                            // ROLE (Spatie): ambil role pertama kalau ada
                            $roleName = '-';
                            if (isset($p->roles) && $p->roles->count() > 0) {
                                $roleName = $p->roles->first()->name;
                            }

                            $roleLower = strtolower($roleName);
                            $roleBadgeClass = str_contains($roleLower, 'admin')
                                ? 'bg-emerald-800/10 text-emerald-800'
                                : 'bg-gray-100 text-gray-800';

                            // STATUS (users.status enum aktif/nonaktif)
                            $status = strtolower($p->status ?? '-');
                            $statusBadgeClass = $status === 'aktif'
                                ? 'bg-green-100 text-green-800'
                                : ($status === 'nonaktif'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-gray-100 text-gray-700');

                            // Avatar placeholder: inisial
                            $initials = collect(explode(' ', trim($p->name ?? 'U')))
                                ->filter()
                                ->take(2)
                                ->map(fn($w) => strtoupper(mb_substr($w,0,1)))
                                ->implode('');
                        @endphp

                        <tr class="hover:bg-emerald-800/5">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-xs font-bold">
                                    {{ $initials ?: 'U' }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $p->name }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $p->username ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold leading-5 {{ $roleBadgeClass }}">
                                    {{ $roleName }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold leading-5 {{ $statusBadgeClass }}">
                                    {{ $status !== '-' ? ucfirst($status) : '-' }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    {{-- view-only dulu: tombol masih non-aktif --}}
                                    <button type="button" class="text-gray-400 hover:text-emerald-800" title="Edit (coming soon)">
                                        <i class="fa-regular fa-pen-to-square text-lg"></i>
                                    </button>
                                    <button type="button" class="text-gray-400 hover:text-emerald-800" title="Reset Password (coming soon)">
                                        <i class="fa-solid fa-key text-lg"></i>
                                    </button>
                                    <button type="button" class="text-gray-400 hover:text-red-500" title="Hapus (coming soon)">
                                        <i class="fa-regular fa-trash-can text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada data petugas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        <div class="mt-3 flex justify-end">
            {{ $petugas->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
