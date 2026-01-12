<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Muzakki;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

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

        // Use database transaction for data consistency
        DB::beginTransaction();
        try {
            // 1. Store to Transaksi table (ZIS Module)
            $transaksi = Transaksi::create([
                'kode_transaksi' => $request->kode_transaksi,
                'muzakki_id' => $request->muzakki_id,
                'user_id' => Auth::guard('zis')->id() ?? Auth::id(),
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);

            // 2. Sync to Pemasukan table (Keuangan Module) - auto verified
            $muzakki = Muzakki::find($request->muzakki_id);
            $sumberNama = $muzakki ? $muzakki->nama : 'Muzakki';

            Pemasukan::create([
                'jenis' => $request->jenis_transaksi, // zakat_fitrah, zakat_maal, infak, sedekah
                'jumlah' => $request->nominal,
                'tanggal' => $request->tanggal_transaksi,
                'sumber' => "ZIS - {$sumberNama}",
                'keterangan' => "[ZIS Sync - {$request->kode_transaksi}] " . ($request->keterangan ?? ''),
                'user_id' => Auth::guard('zis')->id() ?? Auth::id(),
                'status' => 'verified', // Auto-verified karena sudah melalui ZIS module
                'verified_at' => now(),
                'verified_by' => Auth::guard('zis')->id() ?? Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('zis.transaksi.index')->with('success', 'Alhamdulillah, Transaksi ZIS berhasil dicatat dan disinkronkan ke keuangan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        if (!auth('zis')->check() || auth('zis')->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Transaksi keuangan sensitif hanya boleh dihapus Admin.');
        }

        DB::beginTransaction();
        try {
            // Also delete corresponding Pemasukan record
            Pemasukan::where('keterangan', 'like', "%{$transaksi->kode_transaksi}%")->delete();

            $transaksi->delete();
            DB::commit();

            return redirect()->route('zis.transaksi.index')->with('success', 'Data transaksi dihapus beserta sinkronisasi keuangan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
