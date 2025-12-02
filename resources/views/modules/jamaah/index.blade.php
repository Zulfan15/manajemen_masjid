@extends('layouts.app')

@section('title', 'Manajemen Jamaah')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-users text-green-700 mr-2"></i>Manajemen Jamaah
                </h1>
                <p class="text-gray-600 mt-2">Kelola data jamaah masjid</p>
            </div>
            @if(!auth()->user()->isSuperAdmin())
                <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Jamaah
                </button>
            @endif
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Mode View Only:</strong> Anda hanya dapat melihat data, tidak dapat menambah, mengubah, atau menghapus data.
                </p>
            </div>
        @endif

        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-users text-6xl mb-4 text-gray-300"></i>
            <h3 class="text-xl font-semibold mb-2">Halaman Navigasi Modul</h3>
            <p>Konten detail modul ini akan dikembangkan oleh tim lain.</p>
            <p class="text-sm mt-2">Navigasi dan autentikasi sudah berfungsi dengan baik.</p>
        </div>
    </div>
</div>
@endsection
