<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\RememberToken;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('beranda');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_token' => 'nullable',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($isAjax) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email atau password salah.'
                ], 401);
            }
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        // Check device fingerprint - update on each login (matching PHP project behavior)
        $deviceToken = $request->device_token ?: 'spa-' . md5($request->userAgent());
        $userDevice = UserDevice::where('user_id', $user->id)->first();

        if (!$userDevice) {
            // First device registration
            UserDevice::create([
                'user_id' => $user->id,
                'device_fingerprint' => $deviceToken,
                'device_name' => $request->userAgent(),
                'last_used_at' => now(),
            ]);
        } else {
            // Update device fingerprint and last used time
            $userDevice->update([
                'device_fingerprint' => $deviceToken,
                'device_name' => $request->userAgent(),
                'last_used_at' => now(),
            ]);
        }

        // Login the user
        Auth::login($user, $request->has('remember_me'));

        // Log activity
        ActivityLog::log(
            'LOGIN',
            'users',
            $user->id,
            $user->name,
            null,
            null,
            'Pengguna berhasil masuk'
        );

        // Generate remember token if checkbox is checked
        if ($request->has('remember_me')) {
            $this->generateRememberToken($user->id);
        }

        $request->session()->regenerate();

        if ($isAjax) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user' => $user,
            ]);
        }

        return redirect()->intended(route('beranda'))->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();
        $user = Auth::user();

        if ($user) {
            ActivityLog::log(
                'LOGOUT',
                'users',
                $user->id,
                $user->name,
                null,
                null,
                'Pengguna keluar dari sistem'
            );

            // Clear remember token
            RememberToken::where('user_id', $user->id)->delete();
        }

        // Clear remember me cookies
        if ($request->hasCookie('remember_token')) {
            cookie()->forget('remember_token');
            cookie()->forget('remember_user');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAjax) {
            return response()->json(['status' => 'success']);
        }

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    protected function generateRememberToken(int $userId): void
    {
        $token = Str::random(64);
        $tokenHash = hash('sha256', $token);
        $expiresAt = now()->addDays(30);

        // Delete existing tokens
        RememberToken::where('user_id', $userId)->delete();

        // Create new token
        RememberToken::create([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);

        // Set cookies
        $cookieExpiry = 60 * 24 * 30; // 30 days in minutes
        cookie()->queue('remember_token', $token, $cookieExpiry);
        cookie()->queue('remember_user', $userId, $cookieExpiry);
    }
}
