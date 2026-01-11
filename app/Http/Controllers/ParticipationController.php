<?php

namespace App\Http\Controllers;

use App\Models\{Participation,JamaahProfile,Activity};
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','module.access:kegiatan']);
    }

    public function create($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $jamaahs = JamaahProfile::all();

        return view('modules.kegiatan.participation',compact('activity','jamaahs'));
    }

    public function store(Request $request, ActivityLogService $log)
    {
        foreach ($request->jamaah_ids as $id) {
            Participation::create([
                'jamaah_profile_id'=>$id,
                'activity_id'=>$request->activity_id,
                'status_hadir'=>true
            ]);
        }

        $log->logCrud('create','kegiatan','participation',$request->activity_id);

        return redirect()->route('kegiatan.index');
    }
}
