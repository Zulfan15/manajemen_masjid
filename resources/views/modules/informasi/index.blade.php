@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-bullhorn text-green-600 mr-2"></i>
        Manajemen Informasi Masjid
    </h1>

    <!-- Tabs -->
    <div class="flex space-x-3 mb-6">
        <a href="?tab=pengumuman"
           class="px-4 py-2 rounded-lg transition {{ $tab=='pengumuman' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300' }}">
            Pengumuman
        </a>

        <a href="?tab=berita"
           class="px-4 py-2 rounded-lg transition {{ $tab=='berita' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300' }}">
            Berita
        </a>

        <a href="?tab=artikel"
           class="px-4 py-2 rounded-lg transition {{ $tab=='artikel' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300' }}">
            Artikel
        </a>
    </div>

    <!-- Dropdown Tambah -->
    @if(auth()->user()->hasPermission('informasi.create'))
    <div class="mb-4 relative inline-block">
        <button onclick="toggleMenu()"
            class="bg-green-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-green-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Informasi
            <i class="fas fa-chevron-down ml-2 text-sm"></i>
        </button>

        <div id="menuTambah"
             class="hidden absolute mt-2 w-48 bg-white shadow-lg rounded-lg border z-10">

            <a href="{{ route('informasi.pengumuman.create') }}"
               class="block px-4 py-2 hover:bg-gray-100">
                📢 Tambah Pengumuman
            </a>

            <a href="{{ route('informasi.berita.create') }}"
               class="block px-4 py-2 hover:bg-gray-100">
                📰 Tambah Berita
            </a>

            <a href="{{ route('informasi.artikel.create') }}"
               class="block px-4 py-2 hover:bg-gray-100">
                📝 Tambah Artikel
            </a>
        </div>
    </div>
    @endif

    <!-- TABLE CONTENT -->
    @if($tab == 'pengumuman')
        @include('modules.informasi.partials.table_pengumuman')
    @elseif($tab == 'berita')
        @include('modules.informasi.partials.table_berita')
    @else
        @include('modules.informasi.partials.table_artikel')
    @endif

</div>

<script>
function toggleMenu() {
    document.getElementById('menuTambah').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    let menu = document.getElementById('menuTambah');
    if (!menu.contains(e.target) && !e.target.closest('button')) {
        menu.classList.add('hidden');
    }
});
</script>
@endsection
