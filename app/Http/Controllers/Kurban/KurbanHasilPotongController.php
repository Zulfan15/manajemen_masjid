<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanHasilPotong;
use App\Models\KurbanPenyembelihan;
use Illuminate\Http\Request;

class KurbanHasilPotongController extends Controller
{
    public function index()
    {
        $hasil = KurbanHasilPotong::with('penyembelihan.hewan')->get();
        return view('modules.kurban.hasil.index', compact('hasil'));
    }

    public function create()
    {
        $penyembelihan = KurbanPenyembelihan::all();
        return view('modules.kurban.hasil.create', compact('penyembelihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyembelihan_id' => 'required',
            'daging' => 'required|numeric',
            'tulang' => 'required|numeric',
            'jeroan' => 'required|numeric',
            'kulit' => 'required|numeric',
            'total_kantong' => 'required|integer',
        ]);

        KurbanHasilPotong::create($request->all());

        return redirect()->route('kurban.hasil.index')->with('success', 'Hasil potongan tersimpan');
    }
}
