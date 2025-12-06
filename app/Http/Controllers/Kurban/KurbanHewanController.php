<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\KurbanHewan;
use Illuminate\Http\Request;

class KurbanHewanController extends Controller
{
    public function index()
    {
        $data = KurbanHewan::latest()->get();
        return view('kurban.hewan.index', compact('data'));
    }

    public function create()
    {
        return view('kurban.hewan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:sapi,kambing',
            'berat' => 'nullable|numeric',
        ]);

        KurbanHewan::create($request->all());

        return redirect()->route('kurban.hewan.index')->with('success', 'Hewan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $hewan = KurbanHewan::findOrFail($id);
        return view('kurban.hewan.edit', compact('hewan'));
    }

    public function update(Request $request, $id)
    {
        $hewan = KurbanHewan::findOrFail($id);

        $hewan->update($request->all());

        return redirect()->route('kurban.hewan.index')->with('success', 'Hewan berhasil diupdate');
    }

    public function destroy($id)
    {
        KurbanHewan::destroy($id);

        return back()->with('success', 'Hewan dihapus');
    }
}
