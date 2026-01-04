<?php

namespace App\Http\Controllers;

use App\Models\AktivitasHarian;
use App\Models\Takmir;
use App\Exports\AktivitasHarianExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;
// use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AktivitasHarianController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource (role-based)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = AktivitasHarian::with('takmir');

        // Role-based filtering
        if ($user->hasRole('pengurus_takmir')) {
            // Pengurus hanya lihat aktivitas sendiri
            $takmir = Takmir::where('user_id', $user->id)->first();
            if (!$takmir) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak terdaftar sebagai pengurus.');
            }
            $query->where('takmir_id', $takmir->id);
        } elseif ($user->hasRole('admin_takmir')) {
            // Admin bisa filter berdasarkan pengurus
            if ($request->has('takmir_id') && $request->takmir_id !== '') {
                $query->where('takmir_id', $request->takmir_id);
            }
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai !== '') {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir !== '') {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan jenis aktivitas
        if ($request->has('jenis_aktivitas') && $request->jenis_aktivitas !== '') {
            $query->where('jenis_aktivitas', $request->jenis_aktivitas);
        }

        $aktivitas = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Untuk admin, ambil list pengurus untuk filter
        $pengurusList = $user->hasRole('admin_takmir') ? Takmir::aktif()->get() : null;

        $this->activityLogService->log('view', 'aktivitas_harian', 'Melihat daftar aktivitas harian');

        return view('modules.takmir.aktivitas.index', compact('aktivitas', 'pengurusList'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $user = auth()->user();

        // Cek apakah user adalah pengurus
        $takmir = Takmir::where('user_id', $user->id)->first();
        if (!$takmir && !$user->hasRole('admin_takmir')) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terdaftar sebagai pengurus.');
        }

        // Untuk admin, bisa pilih pengurus mana yang mau diinput aktivitasnya
        $pengurusList = $user->hasRole('admin_takmir') ? Takmir::aktif()->get() : null;

        return view('modules.takmir.aktivitas.create', compact('takmir', 'pengurusList'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'takmir_id' => $user->hasRole('admin_takmir') ? 'required|exists:takmir,id' : 'nullable',
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis_aktivitas' => 'required|in:Ibadah,Kebersihan,Administrasi,Pengajaran,Pembinaan,Keuangan,Sosial,Lainnya',
            'deskripsi' => 'required|string|min:10',
            'durasi_jam' => 'nullable|numeric|min:0.5|max:24',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'jenis_aktivitas.required' => 'Jenis aktivitas wajib dipilih',
            'deskripsi.required' => 'Deskripsi aktivitas wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'durasi_jam.min' => 'Durasi minimal 0.5 jam',
            'durasi_jam.max' => 'Durasi maksimal 24 jam',
            'bukti_foto.image' => 'File harus berupa gambar',
            'bukti_foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Set takmir_id berdasarkan role
        if ($user->hasRole('pengurus_takmir')) {
            $takmir = Takmir::where('user_id', $user->id)->first();
            $validated['takmir_id'] = $takmir->id;
        }

        // Handle upload bukti foto
        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('aktivitas', 'public');
        }

        $aktivitas = AktivitasHarian::create($validated);

        $this->activityLogService->log(
            'create',
            'aktivitas_harian',
            "Menambah aktivitas harian: {$aktivitas->jenis_aktivitas} - {$aktivitas->tanggal->format('d/m/Y')}",
            ['aktivitas_id' => $aktivitas->id]
        );

        return redirect()->route('takmir.aktivitas.index')
            ->with('success', 'Aktivitas harian berhasil ditambahkan');
    }

    /**
     * Display the specified resource
     */
    public function show(AktivitasHarian $aktivita)
    {
        $user = auth()->user();

        // Cek akses: pengurus hanya bisa lihat aktivitas sendiri
        if ($user->hasRole('pengurus_takmir')) {
            $takmir = Takmir::where('user_id', $user->id)->first();
            if ($aktivita->takmir_id !== $takmir->id) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        $this->activityLogService->log('view', 'aktivitas_harian', "Melihat detail aktivitas harian ID: {$aktivita->id}");

        return view('modules.takmir.aktivitas.show', compact('aktivita'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(AktivitasHarian $aktivita)
    {
        $user = auth()->user();

        // Cek akses: pengurus hanya bisa edit aktivitas sendiri
        if ($user->hasRole('pengurus_takmir')) {
            $takmir = Takmir::where('user_id', $user->id)->first();
            if ($aktivita->takmir_id !== $takmir->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit aktivitas ini.');
            }

            // Cek apakah masih dalam batas waktu edit (24 jam)
            if ($aktivita->created_at->diffInHours(now()) > 24) {
                return redirect()->route('takmir.aktivitas.index')
                    ->with('error', 'Aktivitas hanya bisa diedit dalam 24 jam setelah dibuat.');
            }
        }

        $pengurusList = $user->hasRole('admin_takmir') ? Takmir::aktif()->get() : null;

        return view('modules.takmir.aktivitas.edit', compact('aktivita', 'pengurusList'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, AktivitasHarian $aktivita)
    {
        $user = auth()->user();

        // Cek akses
        if ($user->hasRole('pengurus_takmir')) {
            $takmir = Takmir::where('user_id', $user->id)->first();
            if ($aktivita->takmir_id !== $takmir->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit aktivitas ini.');
            }

            if ($aktivita->created_at->diffInHours(now()) > 24) {
                return redirect()->route('takmir.aktivitas.index')
                    ->with('error', 'Aktivitas hanya bisa diedit dalam 24 jam setelah dibuat.');
            }
        }

        $validated = $request->validate([
            'takmir_id' => $user->hasRole('admin_takmir') ? 'required|exists:takmir,id' : 'nullable',
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis_aktivitas' => 'required|in:Ibadah,Kebersihan,Administrasi,Pengajaran,Pembinaan,Keuangan,Sosial,Lainnya',
            'deskripsi' => 'required|string|min:10',
            'durasi_jam' => 'nullable|numeric|min:0.5|max:24',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload bukti foto baru
        if ($request->hasFile('bukti_foto')) {
            if ($aktivita->bukti_foto) {
                Storage::disk('public')->delete($aktivita->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('aktivitas', 'public');
        }

        $aktivita->update($validated);

        $this->activityLogService->log(
            'update',
            'aktivitas_harian',
            "Mengupdate aktivitas harian ID: {$aktivita->id}",
            ['aktivitas_id' => $aktivita->id]
        );

        return redirect()->route('takmir.aktivitas.index')
            ->with('success', 'Aktivitas harian berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(AktivitasHarian $aktivita)
    {
        $user = auth()->user();

        // Cek akses
        if ($user->hasRole('pengurus_takmir')) {
            $takmir = Takmir::where('user_id', $user->id)->first();
            if ($aktivita->takmir_id !== $takmir->id) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus aktivitas ini.');
            }
        }

        // Hapus foto jika ada
        if ($aktivita->bukti_foto) {
            Storage::disk('public')->delete($aktivita->bukti_foto);
        }

        $aktivita->delete();

        $this->activityLogService->log(
            'delete',
            'aktivitas_harian',
            "Menghapus aktivitas harian: {$aktivita->jenis_aktivitas} - {$aktivita->tanggal->format('d/m/Y')}"
        );

        return redirect()->route('takmir.aktivitas.index')
            ->with('success', 'Aktivitas harian berhasil dihapus');
    }

    /**
     * Export aktivitas data to Excel
     */
    public function export()
    {
        // Temporarily disabled - requires maatwebsite/excel package
        return redirect()->back()->with('error', 'Fitur export Excel belum tersedia. Install package maatwebsite/excel terlebih dahulu.');
        
        // $this->activityLogService->log('export', 'aktivitas_harian', 'Export data aktivitas harian ke Excel');
        // return Excel::download(new AktivitasHarianExport, 'data-aktivitas-harian-' . date('Y-m-d') . '.xlsx');
    }
}
