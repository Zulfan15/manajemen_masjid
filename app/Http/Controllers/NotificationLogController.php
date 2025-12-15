<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    public function index()
    {
        return NotificationLog::with(['notification', 'jamaah'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'notification_id' => 'required|exists:notifications,id',
            'jamaah_id' => 'required|exists:jamaah,id',
            'status' => 'required|in:sent,failed',
            'response_message' => 'nullable',
            'sent_at' => 'nullable|date'
        ]);

        return NotificationLog::create($data);
    }

    public function show($id)
    {
        return NotificationLog::with(['notification','jamaah'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $log = NotificationLog::findOrFail($id);
        $log->update($request->all());
        return $log;
    }

    public function destroy($id)
    {
        return NotificationLog::destroy($id);
    }
}
