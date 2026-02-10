<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $filterRole = $request->get('role', '');
        $filterAction = $request->get('action_type', '');
        $filterDateFrom = $request->get('date_from', '');
        $filterDateTo = $request->get('date_to', '');

        $page = max(1, intval($request->get('page', 1)));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $query = DB::table('activity_logs as a')
            ->leftJoin('users as u', 'a.user_id', '=', 'u.id')
            ->select('a.*', 'u.role as user_role', 'u.name as username');

        if ($filterRole) {
            $query->whereIn('a.user_id', function ($q) use ($filterRole) {
                $q->select('id')->from('users')->where('role', $filterRole);
            });
        }
        if ($filterAction) {
            $query->where('a.action', $filterAction);
        }
        if ($filterDateFrom) {
            $query->whereDate('a.created_at', '>=', $filterDateFrom);
        }
        if ($filterDateTo) {
            $query->whereDate('a.created_at', '<=', $filterDateTo);
        }

        $total = $query->count();
        $totalPages = ceil($total / $limit);

        $logs = $query->orderBy('a.created_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $roles = DB::table('activity_logs as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->whereNotNull('u.role')
            ->distinct()
            ->pluck('u.role');

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'logs' => $logs,
                'roles' => $roles,
                'total' => $total,
                'totalPages' => $totalPages,
                'page' => $page,
                'offset' => $offset,
            ]);
        }

        return view('spa');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            ActivityLog::whereIn('id', $ids)->delete();
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['status' => 'success', 'message' => count($ids) . ' log berhasil dihapus!']);
            }
            return back()->with('success', count($ids) . ' log berhasil dihapus!');
        }
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dipilih'], 400);
        }
        return back()->with('error', 'Tidak ada data yang dipilih');
    }

    public function deleteSingle(Request $request, $id)
    {
        ActivityLog::where('id', $id)->delete();
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Log berhasil dihapus!']);
        }
        return back()->with('success', 'Log berhasil dihapus!');
    }

    public function clearAll()
    {
        DB::table('activity_logs')->truncate();
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Semua log berhasil dihapus!']);
        }
        return back()->with('success', 'Semua log berhasil dihapus!');
    }
}
