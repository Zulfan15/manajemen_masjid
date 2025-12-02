<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InformasiController extends Controller
{
    // === HALAMAN PUBLIK (Bisa diakses siapa saja) ===

    public function publicIndex()
    {
        // Ambil 6 berita terbaru yang statusnya published
        $posts = Post::published()->latest()->take(6)->get();
        
        // Data Dummy Jadwal Sholat (Nanti bisa diganti API sungguhan)
        $jadwalSholat = [
            'Subuh' => '04:15', 'Dzuhur' => '11:45', 'Ashar' => '15:05', 
            'Maghrib' => '17:55', 'Isya' => '19:05'
        ];

        return view('modules.informasi.public_landing', compact('posts', 'jadwalSholat'));
    }

    public function publicShow($slug)
    {
        // Tampilkan detail berita berdasarkan slug
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        return view('modules.informasi.public_show', compact('post'));
    }

    // === HALAMAN ADMIN (Harus Login) ===

    public function index()
    {
        // Tampilkan list berita di dashboard admin
        $posts = Post::latest()->paginate(10);
        return view('modules.informasi.index', compact('posts'));
    }

    public function create()
    {
        return view('modules.informasi.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => 'required',
        ]);

        // Simpan ke database
        Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(), // Bikin link otomatis
            'content' => $request->content,
            'category' => $request->category,
            'status' => 'published',
            'created_by' => Auth::id() // Ambil ID user yang sedang login
        ]);

        return redirect()->route('informasi.index')->with('success', 'Informasi berhasil dipublikasikan!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('modules.informasi.create', compact('post')); // Pakai ulang tampilan create untuk edit
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'category' => $request->category,
        ]);

        return redirect()->route('informasi.index')->with('success', 'Informasi diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('informasi.index')->with('success', 'Informasi dihapus.');
    }

    // Fitur Broadcast (Akan dikerjakan nanti, ini kerangka saja)
    public function broadcast($id)
    {
        return back()->with('success', 'Fitur Broadcast Email sedang dalam pengembangan (Integrasi Modul 1).');
    }
}