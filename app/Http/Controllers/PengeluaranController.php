<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index()
    {
        // 1. Data Tabel (Tetap)
        $pengeluaran = Pengeluaran::with(['kategori', 'user'])->latest()->get();
        $kategori = KategoriPengeluaran::all();

        // 2. Data Ringkasan (Tetap)
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', date('m'))
                                    ->whereYear('tanggal', date('Y'))
                                    ->sum('jumlah');
        $totalSemua = Pengeluaran::sum('jumlah');

        // 3. (BARU) Data untuk Grafik Pie Chart
        // Kita kelompokkan pengeluaran berdasarkan kategori_id, lalu kita jumlahkan
        $dataChart = Pengeluaran::selectRaw('kategori_id, sum(jumlah) as total')
                                ->groupBy('kategori_id')
                                ->with('kategori')
                                ->get();

        // Kita pisahkan jadi dua array biar gampang dibaca Chart.js
        // Pluck('kategori.nama_kategori') artinya ambil nama kategorinya saja
        $labels = $dataChart->pluck('kategori.nama_kategori'); 
        // Pluck('total') artinya ambil angkanya saja
        $dataset = $dataChart->pluck('total');

        return view('modules.keuangan.pengeluaran.index', compact(
            'pengeluaran', 
            'kategori', 
            'totalBulanIni', 
            'totalSemua',
            'labels',   // Kirim ke view
            'dataset'   // Kirim ke view
        ));
    }

    // Menyimpan transaksi pengeluaran baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_pengeluaran' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar max 2MB
        ]);

        // Setup data dasar
        $data = $request->all();
        $data['user_id'] = Auth::id(); // Otomatis ambil ID user yang sedang login

        // Cek apakah ada file bukti transaksi yang diupload
        if ($request->hasFile('bukti_transaksi')) {
            // Simpan ke folder 'public/bukti_pengeluaran'
            $path = $request->file('bukti_transaksi')->store('bukti_pengeluaran', 'public');
            $data['bukti_transaksi'] = $path;
        }

        Pengeluaran::create($data);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil disimpan!');
    }

    // Update data pengeluaran
    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $request->validate([
            'judul_pengeluaran' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Logika update gambar: Hapus gambar lama jika user upload gambar baru
        if ($request->hasFile('bukti_transaksi')) {
            if ($pengeluaran->bukti_transaksi && Storage::disk('public')->exists($pengeluaran->bukti_transaksi)) {
                Storage::disk('public')->delete($pengeluaran->bukti_transaksi);
            }
            $path = $request->file('bukti_transaksi')->store('bukti_pengeluaran', 'public');
            $data['bukti_transaksi'] = $path;
        }

        $pengeluaran->update($data);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    // Hapus data pengeluaran
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Hapus file bukti fisik jika ada
        if ($pengeluaran->bukti_transaksi && Storage::disk('public')->exists($pengeluaran->bukti_transaksi)) {
            Storage::disk('public')->delete($pengeluaran->bukti_transaksi);
        }

        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    }

    // Cetak Laporan PDF
    public function cetakLaporan(Request $request)
    {
        // 1. Ambil inputan tanggal filter (opsional, default bulan ini)
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Ambil data sesuai filter
        $pengeluaran = Pengeluaran::with(['kategori', 'user'])
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun)
                        ->orderBy('tanggal', 'asc')
                        ->get();

        // 3. Hitung total
        $total = $pengeluaran->sum('jumlah');

        // 4. Siapkan data untuk dikirim ke view PDF
        $data = [
            'pengeluaran' => $pengeluaran,
            'total' => $total,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal_cetak' => date('d F Y')
        ];

        // 5. Load View PDF dan download
        $pdf = Pdf::loadView('modules.keuangan.pengeluaran.cetak_pdf', $data);
        return $pdf->download('Laporan-Pengeluaran-Masjid-'.$bulan.'-'.$tahun.'.pdf');
    }
}