<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanPeserta;
use Illuminate\Http\Request;

class KurbanPesertaController extends Controller
{
    public function index()
    {
        $peserta = KurbanPeserta::latest()->get();
        return view('modules.kurban.peserta.index', compact('peserta'));
    }

    public function create()
    {
        return view('modules.kurban.peserta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kontak' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        KurbanPeserta::create($request->all());

        return redirect()->route('kurban.peserta.index')->with('success', 'Peserta berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peserta = KurbanPeserta::findOrFail($id);
        return view('modules.kurban.peserta.edit', compact('peserta'));
    }

    public function update(Request $request, $id)
    {
        $peserta = KurbanPeserta::findOrFail($id);

        $peserta->update($request->all());

        return redirect()->route('kurban.peserta.index')->with('success', 'Peserta berhasil diupdate');
    }

    public function destroy($id)
    {
        KurbanPeserta::destroy($id);

        return back()->with('success', 'Peserta dihapus');
    }
}
