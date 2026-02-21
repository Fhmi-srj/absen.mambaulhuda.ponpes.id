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
            $disableDailyReset = $request->has('disable_daily_reset') ? (bool)$request->disable_daily_reset : false;

            $rules = ['name' => 'required|string|max:255'];
            if ($disableDailyReset) {
                $rules['no_reset_start_date'] = 'required|date';
                $rules['no_reset_end_date'] = 'nullable|date|after_or_equal:no_reset_start_date';
            } else {
                $rules['start_time'] = 'required';
                $rules['scheduled_time'] = 'required';
                $rules['end_time'] = 'required';
            }
            $request->validate($rules);

            $jadwal = JadwalAbsen::create([
                'name' => $request->name,
                'type' => $request->type ?? 'absen',
                'start_time' => $disableDailyReset ? null : $request->start_time,
                'scheduled_time' => $disableDailyReset ? null : $request->scheduled_time,
                'end_time' => $disableDailyReset ? null : $request->end_time,
                'late_tolerance_minutes' => $disableDailyReset ? null : ($request->late_tolerance_minutes ?? 15),
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'disable_daily_reset' => $disableDailyReset,
                'no_reset_start_date' => $disableDailyReset ? $request->no_reset_start_date : null,
                'no_reset_end_date' => $disableDailyReset ? $request->no_reset_end_date : null,
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

            $disableDailyReset = $request->has('disable_daily_reset') ? (bool)$request->disable_daily_reset : false;

            $jadwal->update([
                'name' => $request->name,
                'type' => $request->type ?? 'absen',
                'start_time' => $disableDailyReset ? null : $request->start_time,
                'scheduled_time' => $disableDailyReset ? null : $request->scheduled_time,
                'end_time' => $disableDailyReset ? null : $request->end_time,
                'late_tolerance_minutes' => $disableDailyReset ? null : ($request->late_tolerance_minutes ?? 15),
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'disable_daily_reset' => $disableDailyReset,
                'no_reset_start_date' => $disableDailyReset ? $request->no_reset_start_date : null,
                'no_reset_end_date' => $disableDailyReset ? $request->no_reset_end_date : null,
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
