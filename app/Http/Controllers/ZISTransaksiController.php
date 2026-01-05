<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Muzakki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ZISTransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('muzakki')->latest()->paginate(10);
        return view('modules.zis.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $muzakki = Muzakki::all(); 
        $nomorOtomatis = 'TRX-' . time();
        return view('modules.zis.transaksi.create', compact('muzakki', 'nomorOtomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'muzakki_id' => 'required',
            'jenis_transaksi' => 'required',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_transaksi' => 'required|date',
        ]);

        Transaksi::create([
            'kode_transaksi' => $request->kode_transaksi,
            'muzakki_id' => $request->muzakki_id,
            'user_id' => Auth::guard('zis')->id(),
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        return redirect()->route('zis.transaksi.index')->with('success', 'Alhamdulillah, Transaksi ZIS berhasil dicatat!');
    }

    public function destroy(Transaksi $transaksi)
    {
        if (!auth('zis')->check() || auth('zis')->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Transaksi keuangan sensitif hanya boleh dihapus Admin.');
        }

        $transaksi->delete();
        return redirect()->route('zis.transaksi.index')->with('success', 'Data transaksi dihapus.');
    }
}
