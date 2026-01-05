<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\TransaksiAset;
use App\Models\JadwalPerawatan;
use App\Models\KondisiBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class InventarisController extends Controller
{
    public function index()
    {
        $totalAset = Aset::count();
        $totalJadwalPerawatan = JadwalPerawatan::count();
        $totalPerluPerbaikan = KondisiBarang::where('kondisi', 'perlu_perbaikan')->count();

        $now = Carbon::now();
        $totalTransaksiBulanIni = TransaksiAset::whereMonth('tanggal_transaksi', $now->month)
            ->whereYear('tanggal_transaksi', $now->year)
            ->count();

        $asetPerKategori = Aset::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        $asetTerbaru = Aset::orderByDesc('aset_id')
            ->take(5)
            ->get();

        $aktivitasTerbaru = TransaksiAset::with('aset', 'petugas')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.inventaris.index', [
            'totalAset'              => $totalAset,
            'totalJadwalPerawatan'   => $totalJadwalPerawatan,
            'totalPerluPerbaikan'    => $totalPerluPerbaikan,
            'totalTransaksiBulanIni' => $totalTransaksiBulanIni,
            'asetPerKategori'        => $asetPerKategori,
            'asetTerbaru'            => $asetTerbaru,
            'aktivitasTerbaru'       => $aktivitasTerbaru,
        ]);
    }

    public function asetIndex(Request $request)
    {
        // Subquery: ambil kondisi terbaru per aset (pakai MAX(kondisi_id) sebagai yang paling baru)
        $latestKondisi = DB::table('kondisi_barang')
            ->select('aset_id', DB::raw('MAX(kondisi_id) as latest_kondisi_id'))
            ->groupBy('aset_id');

        $query = Aset::query()
            ->leftJoinSub($latestKondisi, 'lk', function ($join) {
                // pastikan nama tabel aset sesuai yang dipakai query
                $join->on('aset.aset_id', '=', 'lk.aset_id');
            })
            ->leftJoin('kondisi_barang as kb', 'kb.kondisi_id', '=', 'lk.latest_kondisi_id')
            ->select('aset.*', 'kb.kondisi as kondisi_terbaru');

        // Search
        if ($request->filled('search')) {
            $query->where('aset.nama_aset', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('aset.kategori', $request->kategori);
        }

        // ✅ Jalur A (tetap): Filter status
        if ($request->filled('status')) {
            $query->where('aset.status', $request->status);
        }

        // ✅ Jalur B: Filter kondisi terbaru
        if ($request->filled('kondisi')) {
            $query->where('kb.kondisi', $request->kondisi);
        }

        $assets = $query->orderByDesc('aset.aset_id')
            ->paginate(10)
            ->withQueryString();

        $kategoriOptions = Aset::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('modules.inventaris.aset.index', compact('assets', 'kategoriOptions'));
    }

    public function asetShow($id)
    {
        $asset = Aset::findOrFail($id);

        $kondisiTerbaru = KondisiBarang::where('aset_id', $asset->aset_id)
            ->orderByDesc('tanggal_pemeriksaan')
            ->first();

        $riwayatPerawatan = JadwalPerawatan::where('aset_id', $asset->aset_id)
            ->orderByDesc('tanggal_jadwal')
            ->get();

        $umurText = '-';
        if (!empty($asset->tanggal_perolehan)) {
            $start = Carbon::parse($asset->tanggal_perolehan);
            $diff  = $start->diff(Carbon::now());
            $parts = [];
            if ($diff->y > 0) $parts[] = $diff->y . ' Tahun';
            if ($diff->m > 0) $parts[] = $diff->m . ' Bulan';
            $umurText = count($parts) ? implode(' ', $parts) : '0 Bulan';
        }

        $qrCodeText = $asset->qr_payload ?: ('AST-' . str_pad((string)$asset->aset_id, 6, '0', STR_PAD_LEFT));

        return view('modules.inventaris.aset.show', compact(
            'asset',
            'kondisiTerbaru',
            'riwayatPerawatan',
            'umurText',
            'qrCodeText'
        ));
    }

    public function asetCreate()
    {
        $kategoriOptions = Aset::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->sort()
            ->values();

        $kondisiOptions = [
            'baik' => 'Layak',
            'perlu_perbaikan' => 'Perbaikan',
            'rusak' => 'Rusak',
        ];

        return view('modules.inventaris.aset.create', compact('kategoriOptions', 'kondisiOptions'));
    }

    public function petugasIndex(Request $request)
    {
        $query = User::query();

        // Search (nama/username/email) - mockup search by name, username, or role
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('username', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        if (method_exists(User::class, 'roles')) {
            $query->with('roles');
        }

        $petugas = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('modules.inventaris.petugas.index', compact('petugas'));
    }

    public function asetStore(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,rusak,hilang,dibuang'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $aset = Aset::create([
            'nama_aset' => $validated['nama_aset'],
            'kategori' => $validated['kategori'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? null,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // Generate QR payload (AST-000123) kalau belum ada
        $payload = 'AST-' . str_pad((string)$aset->aset_id, 6, '0', STR_PAD_LEFT);
        $aset->update(['qr_payload' => $payload]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('aset', 'public');
            $aset->update([
                'foto_path' => $path,
            ]);
        }

        return redirect()
            ->route('inventaris.aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function asetEdit($id)
    {
        $asset = Aset::findOrFail($id);

        $kategoriOptions = Aset::select('kategori')->distinct()->pluck('kategori')->filter()->sort()->values();

        return view('modules.inventaris.aset.edit', compact('asset', 'kategoriOptions'));
    }

    public function asetUpdate(Request $request, $id)
    {
        $asset = Aset::findOrFail($id);

        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,rusak,hilang,dibuang'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        if ($request->hasFile('foto')) {
            // hapus foto lama (opsional tapi rapi)
            if ($asset->foto_path && Storage::disk('public')->exists($asset->foto_path)) {
                Storage::disk('public')->delete($asset->foto_path);
            }

            $path = $request->file('foto')->store('aset', 'public');
            $asset->update([
                'foto_path' => $path,
            ]);
        }

        return redirect()->route('inventaris.aset.show', $asset->aset_id)
            ->with('success', 'Aset berhasil diupdate.');
    }

    public function asetDestroy($id)
    {
        $asset = Aset::findOrFail($id);
        $asset->delete();

        return redirect()->route('inventaris.aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }

    public function petugasCreate()
    {
        $roles = Role::orderBy('name')->get();
        return view('modules.inventaris.petugas.create', compact('roles'));
    }

    public function petugasStore(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
            'status'   => ['required', 'in:aktif,nonaktif'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            // status pakai locked_until (AMAN, tidak ubah DB)
            'locked_until' => $validated['status'] === 'nonaktif'
                ? Carbon::now()
                : null,
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('inventaris.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function petugasEdit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();

        return view('modules.inventaris.petugas.edit', compact('user', 'roles'));
    }

    public function petugasUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // DATA WAJIB
        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'locked_until' => $validated['status'] === 'nonaktif' ? now() : null,
        ];

        // ✅ PASSWORD HANYA DI-UPDATE JIKA DIISI
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // update role (kalau pakai spatie)
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()
            ->route('inventaris.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }


    public function petugasDestroy($id)
    {
        $user = User::findOrFail($id);

        // safety: jangan bisa hapus diri sendiri
        if (auth()->check() && auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        // optional: jangan hapus super_admin lain (kalau mau aman)
        // if ($user->hasRole('super_admin')) { ... }

        $user->delete();

        return redirect()
            ->route('inventaris.petugas.index')
            ->with('success', 'Petugas berhasil dihapus.');
    }

    public function petugasResetPassword($id)
    {
        $user = User::findOrFail($id);

        // safety: jangan reset diri sendiri (opsional)
        // if (auth()->check() && auth()->id() === $user->id) { ... }

        // generate password baru (ditampilkan sekali via flash message)
        $newPassword = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$'), 0, 10);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('inventaris.petugas.index')
            ->with('success', "Password berhasil di-reset. Password baru untuk {$user->username}: {$newPassword}");
    }
}
