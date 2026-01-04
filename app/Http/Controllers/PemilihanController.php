<?php

namespace App\Http\Controllers;

use App\Models\Pemilihan;
use App\Models\Kandidat;
use App\Models\Vote;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;

class PemilihanController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of pemilihan (admin only)
     */
    public function index()
    {
        $this->authorize('takmir.view');

        $pemilihanList = Pemilihan::withCount('kandidat', 'votes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $this->activityLogService->log('view', 'pemilihan', 'Melihat daftar pemilihan');

        return view('modules.takmir.pemilihan.index', compact('pemilihanList'));
    }

    /**
     * Display voting page for users
     */
    public function vote($id)
    {
        $pemilihan = Pemilihan::with(['kandidat.takmir'])
            ->findOrFail($id);

        $user = auth()->user();

        // Check apakah pemilihan sedang berlangsung
        if (!$pemilihan->isBerlangsung()) {
            return redirect()->route('takmir.pemilihan.hasil', $id)
                ->with('info', 'Pemilihan sudah selesai. Lihat hasil pemilihan.');
        }

        // Check apakah user sudah vote
        $hasVoted = $pemilihan->userHasVoted($user->id);

        if ($hasVoted) {
            return redirect()->route('takmir.pemilihan.hasil', $id)
                ->with('info', 'Anda sudah memberikan suara pada pemilihan ini.');
        }

        $this->activityLogService->log('view', 'pemilihan', "Membuka halaman voting: {$pemilihan->judul}");

        return view('modules.takmir.pemilihan.vote', compact('pemilihan'));
    }

    /**
     * Submit vote
     */
    public function submitVote(Request $request, $id)
    {
        $pemilihan = Pemilihan::findOrFail($id);
        $user = auth()->user();

        // Validasi
        if (!$pemilihan->isBerlangsung()) {
            return redirect()->route('takmir.pemilihan.hasil', $id)
                ->with('error', 'Pemilihan sudah selesai.');
        }

        if ($pemilihan->userHasVoted($user->id)) {
            return redirect()->route('takmir.pemilihan.hasil', $id)
                ->with('error', 'Anda sudah memberikan suara.');
        }

        $request->validate([
            'kandidat_id' => 'required|exists:kandidat,id',
        ], [
            'kandidat_id.required' => 'Pilih salah satu kandidat',
            'kandidat_id.exists' => 'Kandidat tidak valid',
        ]);

        // Pastikan kandidat adalah bagian dari pemilihan ini
        $kandidat = Kandidat::where('id', $request->kandidat_id)
            ->where('pemilihan_id', $pemilihan->id)
            ->firstOrFail();

        // Simpan vote dengan transaction
        DB::transaction(function () use ($pemilihan, $kandidat, $user) {
            Vote::create([
                'pemilihan_id' => $pemilihan->id,
                'kandidat_id' => $kandidat->id,
                'user_id' => $user->id,
                'voted_at' => now(),
            ]);
        });

        $this->activityLogService->log(
            'create',
            'vote',
            "Memberikan suara pada pemilihan: {$pemilihan->judul} - Kandidat Nomor {$kandidat->nomor_urut}",
            ['pemilihan_id' => $pemilihan->id, 'kandidat_id' => $kandidat->id]
        );

        return redirect()->route('takmir.pemilihan.hasil', $id)
            ->with('success', 'Terima kasih! Suara Anda berhasil disimpan.');
    }

    /**
     * Display hasil pemilihan
     */
    public function hasil($id)
    {
        $pemilihan = Pemilihan::with(['kandidat.takmir', 'kandidat.votes'])
            ->findOrFail($id);

        $user = auth()->user();
        $hasVoted = $pemilihan->userHasVoted($user->id);

        // Jika pemilihan masih berlangsung dan tidak tampilkan hasil, redirect
        if ($pemilihan->isBerlangsung() && !$pemilihan->tampilkan_hasil && !$hasVoted) {
            return redirect()->route('takmir.pemilihan.vote', $id)
                ->with('info', 'Berikan suara Anda terlebih dahulu.');
        }

        // Get kandidat dengan votes count dan persentase
        $kandidat = $pemilihan->kandidat()
            ->with('takmir')
            ->withCount('votes')
            ->get()
            ->sortByDesc('votes_count')
            ->values();

        // Calculate persentase untuk setiap kandidat
        $totalVotes = $pemilihan->totalVotes();
        $kandidat = $kandidat->map(function ($k) use ($totalVotes) {
            $k->persentase = $totalVotes > 0 ? ($k->votes_count / $totalVotes) * 100 : 0;
            return $k;
        });

        // Get pemenang (kandidat dengan suara terbanyak)
        $pemenang = $kandidat->first();

        $this->activityLogService->log('view', 'pemilihan', "Melihat hasil pemilihan: {$pemilihan->judul}");

        return view('modules.takmir.pemilihan.hasil', compact('pemilihan', 'kandidat', 'totalVotes', 'pemenang'));
    }

    /**
     * Create new pemilihan (admin only)
     */
    public function create()
    {
        $this->authorize('takmir.create');

        return view('modules.takmir.pemilihan.create');
    }

    /**
     * Store new pemilihan (admin only)
     */
    public function store(Request $request)
    {
        $this->authorize('takmir.create');

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:draft,aktif',
            'tampilkan_hasil' => 'boolean',
        ]);

        $pemilihan = Pemilihan::create($validated);

        $this->activityLogService->log(
            'create',
            'pemilihan',
            "Membuat pemilihan baru: {$pemilihan->judul}",
            ['pemilihan_id' => $pemilihan->id]
        );

        return redirect()->route('takmir.pemilihan.show', $pemilihan->id)
            ->with('success', 'Pemilihan berhasil dibuat. Silakan tambahkan kandidat.');
    }

    /**
     * Display pemilihan detail (admin only)
     */
    public function show($id)
    {
        $this->authorize('takmir.view');

        $pemilihan = Pemilihan::with(['kandidat.takmir', 'kandidat.votes'])
            ->withCount(['votes', 'kandidat'])
            ->findOrFail($id);

        // Get takmir aktif untuk dropdown kandidat (exclude yang sudah jadi kandidat)
        $existingKandidatTakmirIds = $pemilihan->kandidat->pluck('takmir_id')->toArray();
        $takmirAktif = \App\Models\Takmir::where('status', 'aktif')
            ->whereNotIn('id', $existingKandidatTakmirIds)
            ->get();

        $this->activityLogService->log('view', 'pemilihan', "Melihat detail pemilihan: {$pemilihan->judul}");

        return view('modules.takmir.pemilihan.show', compact('pemilihan', 'takmirAktif'));
    }

    /**
     * Edit pemilihan (admin only)
     */
    public function edit($id)
    {
        $this->authorize('takmir.update');

        $pemilihan = Pemilihan::withCount('votes')->findOrFail($id);

        $this->activityLogService->log('view', 'pemilihan', "Membuka form edit pemilihan: {$pemilihan->judul}");

        return view('modules.takmir.pemilihan.edit', compact('pemilihan'));
    }

    /**
     * Update pemilihan (admin only)
     */
    public function update(Request $request, $id)
    {
        $this->authorize('takmir.update');

        $pemilihan = Pemilihan::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:draft,aktif,selesai',
            'tampilkan_hasil' => 'boolean',
        ]);

        $pemilihan->update($validated);

        $this->activityLogService->log(
            'update',
            'pemilihan',
            "Mengupdate pemilihan: {$pemilihan->judul}",
            ['pemilihan_id' => $pemilihan->id]
        );

        return redirect()->route('takmir.pemilihan.show', $pemilihan->id)
            ->with('success', 'Pemilihan berhasil diupdate.');
    }

    /**
     * Delete pemilihan (admin only)
     */
    public function destroy($id)
    {
        $this->authorize('takmir.delete');

        $pemilihan = Pemilihan::findOrFail($id);
        $judul = $pemilihan->judul;

        $pemilihan->delete();

        $this->activityLogService->log(
            'delete',
            'pemilihan',
            "Menghapus pemilihan: {$judul}"
        );

        return redirect()->route('takmir.pemilihan.index')
            ->with('success', 'Pemilihan berhasil dihapus.');
    }

    /**
     * Store kandidat baru untuk pemilihan
     */
    public function storeKandidat(Request $request, $pemilihanId)
    {
        $this->authorize('takmir.create');

        $pemilihan = Pemilihan::findOrFail($pemilihanId);

        $validated = $request->validate([
            'takmir_id' => 'required|exists:takmir,id',
            'nomor_urut' => 'required|integer|min:1',
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);

        $validated['pemilihan_id'] = $pemilihan->id;

        $kandidat = Kandidat::create($validated);

        $this->activityLogService->log(
            'create',
            'kandidat',
            "Menambah kandidat: {$kandidat->takmir->nama} - Nomor {$kandidat->nomor_urut}",
            ['pemilihan_id' => $pemilihan->id, 'kandidat_id' => $kandidat->id]
        );

        return redirect()->route('takmir.pemilihan.show', $pemilihan->id)
            ->with('success', 'Kandidat berhasil ditambahkan.');
    }

    /**
     * Delete kandidat dari pemilihan
     */
    public function destroyKandidat($pemilihanId, $kandidatId)
    {
        $this->authorize('takmir.delete');

        $kandidat = Kandidat::where('pemilihan_id', $pemilihanId)
            ->findOrFail($kandidatId);

        $nama = $kandidat->takmir->nama;
        $kandidat->delete();

        $this->activityLogService->log(
            'delete',
            'kandidat',
            "Menghapus kandidat: {$nama}"
        );

        return redirect()->route('takmir.pemilihan.show', $pemilihanId)
            ->with('success', 'Kandidat berhasil dihapus.');
    }
}
