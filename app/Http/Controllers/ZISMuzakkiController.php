<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ZISMuzakkiController extends Controller
{
    public function index()
    {
        $muzakki = Muzakki::latest()->paginate(10);
        return view('modules.zis.muzakki.index', compact('muzakki'));
    }

    public function create()
    {
        return view('modules.zis.muzakki.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        Muzakki::create($request->all());

        return redirect()->route('zis.muzakki.index')
            ->with('success', 'Data Muzakki berhasil ditambahkan!');
    }

    public function edit(Muzakki $muzakki)
    {
        return view('modules.zis.muzakki.edit', compact('muzakki'));
    }

    public function update(Request $request, Muzakki $muzakki)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $muzakki->update($request->all());

        return redirect()->route('zis.muzakki.index')
            ->with('success', 'Data Muzakki berhasil diupdate!');
    }

    public function destroy(Muzakki $muzakki)
    {
        if (!auth('zis')->check() || auth('zis')->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Admin yang boleh menghapus data Muzakki.');
        }

        $muzakki->delete();

        return redirect()->route('zis.muzakki.index')
            ->with('success', 'Data Muzakki berhasil dihapus!');
    }
}
