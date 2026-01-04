<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class JamaahVerificationController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth', 'permission:takmir.view']);
    }

    /**
     * Display list of jamaah for verification
     */
    public function index(Request $request)
    {
        $query = User::role('jamaah');

        // Filter berdasarkan status verifikasi
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $jamaahList = $query->with('verifier')
            ->orderBy('is_verified', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => User::role('jamaah')->count(),
            'verified' => User::role('jamaah')->where('is_verified', true)->count(),
            'unverified' => User::role('jamaah')->where('is_verified', false)->count(),
        ];

        $this->activityLogService->log('view', 'jamaah_verification', 'Melihat daftar verifikasi jamaah');

        return view('modules.takmir.verifikasi-jamaah', compact('jamaahList', 'stats'));
    }

    /**
     * Verify a jamaah member
     */
    public function verify(Request $request, User $user)
    {
        if (!$user->hasRole('jamaah')) {
            return redirect()->back()->with('error', 'User bukan jamaah');
        }

        if ($user->is_verified) {
            return redirect()->back()->with('info', 'User sudah terverifikasi');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $user->verify(auth()->id(), $request->notes);

        $this->activityLogService->log(
            'update',
            'jamaah_verification',
            "Memverifikasi jamaah: {$user->name}",
            ['user_id' => $user->id]
        );

        return redirect()->back()->with('success', "Jamaah {$user->name} berhasil diverifikasi");
    }

    /**
     * Unverify a jamaah member
     */
    public function unverify(User $user)
    {
        if (!$user->hasRole('jamaah')) {
            return redirect()->back()->with('error', 'User bukan jamaah');
        }

        if (!$user->is_verified) {
            return redirect()->back()->with('info', 'User belum terverifikasi');
        }

        $user->unverify();

        $this->activityLogService->log(
            'update',
            'jamaah_verification',
            "Membatalkan verifikasi jamaah: {$user->name}",
            ['user_id' => $user->id]
        );

        return redirect()->back()->with('success', "Verifikasi {$user->name} berhasil dibatalkan");
    }
}
