<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Tampilkan halaman history data terhapus
     */
    public function index()
    {
        $deletedData = Pemasukan::onlyTrashed()
            ->with(['user', 'verifier', 'rejector'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('history.index', compact('deletedData'));
    }

    /**
     * Restore satu data
     */
    public function restore($id)
    {
        try {
            $data = Pemasukan::onlyTrashed()->findOrFail($id);
            
            // Log activity sebelum restore
            $namaData = $data->jenis . ' - ' . $data->sumber . ' (' . $data->jumlah_rupiah . ')';
            
            $data->restore();

            // Optional: Log activity
            activity()
                ->causedBy(Auth::user())
                ->performedOn($data)
                ->log('Mengembalikan data pemasukan: ' . $namaData);

            return redirect()->back()->with('success', 'Data berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengembalikan data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus permanen satu data
     */
    public function forceDelete($id)
    {
        try {
            $data = Pemasukan::onlyTrashed()->findOrFail($id);
            
            // Simpan info sebelum dihapus permanen
            $namaData = $data->jenis . ' - ' . $data->sumber . ' (' . $data->jumlah_rupiah . ')';
            
            $data->forceDelete();

            // Optional: Log activity
            activity()
                ->causedBy(Auth::user())
                ->log('Menghapus permanen data pemasukan: ' . $namaData);

            return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Reset/Hapus semua data di history
     */
    public function resetAll()
    {
        try {
            $count = Pemasukan::onlyTrashed()->count();
            
            if ($count == 0) {
                return redirect()->back()->with('info', 'Tidak ada data yang perlu dihapus.');
            }
            
            Pemasukan::onlyTrashed()->forceDelete();

            // Optional: Log activity
            activity()
                ->causedBy(Auth::user())
                ->log("Menghapus permanen semua data history ({$count} data)");

            return redirect()->back()->with('success', "Berhasil menghapus {$count} data permanen!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus semua data: ' . $e->getMessage());
        }
    }

    /**
     * Restore semua data
     */
    public function restoreAll()
    {
        try {
            $count = Pemasukan::onlyTrashed()->count();
            
            if ($count == 0) {
                return redirect()->back()->with('info', 'Tidak ada data yang perlu dikembalikan.');
            }
            
            Pemasukan::onlyTrashed()->restore();

            // Optional: Log activity
            activity()
                ->causedBy(Auth::user())
                ->log("Mengembalikan semua data history ({$count} data)");

            return redirect()->back()->with('success', "Berhasil mengembalikan {$count} data!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengembalikan semua data: ' . $e->getMessage());
        }
    }
}