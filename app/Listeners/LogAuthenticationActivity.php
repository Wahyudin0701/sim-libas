<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthenticationActivity
{
    /**
     * Cache deduplikasi untuk mencegah pencatatan ganda dalam siklus request yang sama.
     *
     * @var array<string, bool>
     */
    protected static array $loggedSessions = [];

    /**
     * Catat aktivitas ketika pengguna berhasil login.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        if (! $user instanceof User) {
            return;
        }

        // Mencegah pencatatan ganda jika event dipicu lebih dari satu kali dalam siklus request
        $sessionId = (request() && request()->hasSession()) ? request()->session()->getId() : 'nosess';
        $dedupKey = 'login_' . $user->id . '_' . $sessionId . '_' . now()->timestamp;
        if (isset(self::$loggedSessions[$dedupKey])) {
            return;
        }
        self::$loggedSessions[$dedupKey] = true;

        $request = request();
        $isAdmin = $request ? ($request->is('admin*') || $request->is('*/admin*')) : false;
        $portalName = $isAdmin ? 'Panel Pengurus SIM-LiBas' : 'Portal Layanan Warga';

        $role = $user->roles->first();
        $roleLabel = match ($role?->name) {
            'super_admin' => 'Super Admin',
            'ketua_rt' => 'Ketua RT',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'koordinator_bank_sampah' => 'Koor. Bank Sampah',
            'koordinator_keamanan' => 'Koor. Keamanan',
            'rw_kelurahan' => 'RW / Kelurahan',
            'warga' => 'Warga',
            default => $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'Pengguna',
        };

        $ip = $request ? $request->ip() : '127.0.0.1';
        $userAgent = $request ? ($request->userAgent() ?: 'Tidak diketahui') : 'CLI / System';

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->event('login')
            ->withProperties([
                'attributes' => [
                    'nama_pengguna' => $user->name,
                    'email' => $user->email,
                    'peran' => $roleLabel,
                    'portal' => $portalName,
                    'alamat_ip' => $ip,
                    'perangkat' => $userAgent,
                    'metode' => $event->remember ? 'Login (Ingat Saya)' : 'Login Standar',
                    'waktu_login' => now()->translatedFormat('d F Y, H:i:s') . ' WIB',
                ],
            ])
            ->log("Pengguna {$user->name} ({$roleLabel}) berhasil masuk ke {$portalName}");
    }

    /**
     * Catat aktivitas ketika pengguna logout dari sistem.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;
        if (! $user instanceof User) {
            return;
        }

        // Mencegah pencatatan ganda jika event dipicu lebih dari satu kali dalam siklus request
        $sessionId = (request() && request()->hasSession()) ? request()->session()->getId() : 'nosess';
        $dedupKey = 'logout_' . $user->id . '_' . $sessionId . '_' . now()->timestamp;
        if (isset(self::$loggedSessions[$dedupKey])) {
            return;
        }
        self::$loggedSessions[$dedupKey] = true;

        $request = request();
        $isAdmin = $request ? ($request->is('admin*') || $request->is('*/admin*')) : false;
        $portalName = $isAdmin ? 'Panel Pengurus SIM-LiBas' : 'Portal Layanan Warga';

        $role = $user->roles->first();
        $roleLabel = match ($role?->name) {
            'super_admin' => 'Super Admin',
            'ketua_rt' => 'Ketua RT',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'koordinator_bank_sampah' => 'Koor. Bank Sampah',
            'koordinator_keamanan' => 'Koor. Keamanan',
            'rw_kelurahan' => 'RW / Kelurahan',
            'warga' => 'Warga',
            default => $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'Pengguna',
        };

        $ip = $request ? $request->ip() : '127.0.0.1';
        $userAgent = $request ? ($request->userAgent() ?: 'Tidak diketahui') : 'CLI / System';

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->event('logout')
            ->withProperties([
                'attributes' => [
                    'nama_pengguna' => $user->name,
                    'email' => $user->email,
                    'peran' => $roleLabel,
                    'portal' => $portalName,
                    'alamat_ip' => $ip,
                    'perangkat' => $userAgent,
                    'waktu_keluar' => now()->translatedFormat('d F Y, H:i:s') . ' WIB',
                ],
            ])
            ->log("Pengguna {$user->name} ({$roleLabel}) keluar dari {$portalName}");
    }

}
