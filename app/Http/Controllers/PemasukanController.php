<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\TransaksiDiterimaNotification;

class PemasukanController extends Controller
{
   public function __construct()
{
    $this->middleware('auth');
    
    // Middleware untuk admin keuangan - hanya berlaku untuk method tertentu
    $this->middleware(function ($request, $next) {
        if (!auth()->user()->canViewKeuangan()) {
            abort(403, 'Anda tidak memiliki akses ke modul keuangan');
        }
        return $next($request);
    })->only(['index', 'show', 'getData']);
    
    // Middleware untuk manage keuangan - hanya untuk create, update, delete
    $this->middleware(function ($request, $next) {
        if (!auth()->user()->canManageKeuangan()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini. Mode view only.'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini. Mode view only.');
        }
        return $next($request);
    })->only(['create', 'store', 'edit', 'update', 'destroy', 'verifikasi', 'tolak']);
    
    // ✅ Method jamaah (jamaahPemasukan, jamaahStore, jamaahDetail) 
    // TIDAK ada middleware tambahan - semua user login bisa akses
}

    // =========================================================================
    // CRUD METHODS (UNTUK ADMIN KEUANGAN)
    // =========================================================================

    /**
     * Menampilkan semua data pemasukan dalam satu halaman
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query dasar
        $query = Pemasukan::query();
        
        // Super admin dan admin keuangan bisa lihat semua data
        if ($user->isSuperAdmin() || $user->canManageKeuangan()) {
            $query->with('user');
        } else {
            // Role lain hanya lihat data sendiri (jika ada)
            $query->where('user_id', $user->id);
        }
        
        // Apply ordering
        $data = $query->orderBy('tanggal', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        // Hitung statistik bulan ini
        $bulanIniQuery = Pemasukan::whereYear('tanggal', date('Y'))
                    ->whereMonth('tanggal', date('m'));
                    
        if (!$user->isSuperAdmin() && !$user->canManageKeuangan()) {
            $bulanIniQuery->where('user_id', $user->id);
        }
        
        $bulanIni = $bulanIniQuery->sum('jumlah');
        
        // Hitung total semua data
        $totalPemasukan = $data->sum('jumlah');
        $transaksiBulanIni = $data->whereBetween('tanggal', [date('Y-m-01'), date('Y-m-d')])->count();
        $rataRata = $data->count() > 0 ? $data->avg('jumlah') : 0;

        // Jika ada filter rekap
        $show_rekap = false;
        $startDate = null;
        $endDate = null;
        $totalRekap = 0;
        
        if ($request->has('start_date') || $request->has('end_date') || $request->has('show_rekap')) {
            $startDate = $request->start_date ?? date('Y-m-01');
            $endDate = $request->end_date ?? date('Y-m-d');
            $show_rekap = true;
            
            $rekapData = $data->whereBetween('tanggal', [$startDate, $endDate]);
            $totalRekap = $rekapData->sum('jumlah');
        }

        // Tentukan view yang akan digunakan
        $viewPath = 'modules.keuangan.pemasukan.index';
        
        // Return view dengan semua data
        return view($viewPath, compact(
            'data', 
            'user', 
            'bulanIni',
            'totalPemasukan',
            'transaksiBulanIni',
            'rataRata',
            'startDate',
            'endDate',
            'totalRekap',
            'show_rekap'
        ));
    }

    /**
     * Method untuk create form (jika menggunakan view terpisah)
     */
    public function create()
    {
        return view('modules.keuangan.pemasukan.create');
    }

    /**
     * Simpan pemasukan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'sumber' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string',
        ]);

        Pemasukan::create([
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'sumber' => $request->sumber,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id(),
            'status' => 'pending', // Status default: pending
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pemasukan berhasil ditambahkan! Menunggu verifikasi admin.');
    }

    /**
     * Method untuk show detail
     */
    public function show($id)
    {
        $pemasukan = Pemasukan::with('user')->findOrFail($id);
        
        // Cek hak akses: super admin dan admin keuangan bisa lihat semua
        // Role lain hanya bisa lihat data sendiri
        if (!Auth::user()->isSuperAdmin() && 
            !Auth::user()->canManageKeuangan() && 
            $pemasukan->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }
        
        return view('modules.keuangan.pemasukan.show', compact('pemasukan'));
    }

    /**
     * Method untuk edit form (jika ada view terpisah)
     */
    public function edit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        return view('modules.keuangan.pemasukan.edit', compact('pemasukan'));
    }

    /**
     * Update pemasukan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'sumber' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string',
        ]);

        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->update($request->all());

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pemasukan berhasil diperbarui!');
    }

    /**
     * Hapus pemasukan
     */
    public function destroy($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();

            // Jika request AJAX
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pemasukan berhasil dihapus!'
                ]);
            }

            // Jika request biasa (form submit)
            return redirect()->route('pemasukan.index')
                ->with('success', 'Data pemasukan berhasil dihapus!');
                
        } catch (\Exception $e) {
            // Jika request AJAX
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }
            
            // Jika request biasa
            return redirect()->route('pemasukan.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get data untuk edit modal (AJAX call)
     */
    public function getData($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        return response()->json($pemasukan);
    }

    // =========================================================================
    // 🆕 METHODS UNTUK JAMAAH (USER BIASA)
    // =========================================================================

    /**
     * Halaman pemasukan untuk jamaah - Lihat riwayat & input donasi
     */
   /**
 * Halaman pemasukan untuk jamaah - Lihat riwayat & input donasi
 */
public function jamaahPemasukan()
{
    $user = auth()->user();
    
    // ✅ HANYA ambil pemasukan milik user yang sedang login
    $pemasukan = Pemasukan::where('user_id', $user->id)
                          ->orderBy('tanggal', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
    
    return view('jamaah.pemasukan', compact('pemasukan'));
}
    /**
     * Simpan pemasukan dari jamaah
     */
    public function jamaahStore(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $data = [
            'user_id' => auth()->id(),
            'jenis' => $request->kategori, // Mapping kategori ke jenis
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'sumber' => $request->metode_pembayaran, // Mapping metode ke sumber
            'keterangan' => $request->keterangan,
            'status' => 'pending', // Menunggu verifikasi
        ];

        // Upload bukti transfer jika ada
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Buat folder jika belum ada
            $uploadPath = public_path('uploads/bukti_transfer');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $file->move($uploadPath, $filename);
            $data['bukti_transfer'] = $filename;
        }

        Pemasukan::create($data);

        return redirect()->route('jamaah.pemasukan')
                         ->with('success', 'Donasi berhasil dikirim! Menunggu verifikasi dari pengurus.');
    }

    /**
     * Detail pemasukan untuk jamaah
     */
    public function jamaahDetail($id)
    {
        $pemasukan = Pemasukan::where('id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();
        
        return view('jamaah.pemasukan-detail', compact('pemasukan'));
    }

    // =========================================================================
    // VERIFIKASI & NOTIFIKASI METHODS (FITUR BARU)
    // =========================================================================

    /**
     * VERIFIKASI transaksi dan kirim notifikasi ke user
     */
    public function verifikasi($id)
    {
        try {
            // 1. Cari transaksi dengan relasi user
            $pemasukan = Pemasukan::with('user')->findOrFail($id);
            
            // 2. Cek apakah sudah diverifikasi sebelumnya
            if ($pemasukan->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Transaksi ini sudah diverifikasi sebelumnya!'
                ], 400);
            }
            
            // 3. Update status transaksi menjadi verified
            $pemasukan->status = 'verified';
            $pemasukan->verified_at = now();
            $pemasukan->verified_by = auth()->id();
            $pemasukan->save();
            
            // 4. Siapkan data untuk notifikasi
            $jenisTransaksi = ucfirst($pemasukan->jenis ?? 'Pemasukan');
            $emailStatus = '⚠️ User tidak memiliki email';
            
            // 5. Kirim notifikasi email ke user
            if ($pemasukan->user && $pemasukan->user->email) {
                try {
                    $pemasukan->user->notify(
                        new TransaksiDiterimaNotification($pemasukan, $jenisTransaksi)
                    );
                    
                    $emailStatus = '✅ Email berhasil dikirim ke: ' . $pemasukan->user->email;
                    
                    Log::info("Email verifikasi berhasil dikirim", [
                        'user_id' => $pemasukan->user->id,
                        'email' => $pemasukan->user->email,
                        'pemasukan_id' => $pemasukan->id,
                    ]);
                    
                } catch (\Exception $e) {
                    $emailStatus = '⚠️ Email gagal dikirim: ' . $e->getMessage();
                    
                    Log::error("Gagal mengirim email verifikasi", [
                        'error' => $e->getMessage(),
                        'pemasukan_id' => $pemasukan->id,
                    ]);
                }
            }
            
            // 6. Response sukses
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diverifikasi! ' . $emailStatus,
                'data' => [
                    'id' => $pemasukan->id,
                    'status' => $pemasukan->status,
                    'verified_at' => $pemasukan->verified_at->format('d-m-Y H:i:s'),
                    'verified_by' => auth()->user()->name,
                    'email_sent_to' => $pemasukan->user->email ?? 'N/A',
                    'user_name' => $pemasukan->user->name ?? 'N/A',
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Data pemasukan tidak ditemukan!'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error("Error verifikasi transaksi", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal memverifikasi transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * TOLAK transaksi dan kirim notifikasi ke user
     */
    public function tolak(Request $request, $id)
    {
        try {
            // 1. Validasi alasan penolakan
            $request->validate([
                'alasan_tolak' => 'required|string|max:500'
            ]);
            
            // 2. Cari transaksi
            $transaksi = Pemasukan::with('user')->findOrFail($id);
            
            // 3. Update status transaksi
            $transaksi->status = 'rejected';
            $transaksi->alasan_tolak = $request->alasan_tolak;
            $transaksi->rejected_at = now();
            $transaksi->rejected_by = auth()->id();
            $transaksi->save();
            
            // 4. Kirim notifikasi penolakan ke user (opsional)
            if ($transaksi->user) {
                try {
                    $this->sendRejectionEmail($transaksi);
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim notifikasi penolakan: " . $e->getMessage());
                }
            }
            
            // 5. Response sukses
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil ditolak!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Transaksi berhasil ditolak!');
            
        } catch (\Exception $e) {
            Log::error("Error menolak transaksi: " . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak transaksi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Kirim email notifikasi penolakan
     */
    private function sendRejectionEmail($transaksi)
    {
        try {
            $user = $transaksi->user;
            $tanggal = Carbon::parse($transaksi->tanggal)->format('d F Y');
            $jumlah = number_format($transaksi->jumlah, 0, ',', '.');
            
            Mail::send([], [], function ($message) use ($user, $transaksi, $tanggal, $jumlah) {
                $message->to($user->email, $user->name)
                    ->subject('❌ Transaksi Ditolak - Manajemen Masjid')
                    ->html("
                    <h2>Assalamualaikum, {$user->name}</h2>
                    <p>Mohon maaf, transaksi Anda telah <strong>DITOLAK</strong> oleh admin.</p>
                    <hr>
                    <p><strong>Detail Transaksi:</strong></p>
                    <ul>
                        <li>Jenis: {$transaksi->jenis}</li>
                        <li>Nominal: Rp {$jumlah}</li>
                        <li>Tanggal: {$tanggal}</li>
                    </ul>
                    <p><strong>Alasan Penolakan:</strong></p>
                    <p style='background: #ffe6e6; padding: 10px; border-left: 4px solid #ff0000;'>{$transaksi->alasan_tolak}</p>
                    <p>Silakan hubungi admin untuk informasi lebih lanjut.</p>
                    <hr>
                    <p><em>Email otomatis dari Sistem Manajemen Masjid</em></p>
                    ");
            });
            
            Log::info("Email penolakan berhasil dikirim ke {$user->email}");
            
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email penolakan: " . $e->getMessage());
        }
    }

    // =========================================================================
    // NOTIFIKASI PEMBATALAN (EXISTING CODE)
    // =========================================================================
    
    /**
     * Kirim notifikasi pembatalan pembayaran ke jemaah
     */
    public function sendCancellationNotification(Request $request)
    {
        try {
            $pemasukan = Pemasukan::with('user')->find($request->id);
            
            if (!$pemasukan) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data pemasukan tidak ditemukan'
                ], 404);
            }
            
            $jemaah = $pemasukan->user;
            
            if (!$jemaah) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data jemaah tidak ditemukan'
                ], 404);
            }
            
            $notificationType = $request->notification_type ?? 'both';
            
            $whatsappSent = false;
            $emailSent = false;
            
            if (in_array($notificationType, ['whatsapp', 'both'])) {
                if (isset($jemaah->no_hp) && !empty($jemaah->no_hp)) {
                    $whatsappSent = $this->sendWhatsAppNotification($jemaah, $pemasukan);
                } else {
                    Log::warning("WhatsApp tidak dikirim: No HP tidak tersedia untuk user ID {$jemaah->id}");
                }
            }
            
            if (in_array($notificationType, ['email', 'both'])) {
                if (isset($jemaah->email) && !empty($jemaah->email)) {
                    $emailSent = $this->sendEmailNotification($jemaah, $pemasukan);
                } else {
                    Log::warning("Email tidak dikirim: Email tidak tersedia untuk user ID {$jemaah->id}");
                }
            }
            
            return response()->json([
                'success' => true,
                'whatsapp' => $whatsappSent,
                'email' => $emailSent,
                'message' => 'Notifikasi berhasil diproses'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error sending cancellation notification: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim notifikasi via WhatsApp
     */
    private function sendWhatsAppNotification($jemaah, $pemasukan)
    {
        try {
            $message = "🕌 *MASJID - Notifikasi Pembatalan Pembayaran*\n\n";
            $message .= "Assalamu'alaikum Wr. Wb.\n\n";
            $message .= "Yth. *{$jemaah->name}*\n\n";
            $message .= "Dengan ini kami informasikan bahwa pembayaran Anda telah *DIBATALKAN* oleh administrator:\n\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "📅 *Tanggal:* " . Carbon::parse($pemasukan->tanggal)->format('d/m/Y') . "\n";
            $message .= "💰 *Jumlah:* Rp " . number_format($pemasukan->jumlah, 0, ',', '.') . "\n";
            $message .= "📝 *Jenis:* {$pemasukan->jenis}\n";
            
            if (!empty($pemasukan->sumber)) {
                $message .= "👤 *Sumber:* {$pemasukan->sumber}\n";
            }
            
            if (!empty($pemasukan->keterangan)) {
                $message .= "📄 *Keterangan:* {$pemasukan->keterangan}\n";
            }
            
            $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "❓ Untuk informasi lebih lanjut atau jika ada pertanyaan, silakan hubungi administrator masjid.\n\n";
            $message .= "Jazakumullahu khairan\n";
            $message .= "Wassalamu'alaikum Wr. Wb.\n\n";
            $message .= "_Pesan otomatis dari Sistem Manajemen Masjid_";
            
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query([
                    'target' => $jemaah->no_hp,
                    'message' => $message,
                    'countryCode' => '62',
                ]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: ' . env('FONNTE_TOKEN', 'YOUR_FONNTE_TOKEN')
                ],
            ]);
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($httpCode == 200) {
                Log::info("WhatsApp berhasil dikirim ke {$jemaah->no_hp}");
                return true;
            } else {
                Log::error("WhatsApp gagal dikirim. HTTP Code: {$httpCode}, Response: {$response}");
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi via Email
     */
    private function sendEmailNotification($jemaah, $pemasukan)
    {
        try {
            Mail::send([], [], function ($message) use ($jemaah, $pemasukan) {
                $message->to($jemaah->email, $jemaah->name)
                    ->subject('🕌 Notifikasi Pembatalan Pembayaran - Masjid')
                    ->html($this->getEmailTemplate($jemaah, $pemasukan));
            });
            
            Log::info("Email berhasil dikirim ke {$jemaah->email}");
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email Notification Error: ' . $e->getMessage());
            return false;
        }
    }

   /**
 * Template HTML untuk email pembatalan
 */
private function getEmailTemplate($jemaah, $pemasukan)
{
    $tanggal = Carbon::parse($pemasukan->tanggal)->format('d F Y');
    $jumlah = number_format($pemasukan->jumlah, 0, ',', '.');
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Notifikasi Pembatalan Pembayaran</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
        <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
            <h1 style='color: white; margin: 0; font-size: 24px;'>🕌 Sistem Manajemen Masjid</h1>
            <p style='color: white; margin: 10px 0 0 0; opacity: 0.9;'>Notifikasi Pembatalan Pembayaran</p>
        </div>
        
        <div style='background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none;'>
            <p style='margin-top: 0;'>Assalamu'alaikum Wr. Wb.</p>
            
            <p>Yth. <strong>{$jemaah->name}</strong>,</p>
            
            <p>Dengan ini kami informasikan bahwa pembayaran Anda telah <strong style='color: #dc3545;'>DIBATALKAN</strong> oleh administrator:</p>
            
            <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545;'>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; width: 40%;'><strong>📅 Tanggal:</strong></td>
                        <td style='padding: 8px 0;'>{$tanggal}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0;'><strong>💰 Jumlah:</strong></td>
                        <td style='padding: 8px 0; color: #dc3545; font-weight: bold;'>Rp {$jumlah}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0;'><strong>📝 Jenis:</strong></td>
                        <td style='padding: 8px 0;'>{$pemasukan->jenis}</td>
                    </tr>
                    " . (!empty($pemasukan->sumber) ? "
                    <tr>
                        <td style='padding: 8px 0;'><strong>👤 Sumber:</strong></td>
                        <td style='padding: 8px 0;'>{$pemasukan->sumber}</td>
                    </tr>
                    " : "") . "
                    " . (!empty($pemasukan->keterangan) ? "
                    <tr>
                        <td style='padding: 8px 0;'><strong>📄 Keterangan:</strong></td>
                        <td style='padding: 8px 0;'>{$pemasukan->keterangan}</td>
                    </tr>
                    " : "") . "
                </table>
            </div>
            
            <div style='background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <p style='margin: 0; color: #856404;'>
                    <strong>❓ Pertanyaan?</strong><br>
                    Untuk informasi lebih lanjut atau jika ada pertanyaan, silakan hubungi administrator masjid.
                </p>
            </div>
            
            <p>Jazakumullahu khairan</p>
            <p>Wassalamu'alaikum Wr. Wb.</p>
            
            <hr style='border: none; border-top: 1px solid #dee2e6; margin: 30px 0;'>
            
            <p style='font-size: 12px; color: #6c757d; text-align: center; margin: 0;'>
                <em>Pesan otomatis dari Sistem Manajemen Masjid</em><br>
                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
            </p>
        </div>
        
        <div style='background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; font-size: 12px; color: #6c757d;'>
            <p style='margin: 0;'>© " . date('Y') . " Sistem Manajemen Masjid. All rights reserved.</p>
        </div>
    </body>
    </html>
    ";
}
}