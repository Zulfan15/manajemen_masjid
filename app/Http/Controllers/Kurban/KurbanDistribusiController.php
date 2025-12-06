<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanDistribusi;
use App\Models\KurbanPenerima;
use App\Models\KurbanHasilPotong;
use Illuminate\Http\Request;

class KurbanDistribusiController extends Controller
{
    public function index()
    {
        $distribusi = KurbanDistribusi::with(['hasil', 'penerima'])->get();
        return view('modules.kurban.distribusi.index', compact('distribusi'));
    }

    public function create()
    {
        $hasil = KurbanHasilPotong::all();
        $penerima = KurbanPenerima::all();

        return view('modules.kurban.distribusi.create', compact('hasil', 'penerima'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hasil_id' => 'required',
            'penerima_id' => 'required',
            'jumlah_kantong' => 'required|integer|min:1',
            'status' => 'required|in:proses,selesai',
        ]);

        KurbanDistribusi::create($request->all());

        return redirect()->route('kurban.distribusi.index')->with('success', 'Distribusi dicatat');
    }
}
