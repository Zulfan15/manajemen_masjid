<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','module.access:kegiatan']);
    }

    public function index()
    {
        $activities = Activity::orderBy('tanggal','desc')->get();
        return view('modules.kegiatan.index',compact('activities'));
    }

    public function create()
    {
        return view('modules.kegiatan.create');
    }

    public function store(Request $request, ActivityLogService $log)
    {
        $request->validate([
            'nama_kegiatan'=>'required',
            'tanggal'=>'required|date'
        ]);

        $activity = Activity::create($request->all());

        $log->logCrud('create','kegiatan','activities',$activity->id);

        return redirect()->route('kegiatan.index');
    }
}
