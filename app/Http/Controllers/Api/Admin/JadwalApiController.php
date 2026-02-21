<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalAbsen;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalApiController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'start_time' => 'required',
                'scheduled_time' => 'required',
                'end_time' => 'required',
            ]);

            $jadwal = JadwalAbsen::create([
                'name' => $request->name,
                'type' => $request->type ?? 'absen',
                'start_time' => $request->start_time,
                'scheduled_time' => $request->scheduled_time,
                'end_time' => $request->end_time,
                'late_tolerance_minutes' => $request->late_tolerance_minutes ?? 15,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'disable_daily_reset' => $request->has('disable_daily_reset') ? (bool)$request->disable_daily_reset : false,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'create',
                'module' => 'jadwal_absens',
                'description' => "Menambahkan jadwal: {$jadwal->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jadwal = JadwalAbsen::findOrFail($id);

            $jadwal->update([
                'name' => $request->name,
                'type' => $request->type ?? 'absen',
                'start_time' => $request->start_time,
                'scheduled_time' => $request->scheduled_time,
                'end_time' => $request->end_time,
                'late_tolerance_minutes' => $request->late_tolerance_minutes ?? 15,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'disable_daily_reset' => $request->has('disable_daily_reset') ? (bool)$request->disable_daily_reset : false,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'update',
                'module' => 'jadwal_absens',
                'description' => "Mengubah jadwal: {$jadwal->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $jadwal = JadwalAbsen::findOrFail($id);
            $jadwal->deleted_at = now();
            $jadwal->deleted_by = Auth::id();
            $jadwal->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'delete',
                'module' => 'jadwal_absens',
                'description' => "Menghapus jadwal: {$jadwal->name} ke trash",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal dipindahkan ke trash!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
}
