<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use App\Models\Attendance;
use App\Models\JadwalAbsen;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiManualController extends Controller
{
    public function index()
    {
        $siswaList = DataInduk::whereNull('deleted_at')
            ->orderBy('nama_lengkap')
            ->select('id', 'nama_lengkap', 'kelas')
            ->get();

        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        $recentAttendances = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->select('a.id', 'a.*', 'di.nama_lengkap', 'di.kelas', 'j.name as jadwal_name')
            ->orderBy('a.created_at', 'desc')
            ->limit(20)
            ->get();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'siswaList' => $siswaList,
                'jadwalList' => $jadwalList,
                'recentAttendances' => $recentAttendances,
            ]);
        }

        return view('spa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:data_induk,id',
            'jadwal_id' => 'required|exists:jadwal_absens,id',
            'attendance_date' => 'required|date',
            'attendance_time' => 'required',
            'status' => 'required|in:hadir,terlambat,izin,sakit,absen,pulang',
        ]);

        $existing = Attendance::where('user_id', $request->siswa_id)
            ->where('attendance_date', $request->attendance_date)
            ->where('jadwal_id', $request->jadwal_id)
            ->first();

        if ($existing) {
            $existing->update([
                'attendance_time' => $request->attendance_time,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);
        } else {
            Attendance::create([
                'user_id' => $request->siswa_id,
                'jadwal_id' => $request->jadwal_id,
                'attendance_date' => $request->attendance_date,
                'attendance_time' => $request->attendance_time,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);
        }

        $message = $existing ? 'Data absensi berhasil diperbarui!' : 'Data absensi berhasil ditambahkan!';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->deleted_at = now();
        $attendance->deleted_by = Auth::id();
        $attendance->save();

        ActivityLog::log(
            action: 'DELETE',
            tableName: 'attendances',
            recordId: $attendance->id,
            recordName: "Absensi santri ID: {$attendance->user_id}",
            description: 'Hapus absensi ke trash'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data absensi dipindahkan ke trash!'
            ]);
        }

        return back()->with('success', 'Data absensi dipindahkan ke trash!');
    }
}
