<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleLabels = [
            'admin' => 'Administrator',
            'karyawan' => 'Karyawan',
            'pengurus' => 'Pengurus',
            'guru' => 'Guru',
            'keamanan' => 'Keamanan',
            'kesehatan' => 'Kesehatan'
        ];

        return view('user.profil', compact('user', 'roleLabels'));
    }

    public function updateData(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return back()->with('success', 'Data profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->foto && $user->foto !== 'profile.jpg') {
            $oldPath = public_path('uploads/profiles/' . $user->foto);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Upload new photo
        $file = $request->file('foto');
        $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $uploadPath = public_path('uploads/profiles');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $filename);

        $user->foto = $filename;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
