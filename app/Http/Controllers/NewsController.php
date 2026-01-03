<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\InformasiNotificationMail;
use Illuminate\Support\Facades\Mail;


class NewsController extends Controller
{
    /** FORM TAMBAH */
    public function create()
    {
        return view('modules.informasi.form', [
            'type' => 'berita',
            'data' => null,
            'route_store' => route('informasi.berita.store'),
        ]);
    }

    /** SIMPAN DATA BARU */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'content'   => 'required',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('news', 'public');
        }

        $news = News::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'content'     => $request->content,
            'thumbnail'   => $thumbnailPath,
            'created_by'  => Auth::id(),
            'published_at'=> now(),
        ]);

        // Jika user memilih kirim notifikasi
        if ($request->send_notification == 'yes') {
                    // // // URL menuju halaman detail
                    $url = route('public.info.show', $news->slug);

                    // Ambil email user (jamaah)
                    $emails = User::pluck('email')->toArray();

                    // Kirim email (bcc agar privasi aman)
                    Mail::to('ajirahman215@gmail.com')
                        ->bcc($emails)
                        ->send(new InformasiNotificationMail(
                            $news->title,
                            $news->content,
                            $url
                        ));
        }

        return redirect()->route('informasi.index')
                         ->with('success', 'Berita berhasil ditambahkan.');
    }

    /** FORM EDIT */
    public function edit($id)
    {
        $data = News::findOrFail($id);

        return view('modules.informasi.form', [
            'type' => 'berita',
            'data' => $data,
            'route_update' => route('informasi.berita.update', $id),
        ]);
    }

    /** UPDATE */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'content'   => 'required',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $news = News::findOrFail($id);

        // Upload jika ada file baru
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail')->store('news', 'public');
            $news->thumbnail = $thumb;
        }

        $news->update([
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('informasi.index')
                         ->with('success', 'Berita berhasil diperbarui.');
    }

    /** DELETE */
    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
