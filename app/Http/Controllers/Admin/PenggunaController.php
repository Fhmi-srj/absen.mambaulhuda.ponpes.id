<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $filterRole = $request->get('role', '');

        $query = User::whereNull('deleted_at')
            ->selectRaw('users.*, (SELECT COUNT(*) FROM user_devices WHERE user_devices.user_id = users.id) as device_count')
            ->orderBy('name', 'asc');

        if ($filterRole) {
            $query->where('role', $filterRole);
        }

        $users = $query->get();

        $roles = ['admin', 'karyawan', 'pengurus', 'guru', 'keamanan', 'kesehatan'];
        $roleLabels = [
            'admin' => 'Administrator',
            'karyawan' => 'Karyawan',
            'pengurus' => 'Pengurus',
            'guru' => 'Guru',
            'keamanan' => 'Keamanan',
            'kesehatan' => 'Kesehatan'
        ];

        return view('admin.pengguna', compact('users', 'roles', 'roleLabels', 'filterRole'));
    }
}
