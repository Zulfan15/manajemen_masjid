<?php

namespace App\Http\Controllers;

use App\Models\Takmir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

class TakmirController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth', 'permission:takmir.view'])->only(['index', 'show']);
        $this->middleware(['auth', 'permission:takmir.create'])->only(['create', 'store']);
        $this->middleware(['auth', 'permission:takmir.update'])->only(['edit', 'update']);
        $this->middleware(['auth', 'permission:takmir.delete'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Takmir::query();

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jabatan
        if ($request->has('jabatan') && $request->jabatan !== '') {
            $query->where('jabatan', $request->jabatan);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $takmir = $query->orderBy('created_at', 'desc')->paginate(10);

        $this->activityLogService->log(
            'view',
            'takmir',
            'Melihat daftar takmir/pengurus'
        );

        return view('modules.takmir.index', compact('takmir'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar jamaah (user dengan role jamaah)
        $jamaahList = User::role('jamaah')->get();
        return view('modules.takmir.create', compact('jamaahList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|in:Ketua (DKM),Wakil Ketua,Sekretaris,Bendahara,Pengurus',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after:periode_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_akhir.required' => 'Periode akhir wajib diisi',
            'periode_akhir.after' => 'Periode akhir harus setelah periode mulai',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('takmir', 'public');
        }

        $takmir = Takmir::create($validated);

        $this->activityLogService->log(
            'create',
            'takmir',
            "Menambah data takmir/pengurus: {$takmir->nama}",
            ['takmir_id' => $takmir->id]
        );

        return redirect()->route('takmir.index')
            ->with('success', 'Data takmir/pengurus berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Takmir $takmir)
    {
        $this->activityLogService->log(
            'view',
            'takmir',
            "Melihat detail takmir/pengurus: {$takmir->nama}",
            ['takmir_id' => $takmir->id]
        );

        return view('modules.takmir.show', compact('takmir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Takmir $takmir)
    {
        // Ambil daftar jamaah (user dengan role jamaah)
        $jamaahList = User::role('jamaah')->get();
        return view('modules.takmir.edit', compact('takmir', 'jamaahList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Takmir $takmir)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|in:Ketua (DKM),Wakil Ketua,Sekretaris,Bendahara,Pengurus',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after:periode_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_akhir.required' => 'Periode akhir wajib diisi',
            'periode_akhir.after' => 'Periode akhir harus setelah periode mulai',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($takmir->foto) {
                Storage::disk('public')->delete($takmir->foto);
            }
            $validated['foto'] = $request->file('foto')->store('takmir', 'public');
        }

        $takmir->update($validated);

        $this->activityLogService->log(
            'update',
            'takmir',
            "Mengupdate data takmir/pengurus: {$takmir->nama}",
            ['takmir_id' => $takmir->id]
        );

        return redirect()->route('takmir.index')
            ->with('success', 'Data takmir/pengurus berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Takmir $takmir)
    {
        $nama = $takmir->nama;

        // Hapus foto jika ada
        if ($takmir->foto) {
            Storage::disk('public')->delete($takmir->foto);
        }

        $takmir->delete();

        $this->activityLogService->log(
            'delete',
            'takmir',
            "Menghapus data takmir/pengurus: {$nama}"
        );

        return redirect()->route('takmir.index')
            ->with('success', 'Data takmir/pengurus berhasil dihapus');
    }
}
