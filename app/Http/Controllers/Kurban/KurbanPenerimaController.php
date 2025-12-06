<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanPenerima;
use Illuminate\Http\Request;

class KurbanPenerimaController extends Controller
{
    public function index()
    {
        $penerima = KurbanPenerima::all();
        return view('modules.kurban.penerima.index', compact('penerima'));
    }

    public function create()
    {
        return view('modules.kurban.penerima.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable|string',
        ]);

        KurbanPenerima::create($request->all());

        return redirect()->route('kurban.penerima.index')->with('success', 'Penerima ditambahkan');
    }
}
