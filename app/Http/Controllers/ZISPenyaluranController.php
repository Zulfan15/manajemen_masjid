<?php

namespace App\Http\Controllers;

use App\Models\Penyaluran;
use App\Models\Mustahiq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZISPenyaluranController extends Controller
{
    public function index()
    {
        $penyaluran = Penyaluran::with('mustahiq')->latest()->paginate(10);
        return view('modules.zis.penyaluran.index', compact('penyaluran'));
    }

    public function create()
    {
        $mustahiq = Mustahiq::where('status_aktif', 1)->get();
        
        $nomorOtomatis = 'OUT-' . time();
        
        return view('modules.zis.penyaluran.create', compact('mustahiq', 'nomorOtomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mustahiq_id' => 'required',
            'jenis_bantuan' => 'required',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
        ]);

        Penyaluran::create([
            'kode_penyaluran' => $request->kode_penyaluran,
            'mustahiq_id' => $request->mustahiq_id,
            'user_id' => Auth::guard('zis')->id(),
            'jenis_bantuan' => $request->jenis_bantuan,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal_penyaluran' => $request->tanggal_penyaluran,
        ]);

        return redirect()->route('zis.penyaluran.index')
            ->with('success', 'Alhamdulillah, Dana berhasil disalurkan!');
    }

    public function destroy(Penyaluran $penyaluran)
    {
        $penyaluran->delete();
        return redirect()->route('zis.penyaluran.index')->with('success', 'Data penyaluran dihapus.');
    }
}
