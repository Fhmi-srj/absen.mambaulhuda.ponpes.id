<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrashController extends Controller
{
    private $allowedTables = ['data_induk', 'catatan_aktivitas', 'attendances', 'users', 'jadwal_absens'];

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'santri');

        $trashData = [];

        $trashData['santri'] = DB::table('data_induk')
            ->whereNotNull('deleted_at')
            ->orderBy('deleted_at', 'desc')
            ->select('id', 'nama_lengkap', 'nisn', 'kelas', 'deleted_at', 'deleted_by')
            ->get();

        $trashData['aktivitas'] = DB::table('catatan_aktivitas as ca')
            ->leftJoin('data_induk as di', 'ca.siswa_id', '=', 'di.id')
            ->whereNotNull('ca.deleted_at')
            ->orderBy('ca.deleted_at', 'desc')
            ->select('ca.id', 'di.nama_lengkap', 'ca.kategori', 'ca.judul', 'ca.deleted_at', 'ca.deleted_by')
            ->get();

        $trashData['absensi'] = DB::table('attendances as a')
            ->leftJoin('data_induk as di', 'a.user_id', '=', 'di.id')
            ->whereNotNull('a.deleted_at')
            ->orderBy('a.deleted_at', 'desc')
            ->select('a.id', 'di.nama_lengkap', 'a.status', 'a.attendance_time', 'a.deleted_at', 'a.deleted_by')
            ->get();

        $trashData['users'] = DB::table('users')
            ->whereNotNull('deleted_at')
            ->orderBy('deleted_at', 'desc')
            ->select('id', 'name', 'email', 'role', 'deleted_at', 'deleted_by')
            ->get();

        $deleters = DB::table('users')->pluck('name', 'id')->toArray();

        // Settings
        $autoPurgeEnabled = $this->getSetting('auto_purge_enabled', '0') === '1';
        $autoPurgeDays = $this->getSetting('auto_purge_days', '30');

        return view('admin.trash', compact('activeTab', 'trashData', 'deleters', 'autoPurgeEnabled', 'autoPurgeDays'));
    }

    public function restore(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        if (!in_array($table, $this->allowedTables)) {
            return back()->with('error', 'Tabel tidak valid!');
        }

        DB::table($table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'restore',
            'module' => $table,
            'description' => 'Restored from trash',
        ]);

        return back()->with('success', 'Data berhasil di-restore!');
    }

    public function permanentDelete(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        if (!in_array($table, $this->allowedTables)) {
            return back()->with('error', 'Tabel tidak valid!');
        }

        DB::table($table)->where('id', $id)->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'permanent_delete',
            'module' => $table,
            'description' => 'Permanently deleted',
        ]);

        return back()->with('success', 'Data berhasil dihapus permanen!');
    }

    public function bulkRestore(Request $request)
    {
        $table = $request->table;
        $ids = $request->input('ids', []);

        if (!in_array($table, $this->allowedTables)) {
            return back()->with('error', 'Tabel tidak valid!');
        }

        $restored = DB::table($table)->whereIn('id', $ids)->update(['deleted_at' => null, 'deleted_by' => null]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'bulk_restore',
            'module' => $table,
            'description' => "Restored $restored records",
        ]);

        return back()->with('success', "$restored data berhasil di-restore!");
    }

    public function bulkDelete(Request $request)
    {
        $table = $request->table;
        $ids = $request->input('ids', []);

        if (!in_array($table, $this->allowedTables)) {
            return back()->with('error', 'Tabel tidak valid!');
        }

        $deleted = DB::table($table)->whereIn('id', $ids)->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'bulk_permanent_delete',
            'module' => $table,
            'description' => "Permanently deleted $deleted records",
        ]);

        return back()->with('success', "$deleted data berhasil dihapus permanen!");
    }

    public function emptyTrash(Request $request)
    {
        $counts = 0;
        foreach ($this->allowedTables as $table) {
            try {
                $counts += DB::table($table)->whereNotNull('deleted_at')->delete();
            } catch (\Exception $e) {
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'empty_trash',
            'module' => 'trash',
            'description' => "Dikosongkan trash: $counts data dihapus permanen",
        ]);

        return back()->with('success', "Trash dikosongkan! $counts data dihapus permanen.");
    }

    public function saveSettings(Request $request)
    {
        $this->setSetting('auto_purge_enabled', $request->has('auto_purge_enabled') ? '1' : '0');
        $this->setSetting('auto_purge_days', intval($request->auto_purge_days));
        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    private function getSetting($key, $default = null)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    private function setSetting($key, $value)
    {
        DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
    }
}
