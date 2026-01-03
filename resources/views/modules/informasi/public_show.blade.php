<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->title }} - Masjid Al-Ikhlas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow fixed w-full z-50 top-0">
        <div class="container mx-auto px-4 h-16 flex items-center">
            <a href="{{ route('public.home') }}" class="text-gray-500 hover:text-green-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>


    <div class="container mx-auto px-4 py-24 max-w-4xl">

        <article class="bg-white rounded-lg shadow-lg overflow-hidden">

            {{-- THUMBNAIL --}}
            @if(!empty($item->thumbnail))
                <img src="{{ asset('storage/'.$item->thumbnail) }}" 
                     class="w-full h-64 object-cover">
            @else
                <div class="h-64 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
            @endif


            <div class="p-8">

                {{-- META --}}
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">

                    {{-- KATEGORI (hanya untuk Article) --}}
                    @if($item instanceof \App\Models\Article)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-bold uppercase text-xs">
                            {{ $item->category->name ?? 'Tanpa Kategori' }}
                        </span>
                    @endif

                    <span>
                        <i class="far fa-calendar mr-1"></i>
                        {{ $item->created_at->format('d F Y') }}
                    </span>

                    {{-- AUTHOR --}}
                    @if($item instanceof \App\Models\Article)
                        <span><i class="far fa-user mr-1"></i> {{ $item->author_name }}</span>
                    @else
                        <span><i class="far fa-user mr-1"></i> Admin</span>
                    @endif
                </div>


                {{-- JUDUL --}}
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    {{ $item->title }}
                </h1>

                {{-- KONTEN --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($item->content)) !!}
                </div>

            </div>
        </article>
    </div>

</body>
</html>
