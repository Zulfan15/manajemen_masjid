<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $data = Notification::latest()->get();
        return view('modules.informasi.notification.index', compact('data'));
    }

    public function send()
    {
        // Terserah kamu mau pakai email/wa gateway apa

        return back()->with('success', 'Notifikasi berhasil diproses');
    }
}
