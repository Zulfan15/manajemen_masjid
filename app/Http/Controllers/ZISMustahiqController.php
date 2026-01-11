<?php

namespace App\Http\Controllers;

use App\Models\Mustahiq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ZISMustahiqController extends Controller
{
    public function index()
    {
        $mustahiq = Mustahiq::latest()->paginate(10);
        return view('modules.zis.mustahiq.index', compact('mustahiq'));
    }

    public function create()
    {
        return view('modules.zis.mustahiq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'kategori' => 'required',
        ]);

        Mustahiq::create($request->all());

        return redirect()->route('zis.mustahiq.index')->with('success', 'Data Mustahiq berhasil ditambahkan!');
    }

    public function edit(Mustahiq $mustahiq)
    {
        return view('modules.zis.mustahiq.edit', compact('mustahiq'));
    }

    public function update(Request $request, Mustahiq $mustahiq)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'kategori' => 'required',
        ]);

        $mustahiq->update($request->all());

        return redirect()->route('zis.mustahiq.index')->with('success', 'Data Mustahiq berhasil diupdate!');
    }

    public function destroy(Mustahiq $mustahiq)
    {
        if (!auth('zis')->check() || auth('zis')->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Admin yang boleh menghapus data Mustahiq.');
        }

        $mustahiq->delete();
        return redirect()->route('zis.mustahiq.index')->with('success', 'Data Mustahiq berhasil dihapus!');
    }
}
