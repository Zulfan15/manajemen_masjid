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
        $data = KurbanHasilPotong::with('penyembelihan.hewan')->get();
        return view('kurban.hasil.index', compact('data'));
    }

    public function create()
    {
        $penyembelihan = KurbanPenyembelihan::all();
        return view('kurban.hasil.create', compact('penyembelihan'));
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
