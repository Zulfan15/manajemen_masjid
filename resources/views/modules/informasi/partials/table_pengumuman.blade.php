<div class="bg-white shadow rounded-lg">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Jenis</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pengumuman as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $item->title }}</td>
                    <td class="px-4 py-3 capitalize">{{ $item->type }}</td>
                    <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>

                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('informasi.pengumuman.edit', $item->id) }}"
                           class="text-blue-600 mx-1"><i class="fas fa-edit"></i></a>

                        <form action="{{ route('informasi.pengumuman.destroy',$item->id) }}"
                              method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus?')" 
                                    class="text-red-600 mx-1">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr><td colspan="4" class="py-4 text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
