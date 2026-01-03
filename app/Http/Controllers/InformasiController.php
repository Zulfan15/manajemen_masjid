<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Article;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



class InformasiController extends Controller
{
    public function publicIndex()
{
    $pengumuman = Announcement::orderBy('start_date', 'DESC')->get();
    $berita     = News::latest()->get();
    $artikel    = Article::latest()->get();

    // panggil API jadwal sholat
    $jadwalSholat = $this->getJadwalSholat();

    return view('modules.informasi.public_landing', compact(
        'pengumuman',
        'berita',
        'artikel',
        'jadwalSholat'
    ));
}
    private function getJadwalSholat()
{
    // Ambil lokasi (contoh: Surabaya)
    $kota = "Bandung";

    // Format tanggal
    $today = now()->format('d-m-Y');

    try {
        $response = Http::get("https://api.aladhan.com/v1/timingsByCity", [
            'city'      => $kota,
            'country'   => 'Indonesia',
            'method'    => 2
        ]);

        if ($response->successful()) {

            $data = $response->json()['data']['timings'];

            return [
                'Subuh'   => $data['Fajr'],
                'Dzuhur'  => $data['Dhuhr'],
                'Ashar'   => $data['Asr'],
                'Maghrib' => $data['Maghrib'],
                'Isya'    => $data['Isha'],
            ];
        }

    } catch (\Exception $e) {
        // Jika API error -> fallback default
        return [
            'Subuh'   => '--:--',
            'Dzuhur'  => '--:--',
            'Ashar'   => '--:--',
            'Maghrib' => '--:--',
            'Isya'    => '--:--',
        ];
    }
}

    public function publicShow($slug)
    {
        $item = Announcement::where('slug', $slug)->first()
            ?? News::where('slug', $slug)->first()
            ?? Article::where('slug', $slug)->first();

        if (!$item) {
            abort(404);
        }

        return view('modules.informasi.public_show', compact('item'));
    }
    public function index(Request $request)
    {
        // Tab aktif dari query string, default pengumuman
        $tab = $request->query('tab', 'pengumuman');

        // Ambil data sesuai kebutuhan (sesuaikan pagination jika perlu)
        $pengumuman = Announcement::latest()->take(20)->get();
        $berita     = News::latest()->take(20)->get();
        $artikel    = Article::with('category')->latest()->take(20)->get();

        return response()->view('modules.informasi.index', [
            'tab' => $tab,
            'pengumuman' => $pengumuman,
            'berita' => $berita,
            'artikel' => $artikel,
        ])->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
