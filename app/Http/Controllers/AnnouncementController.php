<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\InformasiNotificationMail;
use Illuminate\Support\Facades\Mail;

class AnnouncementController extends Controller
{
    /** Tampilkan form tambah */
    public function create()
    {
        return view('modules.informasi.form', [
            'type' => 'pengumuman',
            'data' => null,
            'route_store' => route('informasi.pengumuman.store'),
        ]);
    }

    /** Simpan data baru */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $save = Announcement::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'type'        => 'penting', // bisa diganti dropdown kalau mau
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'created_by'  => Auth::id(),
        ]);

        // Jika user memilih "kirim notifikasi"
        if ($request->send_notification == 'yes') {

            // URL menuju halaman detail
            $url = route('public.info.show', $save->slug);

            // Ambil email user (jamaah)
            $emails = User::pluck('email')->toArray();

            // Kirim email (bcc agar privasi aman)
            Mail::to('ajirahman215@gmail.com')
                ->bcc($emails)
                ->queue(new InformasiNotificationMail(
                    $save->title,
                    $save->content,
                    $url
                ));
        }

        return redirect()->route('informasi.index')
                         ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /** Tampilkan form edit */
    public function edit($id)
    {
        $data = Announcement::findOrFail($id);

        return view('modules.informasi.form', [
            'type' => 'pengumuman',
            'data' => $data,
            'route_update' => route('informasi.pengumuman.update', $id),
        ]);
    }

    /** Update data */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $item = Announcement::findOrFail($id);

        $item->update([
            'title'      => $request->title,
            'content'    => $request->content,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        return redirect()->route('informasi.index')
                         ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /** Hapus data */
    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
