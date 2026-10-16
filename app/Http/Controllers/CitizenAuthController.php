<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Proses autentikasi login warga (email atau nomor HP)
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $attemptCredentials = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

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
            'login' => 'Nomor HP/Email atau kata sandi yang Anda masukkan salah.',
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
