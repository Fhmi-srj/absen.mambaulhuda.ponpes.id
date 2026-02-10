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

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'user' => $user,
                'roleLabels' => $roleLabels,
            ]);
        }

        // For SPA, return the view
        return view('spa');
    }

    public function updateData(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        $user->save();

        if ($isAjax) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data profil berhasil diperbarui!',
                'user' => $user,
            ]);
        }

        return back()->with('success', 'Data profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($isAjax) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password saat ini salah!',
                ], 422);
            }
            return back()->with('error', 'Password saat ini salah!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($isAjax) {
            return response()->json([
                'status' => 'success',
                'message' => 'Password berhasil diperbarui!',
            ]);
        }

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function updateFoto(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();

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

        if ($isAjax) {
            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui!',
                'foto' => $filename,
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
