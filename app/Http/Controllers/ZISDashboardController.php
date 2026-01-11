<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muzakki;
use App\Models\Mustahiq;
use App\Models\Transaksi;
use App\Models\Penyaluran;

class ZISDashboardController extends Controller
{
    public function index()
    {
        $total_muzakki = Muzakki::count();
        $total_mustahiq = Mustahiq::where('status_aktif', 1)->count();
        
        $total_pemasukan = Transaksi::sum('nominal');
        $total_penyaluran = Penyaluran::sum('nominal');
        
        $saldo_akhir = $total_pemasukan - $total_penyaluran;

        $pemasukan_bulan_ini = Transaksi::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('nominal');
        
        $bulan_lalu = date('Y-m-d', strtotime('-1 month'));
        $pemasukan_bulan_lalu = Transaksi::whereYear('created_at', date('Y', strtotime($bulan_lalu)))
            ->whereMonth('created_at', date('m', strtotime($bulan_lalu)))
            ->sum('nominal');
        
        if ($pemasukan_bulan_lalu > 0) {
            $persentase_perubahan = (($pemasukan_bulan_ini - $pemasukan_bulan_lalu) / $pemasukan_bulan_lalu) * 100;
        } else {
            $persentase_perubahan = $pemasukan_bulan_ini > 0 ? 100 : 0;
        }

        return view('modules.zis.dashboard', compact(
            'total_muzakki', 
            'total_mustahiq', 
            'total_pemasukan', 
            'total_penyaluran',
            'saldo_akhir',
            'persentase_perubahan'
        ));
    }
}
