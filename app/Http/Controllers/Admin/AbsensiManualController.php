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
            ->orderBy('start_time')
            ->get();

        $recentAttendances = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->select('a.*', 'di.nama_lengkap', 'di.kelas', 'j.name as jadwal_name')
            ->orderBy('a.created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.absensi-manual', compact('siswaList', 'jadwalList', 'recentAttendances'));
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
            return back()->with('success', 'Data absensi berhasil diperbarui!');
        }

        Attendance::create([
            'user_id' => $request->siswa_id,
            'jadwal_id' => $request->jadwal_id,
            'attendance_date' => $request->attendance_date,
            'attendance_time' => $request->attendance_time,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Data absensi berhasil ditambahkan!');
    }

    public function destroy(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->deleted_at = now();
        $attendance->deleted_by = Auth::id();
        $attendance->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'delete',
            'module' => 'attendances',
            'description' => 'Hapus absensi ke trash',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Data absensi dipindahkan ke trash!');
    }
}
