<?php

namespace App\Http\Controllers;

use App\Models\{JamaahProfile, JamaahCategory, Donation, User, Kegiatan, KegiatanPeserta};
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JamaahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'module.access:jamaah']);
    }

    /**
     * ===============================
     * LIST JAMAAH
     * ===============================
     */
    public function index(Request $request)
    {
        $query = JamaahProfile::with([
            'categories',
            'user',
            'donations',
            'kegiatanPeserta'
        ]);

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('jamaah_categories.id', $request->kategori);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'aktif');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $jamaahs = $query->latest()->get();

        // Statistik
        $totalJamaah = JamaahProfile::count();

        $jamaahBaruBulanIni = JamaahProfile::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalRelawan = JamaahProfile::whereHas('categories', function ($q) {
            $q->where('nama', 'Pengurus');
        })->count();

        $jamaahAktif = JamaahProfile::where(function ($q) {
            $q->whereHas('donations')
                ->orWhereHas('kegiatanPeserta');
        })->count();

        $tingkatPartisipasi = $totalJamaah > 0
            ? round(($jamaahAktif / $totalJamaah) * 100, 1)
            : 0;

        $categories = JamaahCategory::all();

        return view(
            'modules.jamaah.index',
            compact(
                'jamaahs',
                'totalJamaah',
                'jamaahBaruBulanIni',
                'totalRelawan',
                'tingkatPartisipasi',
                'categories'
            )
        );
    }

    /**
     * ===============================
     * FORM CREATE JAMAAH
     * ===============================
     */
    public function create()
    {
        $categories = JamaahCategory::all();
        return view('modules.jamaah.create-jamaah', compact('categories'));
    }

    /**
     * ===============================
     * STORE JAMAAH
     * ===============================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string|min:6',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pernikahan' => 'nullable|in:belum_menikah,menikah,duda,janda',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:jamaah_categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Buat user jika ada email/username
            $userId = null;
            if ($request->filled('email') || $request->filled('username')) {
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'] ?? null,
                    'username' => $validated['username'] ?? strtolower(str_replace(' ', '', $validated['nama_lengkap'])) . rand(100, 999),
                    'password' => Hash::make($validated['password'] ?? 'password123'),
                ]);
                $userId = $user->id;
                $user->assignRole('user');
            }

            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('jamaah-photos', 'public');
            }

            // Buat jamaah profile
            $jamaah = JamaahProfile::create([
                'user_id' => $userId,
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
                'status_pernikahan' => $validated['status_pernikahan'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'rt' => $validated['rt'] ?? null,
                'rw' => $validated['rw'] ?? null,
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'foto' => $fotoPath,
                'status_aktif' => true,
            ]);

            // Attach categories
            if (!empty($validated['categories'])) {
                $jamaah->categories()->attach($validated['categories']);
            } else {
                // Default: Umum
                $umumCategory = JamaahCategory::where('nama', 'Umum')->first();
                if ($umumCategory) {
                    $jamaah->categories()->attach($umumCategory->id);
                }
            }

            DB::commit();

            return redirect()
                ->route('jamaah.index')
                ->with('success', 'Data jamaah berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * ===============================
     * DETAIL JAMAAH
     * ===============================
     */
    public function show(JamaahProfile $jamaah)
    {
        $jamaah->load([
            'categories',
            'donations' => function ($q) {
                $q->latest()->limit(10);
            },
            'kegiatanPeserta.kegiatan'
        ]);

        // Get kegiatan yang diikuti
        $kegiatanDiikuti = KegiatanPeserta::where('user_id', $jamaah->user_id)
            ->with('kegiatan')
            ->latest()
            ->get();

        return view('modules.jamaah.show', compact('jamaah', 'kegiatanDiikuti'));
    }

    /**
     * ===============================
     * FORM EDIT JAMAAH
     * ===============================
     */
    public function edit(JamaahProfile $jamaah)
    {
        $categories = JamaahCategory::all();
        return view('modules.jamaah.edit', compact('jamaah', 'categories'));
    }

    /**
     * ===============================
     * UPDATE JAMAAH
     * ===============================
     */
    public function update(Request $request, JamaahProfile $jamaah)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pernikahan' => 'nullable|in:belum_menikah,menikah,duda,janda',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'status_aktif' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:jamaah_categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($jamaah->foto) {
                    Storage::disk('public')->delete($jamaah->foto);
                }
                $validated['foto'] = $request->file('foto')->store('jamaah-photos', 'public');
            }

            $validated['status_aktif'] = $request->has('status_aktif');

            $jamaah->update($validated);

            // Sync categories
            if (isset($validated['categories'])) {
                $jamaah->categories()->sync($validated['categories']);
            }

            DB::commit();

            return redirect()
                ->route('jamaah.show', $jamaah)
                ->with('success', 'Data jamaah berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * ===============================
     * DELETE JAMAAH
     * ===============================
     */
    public function destroy(JamaahProfile $jamaah)
    {
        DB::beginTransaction();
        try {
            // Hapus foto jika ada
            if ($jamaah->foto) {
                Storage::disk('public')->delete($jamaah->foto);
            }

            $jamaah->categories()->detach();
            $jamaah->delete();

            DB::commit();

            return redirect()
                ->route('jamaah.index')
                ->with('success', 'Data jamaah berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * ===============================
     * FORM UBAH KATEGORI / ROLE JAMAAH
     * ===============================
     */
    public function editRole(JamaahProfile $jamaah)
    {
        $categories = JamaahCategory::all();
        return view('modules.jamaah.edit-role', compact('jamaah', 'categories'));
    }

    /**
     * ===============================
     * SIMPAN PERUBAHAN ROLE / KATEGORI
     * ===============================
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'main_role' => 'required|in:umum,pengurus',
            'donatur' => 'nullable|boolean',
        ]);

        $jamaah = JamaahProfile::findOrFail($id);

        $mainCategoryName = $request->main_role === 'umum' ? 'Umum' : 'Pengurus';

        $mainCategory = JamaahCategory::where('nama', $mainCategoryName)->firstOrFail();
        $donaturCategory = JamaahCategory::where('nama', 'Donatur')->firstOrFail();

        $umumId = JamaahCategory::where('nama', 'Umum')->value('id');
        $pengurusId = JamaahCategory::where('nama', 'Pengurus')->value('id');

        // Hapus kategori utama lama
        $jamaah->categories()->detach([$umumId, $pengurusId]);

        // Pasang kategori utama baru
        $jamaah->categories()->attach($mainCategory->id);

        // Donatur opsional
        if ($request->boolean('donatur')) {
            $jamaah->categories()->syncWithoutDetaching([$donaturCategory->id]);
        } else {
            $jamaah->categories()->detach($donaturCategory->id);
        }

        return redirect()
            ->route('jamaah.index')
            ->with('success', 'Kategori jamaah berhasil diperbarui');
    }

    /**
     * ===============================
     * TAMBAH DONASI JAMAAH
     * ===============================
     */
    public function createDonation(JamaahProfile $jamaah)
    {
        $types = Donation::getTypes();
        return view('modules.jamaah.donations.create', compact('jamaah', 'types'));
    }

    /**
     * ===============================
     * SIMPAN DONASI JAMAAH
     * ===============================
     */
    public function storeDonation(Request $request, JamaahProfile $jamaah)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:infak,sedekah,zakat,wakaf,lainnya',
            'donation_date' => 'required|date',
            'keterangan' => 'nullable|string',
            'metode_pembayaran' => 'nullable|string|max:50',
            'status' => 'required|in:pending,confirmed,cancelled',
            'bukti_transfer' => 'nullable|image|max:2048',
        ]);

        // Upload bukti transfer jika ada
        if ($request->hasFile('bukti_transfer')) {
            $validated['bukti_transfer'] = $request->file('bukti_transfer')->store('donation-proofs', 'public');
        }

        $validated['jamaah_profile_id'] = $jamaah->id;

        Donation::create($validated);

        // Update kategori menjadi Donatur jika donasi dikonfirmasi
        if ($validated['status'] === 'confirmed') {
            $donaturCategory = JamaahCategory::where('nama', 'Donatur')->first();
            if ($donaturCategory) {
                $jamaah->categories()->syncWithoutDetaching([$donaturCategory->id]);
            }
        }

        return redirect()
            ->route('jamaah.show', $jamaah)
            ->with('success', 'Donasi berhasil ditambahkan');
    }

    /**
     * ===============================
     * LIST SEMUA DONASI
     * ===============================
     */
    public function donations(Request $request)
    {
        $query = Donation::with('jamaah');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('donation_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('donation_date', '<=', $request->to_date);
        }

        $donations = $query->latest('donation_date')->paginate(20);
        $types = Donation::getTypes();
        $statuses = Donation::getStatuses();

        // Statistik
        $totalDonasi = Donation::confirmed()->sum('amount');
        $donasiPending = Donation::pending()->count();

        return view('modules.jamaah.donations.index', compact(
            'donations',
            'types',
            'statuses',
            'totalDonasi',
            'donasiPending'
        ));
    }

    /**
     * ===============================
     * UPDATE STATUS DONASI
     * ===============================
     */
    public function updateDonationStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $donation->update($validated);

        // Jika confirmed, tambahkan kategori Donatur
        if ($validated['status'] === 'confirmed' && $donation->jamaah) {
            $donaturCategory = JamaahCategory::where('nama', 'Donatur')->first();
            if ($donaturCategory) {
                $donation->jamaah->categories()->syncWithoutDetaching([$donaturCategory->id]);
            }
        }

        return back()->with('success', 'Status donasi berhasil diperbarui');
    }

    /**
     * ===============================
     * HAPUS DONASI
     * ===============================
     */
    public function destroyDonation(Donation $donation)
    {
        if ($donation->bukti_transfer) {
            Storage::disk('public')->delete($donation->bukti_transfer);
        }

        $jamaahId = $donation->jamaah_profile_id;
        $donation->delete();

        return redirect()
            ->route('jamaah.show', $jamaahId)
            ->with('success', 'Donasi berhasil dihapus');
    }

    /**
     * ===============================
     * EXPORT DATA JAMAAH
     * ===============================
     */
    public function export(Request $request)
    {
        $jamaahs = JamaahProfile::with('categories', 'donations')->get();

        // Simple CSV export
        $filename = 'data-jamaah-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($jamaahs) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'ID',
                'Nama Lengkap',
                'No HP',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Alamat',
                'Kategori',
                'Total Donasi',
                'Status'
            ]);

            // Data
            foreach ($jamaahs as $j) {
                fputcsv($file, [
                    $j->id,
                    $j->nama_lengkap,
                    $j->no_hp,
                    $j->jenis_kelamin_label ?? '-',
                    $j->tanggal_lahir ? $j->tanggal_lahir->format('d/m/Y') : '-',
                    $j->alamat_lengkap,
                    $j->categories->pluck('nama')->implode(', '),
                    $j->total_donasi,
                    $j->status_aktif ? 'Aktif' : 'Non-Aktif',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
