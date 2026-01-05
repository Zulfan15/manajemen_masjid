<?php

namespace App\Http\Controllers;

use App\Models\ZISUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ZISUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth('zis')->check() || auth('zis')->user()->role !== 'admin') {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = ZISUser::latest()->paginate(10);
        return view('modules.zis.user.index', compact('users'));
    }

    public function create()
    {
        return view('modules.zis.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:zis_users',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        ZISUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('zis.user.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function destroy(ZISUser $user)
    {
        if (auth('zis')->user()->id == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('zis.user.index')->with('success', 'User berhasil dihapus!');
    }
}
