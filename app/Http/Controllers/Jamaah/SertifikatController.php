<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    /**
     * Display a listing of certificates for Jamaah user
     */
    public function index()
    {
        // Get all sertifikat for this user (match by name)
        $sertifikats = \App\Models\Sertifikat::with(['kegiatan', 'generator'])
            ->where('nama_peserta', auth()->user()->name)
            ->whereHas('kegiatan', function($query) {
                $query->where('sertifikat_tersedia', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modules.jamaah.sertifikat.index', compact('sertifikats'));
    }

    /**
     * Download certificate for Jamaah user
     */
    public function download($id)
    {
        // Find certificate
        $sertifikat = \App\Models\Sertifikat::findOrFail($id);
        
        // Check if this certificate belongs to the authenticated user
        // by checking if user's name matches the certificate nama_peserta
        if ($sertifikat->nama_peserta !== auth()->user()->name) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }
        
        // Increment download count
        $sertifikat->incrementDownload();
        
        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.kegiatan.sertifikat.pdf', [
            'sertifikat' => $sertifikat
        ]);
        
        // Set paper to A4 landscape
        $pdf->setPaper('a4', 'landscape');
        
        // Generate filename
        $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $sertifikat->nama_peserta);
        $cleanNumber = preg_replace('/[^A-Za-z0-9\-]/', '_', $sertifikat->nomor_sertifikat);
        $filename = 'Sertifikat_' . $cleanName . '_' . $cleanNumber . '.pdf';
        
        return $pdf->download($filename);
    }
}
