@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Manajemen Kategori Pengeluaran
    </h2>

    <div class="flex border-b border-gray-200 mb-6">
        <button class="py-2 px-4 text-gray-500 hover:text-green-600 focus:outline-none">
            Pemasukan (Coming Soon)
        </button>
        <a href="{{ route('keuangan.pengeluaran.index') }}" class="py-2 px-4 text-gray-500 hover:text-green-600 focus:outline-none">
            Pengeluaran
        </a>
        <button class="py-2 px-4 border-b-2 border-green-600 text-green-600 font-semibold focus:outline-none">
            Kategori
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
        <span class="font-medium">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div x-data="{ openModal: false, isEdit: false, formData: { id: '', nama_kategori: '', deskripsi: '' } }">
        
        <div class="flex justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-600">Daftar Kategori</h4>
            @if(auth()->user()->hasPermission('keuangan.create'))
            <button 
                @click="openModal = true; isEdit = false; formData = { id: '', nama_kategori: '', deskripsi: '' }" 
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-purple">
                + Tambah Kategori
            </button>
            @endif
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Kategori</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($kategori as $index => $item)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $item->nama_kategori }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2 text-sm">
                                    @if(auth()->user()->hasPermission('keuangan.update'))
                                    <button 
                                        @click="openModal = true; isEdit = true; formData = { id: '{{ $item->id }}', nama_kategori: '{{ $item->nama_kategori }}', deskripsi: '{{ $item->deskripsi }}' }"
                                        class="px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" 
                                        aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </button>
                                    @endif

                                    @if(auth()->user()->hasPermission('keuangan.delete'))
                                    <form action="{{ route('keuangan.kategori-pengeluaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Data pengeluaran terkait juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if(!auth()->user()->hasPermission('keuangan.update') && !auth()->user()->hasPermission('keuangan.delete'))
                                    <span class="text-gray-400 text-sm italic">Read Only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            x-show="openModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
            style="display: none;"
        >
            <div 
                x-show="openModal"
                class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
                @click.away="openModal = false"
            >
                <header class="flex justify-between mb-4">
                    <p class="text-lg font-semibold text-gray-700" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></p>
                    <button class="text-gray-400 hover:text-gray-700" @click="openModal = false">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L10 8.586 5.707 4.293a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                    </button>
                </header>

                <form :action="isEdit ? '{{ url('keuangan/kategori-pengeluaran') }}/' + formData.id : '{{ route('keuangan.kategori-pengeluaran.store') }}'" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="mb-4">
                        <label class="block text-sm">
                            <span class="text-gray-700">Nama Kategori</span>
                            <input x-model="formData.nama_kategori" name="nama_kategori" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 focus:outline-none focus:shadow-outline-green form-input" required />
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm">
                            <span class="text-gray-700">Deskripsi</span>
                            <textarea x-model="formData.deskripsi" name="deskripsi" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 focus:outline-none focus:shadow-outline-green form-textarea" rows="3"></textarea>
                        </label>
                    </div>

                    <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50">
                        <button @click="openModal = false" type="button" class="w-full px-5 py-3 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
                            Batal
                        </button>
                        <button type="submit" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                            Simpan
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection