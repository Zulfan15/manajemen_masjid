<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan
     */
    public function index()
    {
        return view('modules.keuangan.laporan.index');
    }

    /**
     * Get data rekap untuk AJAX
     */
    public function rekap(Request $request)
    {
        try {
            Log::info('Laporan rekap called', [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'user' => auth()->user()->name ?? 'guest'
            ]);

            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $query = Pemasukan::query();
            
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }
            
            $pemasukan = $query->orderBy('tanggal', 'asc')->get();
            
            Log::info('Data pemasukan retrieved', [
                'count' => $pemasukan->count(),
                'total' => $pemasukan->sum('jumlah')
            ]);
            
            $total = $pemasukan->sum('jumlah');
            $count = $pemasukan->count();
            $average = $count > 0 ? $total / $count : 0;
            
            $chartData = [];
            
            if ($pemasukan->isNotEmpty()) {
                $grouped = $pemasukan->groupBy(function($item) {
                    return Carbon::parse($item->tanggal)->format('Y-m');
                });
                
                foreach ($grouped as $month => $items) {
                    $chartData[$month] = $items->sum('jumlah');
                }
            }
            
            if ($count === 0) {
                Log::info('No data found for the selected period');
            }
            
            return response()->json([
                'success' => true,
                'total' => (float) $total,
                'count' => $count,
                'average' => round($average, 2),
                'chartData' => $chartData,
                'message' => $count === 0 ? 'Tidak ada data untuk periode yang dipilih' : 'Data berhasil dimuat'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in laporan rekap', [
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid',
                'message' => 'Mohon periksa kembali tanggal yang Anda masukkan',
                'details' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in laporan rekap', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memuat data',
                'message' => config('app.debug') ? $e->getMessage() : 'Silakan coba lagi atau hubungi administrator',
                'debug_info' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Export ke PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            Log::info('PDF Export started', [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'user' => auth()->user()->name ?? 'guest'
            ]);

            // Query data
            $query = Pemasukan::query();
            
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }
            
            $pemasukan = $query->orderBy('tanggal', 'asc')->get();
            $total = $pemasukan->sum('jumlah');
            
            Log::info('PDF data loaded', [
                'records' => $pemasukan->count(),
                'total' => $total
            ]);

            // Generate PDF
            $pdf = Pdf::loadView('modules.keuangan.laporan.pdf', [
                'pemasukan' => $pemasukan,
                'total' => $total,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);
            
            // Set paper dan orientasi
            $pdf->setPaper('a4', 'landscape');
            
            // Nama file
            $filename = 'laporan-keuangan-' . date('Ymd-His') . '.pdf';
            
            Log::info('PDF generated successfully', ['filename' => $filename]);
            
            // Return download response
            return $pdf->download($filename);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in PDF export', ['errors' => $e->errors()]);
            return redirect()->back()->with('error', 'Data tidak valid. Mohon pilih tanggal yang benar.');
            
        } catch (\Exception $e) {
            Log::error('Error in export PDF', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export ke Excel (CSV Format - Tanpa Package)
     */
    public function exportExcel(Request $request)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            Log::info('Excel Export started', [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'user' => auth()->user()->name ?? 'guest'
            ]);

            // Query data
            $query = Pemasukan::query();
            
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }
            
            $pemasukan = $query->orderBy('tanggal', 'asc')->get();
            $total = $pemasukan->sum('jumlah');
            
            // Nama file
            $filename = 'laporan-keuangan-' . date('Ymd-His') . '.csv';
            
            // Header CSV
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];
            
            // Callback untuk generate CSV
            $callback = function() use ($pemasukan, $total) {
                $file = fopen('php://output', 'w');
                
                // BOM untuk UTF-8 (agar Excel bisa baca karakter Indonesia dengan benar)
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Header kolom
                fputcsv($file, ['No', 'Tanggal', 'Jenis', 'Sumber', 'Jumlah (Rp)', 'Keterangan'], ';');
                
                // Data rows
                $no = 1;
                foreach ($pemasukan as $item) {
                    fputcsv($file, [
                        $no++,
                        Carbon::parse($item->tanggal)->format('d/m/Y'),
                        $item->jenis ?? '-',
                        $item->sumber ?? '-',
                        number_format($item->jumlah, 0, ',', '.'),
                        $item->keterangan ?? '-'
                    ], ';');
                }
                
                // Total row
                fputcsv($file, ['', '', '', 'TOTAL:', number_format($total, 0, ',', '.'), ''], ';');
                
                fclose($file);
            };
            
            Log::info('CSV file generated successfully', ['filename' => $filename]);
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in Excel export', ['errors' => $e->errors()]);
            return redirect()->back()->with('error', 'Data tidak valid. Mohon pilih tanggal yang benar.');
            
        } catch (\Exception $e) {
            Log::error('Error in export Excel', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }
}