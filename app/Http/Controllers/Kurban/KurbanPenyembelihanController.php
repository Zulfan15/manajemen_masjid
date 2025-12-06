<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanPenyembelihan;
use App\Models\KurbanHewan;
use Illuminate\Http\Request;

class KurbanPenyembelihanController extends Controller
{
    public function index()
    {
        $jadwal = KurbanPenyembelihan::with('hewan')->get();
        return view('modules.kurban.penyembelihan.index', compact('jadwal'));
    }

    public function create()
    {
        $hewan = KurbanHewan::all();
        return view('modules.kurban.penyembelihan.create', compact('hewan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hewan_id' => 'required',
            'tanggal' => 'required|date',
            'jam' => 'nullable',
            'petugas' => 'nullable|string',
        ]);

        KurbanPenyembelihan::create($request->all());

        return redirect()->route('kurban.penyembelihan.index')->with('success', 'Jadwal sembelih ditambahkan');
    }

    public function edit($id)
    {
        $data = KurbanPenyembelihan::findOrFail($id);
        $hewan = KurbanHewan::all();
        return view('modules.kurban.penyembelihan.edit', compact('data', 'hewan'));
    }

    public function update(Request $request, $id)
    {
        $data = KurbanPenyembelihan::findOrFail($id);

        $data->update($request->all());

        return redirect()->route('kurban.penyembelihan.index')->with('success', 'Data berhasil diupdate');
    }
}
