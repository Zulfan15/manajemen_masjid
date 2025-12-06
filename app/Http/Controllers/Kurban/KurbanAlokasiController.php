<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanAlokasi;
use App\Models\KurbanPeserta;
use App\Models\KurbanHewan;
use Illuminate\Http\Request;

class KurbanAlokasiController extends Controller
{
    public function index()
    {
        $data = KurbanAlokasi::with(['peserta', 'hewan'])->get();
        return view('kurban.alokasi.index', compact('data'));
    }

    public function create()
    {
        $peserta = KurbanPeserta::all();
        $hewan = KurbanHewan::all();
        return view('kurban.alokasi.create', compact('peserta', 'hewan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required',
            'hewan_id' => 'required',
            'porsi' => 'required|integer|min:1|max:7',
            'nama_shohibul' => 'nullable|string'
        ]);

        KurbanAlokasi::create($request->all());

        return redirect()->route('kurban.alokasi.index')->with('success', 'Alokasi berhasil dibuat');
    }

    public function destroy($id)
    {
        KurbanAlokasi::destroy($id);
        return back()->with('success', 'Alokasi dihapus');
    }
}
