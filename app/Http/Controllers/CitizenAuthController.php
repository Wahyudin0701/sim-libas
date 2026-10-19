<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CitizenAuthController extends Controller
{
    /**
     * Tampilkan formulir login warga SIM-LiBas
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('warga.dashboard');
        }

        return view('auth.citizen-login');
    }

    /**
     * Proses autentikasi login warga (NIK, email, atau nomor HP)
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($credentials['login']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        $user = null;

        // 1. Cek jika input adalah NIK 16 digit angka
        if (preg_match('/^\d{16}$/', $loginInput)) {
            $nikHash = hash('sha256', $loginInput);
            $resident = Resident::where('nik_hash', $nikHash)->first();
            if ($resident && $resident->user_id) {
                $user = User::find($resident->user_id);
            }
        }

        // 2. Jika bukan NIK atau belum ketemu, cek via email
        if (!$user && filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        }

        // 3. Jika belum ketemu, cek via nomor telepon
        if (!$user) {
            $user = User::where('phone', $loginInput)->first();
        }

        // 4. Verifikasi kredensial dan login
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            if ($user->status !== 'aktif') {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Akun Anda sedang dalam proses verifikasi pengurus RT atau telah dinonaktifkan.',
                ]);
            }

            // Jika user adalah pengurus atau admin dan tidak punya role warga, arahkan ke admin panel
            $adminPanel = Filament::getPanel('admin') ?? Filament::getDefaultPanel();
            if (!$user->isWarga() && $user->canAccessPanel($adminPanel)) {
                $intended = session()->get('url.intended');
                if ($intended && str_contains($intended, '/warga')) {
                    session()->forget('url.intended');
                }
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('warga.dashboard'));
        }

        return back()->withErrors([
            'login' => 'NIK, Nomor HP, Email, atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    /**
     * Logout warga dari sesi portal
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.home')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}
