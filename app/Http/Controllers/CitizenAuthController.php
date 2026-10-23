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
     * Proses autentikasi login warga SIM-LiBas:
     * - Username: Nama lengkap warga sesuai KTP
     * - Password: NIK warga (16 digit angka)
     * Tetap mendukung pengurus yang mencoba login via portal warga untuk diredirect ke panel admin,
     * serta kompatibel dengan pengujian sistem yang sudah ada.
     */
    public function login(Request $request): RedirectResponse
    {
        // Normalisasi input jika menggunakan field alternatif 'login' / 'password'
        if (!$request->filled('nama') && $request->filled('login')) {
            $request->merge(['nama' => $request->input('login')]);
        }
        if (!$request->filled('nik') && $request->filled('password')) {
            $request->merge(['nik' => $request->input('password')]);
        }

        $credentials = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'string'],
        ], [
            'nama.required' => 'Nama lengkap sesuai KTP wajib diisi.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
        ]);

        $namaInput = trim($credentials['nama']);
        $nikInputRaw = trim($credentials['nik']);
        $remember = $request->boolean('remember');

        // 1. Dukungan Login via Email (Pengurus RT atau Warga via Email)
        if (filter_var($namaInput, FILTER_VALIDATE_EMAIL)) {
            $emailUser = User::where('email', $namaInput)->first();
            if ($emailUser && Hash::check($nikInputRaw, $emailUser->password)) {
                if ($emailUser->status !== 'aktif') {
                    $errorMsg = 'Akun Anda sedang dalam proses verifikasi pengurus RT atau telah dinonaktifkan.';
                    return back()->withErrors([
                        'nama' => $errorMsg,
                        'login' => $errorMsg,
                    ])->onlyInput('nama', 'login');
                }

                Auth::login($emailUser, $remember);
                $request->session()->regenerate();

                $adminPanel = Filament::getPanel('admin') ?? Filament::getDefaultPanel();
                if (!$emailUser->isWarga() && $emailUser->canAccessPanel($adminPanel)) {
                    $intended = session()->get('url.intended');
                    if ($intended && str_contains($intended, '/warga')) {
                        session()->forget('url.intended');
                    }
                    return redirect()->intended('/admin');
                }

                return redirect()->intended(route('warga.dashboard'));
            }
        }

        // 2. Dukungan Kompatibilitas jika input pertama berupa 16 digit NIK
        if (preg_match('/^\d{16}$/', $namaInput)) {
            $candidateHash = hash('sha256', $namaInput);
            $legacyResident = Resident::where('nik_hash', $candidateHash)->first();
            if ($legacyResident && $legacyResident->user_id) {
                $legacyUser = User::find($legacyResident->user_id);
                if ($legacyUser && (Hash::check($nikInputRaw, $legacyUser->password) || ($nikInputRaw === $namaInput && Hash::check($namaInput, $legacyUser->password)))) {
                    if ($legacyUser->status !== 'aktif') {
                        $errorMsg = 'Akun Anda sedang dalam proses verifikasi pengurus RT atau telah dinonaktifkan.';
                        return back()->withErrors([
                            'nama' => $errorMsg,
                            'login' => $errorMsg,
                        ])->onlyInput('nama', 'login');
                    }

                    Auth::login($legacyUser, $remember);
                    $request->session()->regenerate();
                    return redirect()->intended(route('warga.dashboard'));
                }
            }
        }

        // 3. Dukungan Kompatibilitas jika input pertama berupa Nomor Telepon
        if (preg_match('/^[0-9+\-\s]{8,20}$/', $namaInput)) {
            $phoneUser = User::where('phone', $namaInput)->first();
            if ($phoneUser && Hash::check($nikInputRaw, $phoneUser->password)) {
                if ($phoneUser->status !== 'aktif') {
                    $errorMsg = 'Akun Anda sedang dalam proses verifikasi pengurus RT atau telah dinonaktifkan.';
                    return back()->withErrors([
                        'nama' => $errorMsg,
                        'login' => $errorMsg,
                    ])->onlyInput('nama', 'login');
                }

                Auth::login($phoneUser, $remember);
                $request->session()->regenerate();
                return redirect()->intended(route('warga.dashboard'));
            }
        }

        // 4. Alur Utama: Warga Login dengan Nama Lengkap KTP + NIK 16 Digit
        $nikInput = preg_replace('/\D/', '', $nikInputRaw);
        if (strlen($nikInput) !== 16) {
            $errorMsg = 'Nomor Induk Kependudukan (NIK) harus berjumlah tepat 16 digit angka sesuai KTP.';
            return back()->withErrors([
                'nik' => $errorMsg,
                'login' => $errorMsg,
            ])->onlyInput('nama', 'login');
        }

        $nikHash = hash('sha256', $nikInput);

        // Cari Resident berdasarkan NIK Hash (Unik per individu)
        $resident = Resident::where('nik_hash', $nikHash)->first();

        if (!$resident) {
            $errorMsg = 'Nama lengkap atau NIK yang Anda masukkan tidak sesuai atau belum terdaftar.';
            return back()->withErrors([
                'nama' => $errorMsg,
                'login' => $errorMsg,
            ])->onlyInput('nama', 'login');
        }

        // Verifikasi Kecocokan Nama (Case-insensitive & Toleransi Gelar/Tanda Baca)
        if (!$this->isNameMatching($namaInput, (string) $resident->nama)) {
            $errorMsg = 'Nama lengkap tidak cocok dengan NIK yang dimasukkan. Pastikan ejaan sesuai KTP.';
            return back()->withErrors([
                'nama' => $errorMsg,
                'login' => $errorMsg,
            ])->onlyInput('nama', 'login');
        }

        // Resolusi Akun User Warga
        $user = null;
        if ($resident->user_id) {
            $user = User::find($resident->user_id);
        }

        // Jika resident belum memiliki user_id, buatkan akun otomatis
        if (!$user) {
            $syntheticEmail = !empty($resident->email) ? $resident->email : $nikInput . '@warga.simlibas.com';
            $user = User::firstOrCreate(
                ['email' => $syntheticEmail],
                [
                    'name' => $resident->nama,
                    'phone' => $resident->telepon ?: null,
                    'password' => Hash::make($nikInput),
                    'status' => $resident->status_kependudukan === 'aktif' ? 'aktif' : 'nonaktif',
                ]
            );

            if (!$user->hasRole('warga')) {
                $user->assignRole('warga');
            }

            $resident->update(['user_id' => $user->id]);
        }

        // Verifikasi Kredensial Password (Password NIK hash atau password legacy)
        $passwordValid = Hash::check($nikInput, $user->password);
        if (!$passwordValid && Hash::check('password', $user->password)) {
            // Self-heal: otomatis update ke hash NIK
            $user->update(['password' => Hash::make($nikInput)]);
            $passwordValid = true;
        }

        if (!$passwordValid) {
            $errorMsg = 'Nama lengkap atau NIK yang Anda masukkan tidak sesuai.';
            return back()->withErrors([
                'nama' => $errorMsg,
                'login' => $errorMsg,
            ])->onlyInput('nama', 'login');
        }

        // Cek Status Akun (Harus Aktif)
        if ($user->status !== 'aktif') {
            $errorMsg = 'Akun Anda sedang dalam proses verifikasi pengurus RT atau telah dinonaktifkan.';
            return back()->withErrors([
                'nama' => $errorMsg,
                'login' => $errorMsg,
            ])->onlyInput('nama', 'login');
        }

        // Sukses Autentikasi Warga
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Jika user adalah pengurus dan tidak punya role warga, arahkan ke admin panel
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

    /**
     * Membandingkan kecocokan nama warga dengan penanganan case-insensitive,
     * normalisasi spasi ganda, serta toleransi tanda baca/gelar.
     */
    protected function isNameMatching(string $inputName, string $residentName): bool
    {
        $cleanInput = preg_replace('/\s+/', ' ', trim($inputName));
        $cleanResident = preg_replace('/\s+/', ' ', trim($residentName));

        // 1. Pencocokan langsung (case-insensitive)
        if (strcasecmp($cleanInput, $cleanResident) === 0) {
            return true;
        }

        // 2. Normalisasi tanda baca dan gelar umum
        $normalizedInput = $this->stripTitlesAndPunctuation($cleanInput);
        $normalizedResident = $this->stripTitlesAndPunctuation($cleanResident);

        if ($normalizedInput === $normalizedResident) {
            return true;
        }

        // 3. Toleransi jika input adalah bagian utama nama (misal nama tanpa gelar)
        if (!empty($normalizedInput) && (str_starts_with($normalizedResident, $normalizedInput) || str_ends_with($normalizedResident, $normalizedInput))) {
            return true;
        }

        return false;
    }

    /**
     * Menghapus gelar dan tanda baca untuk perbandingan toleran bagi warga lansia
     */
    protected function stripTitlesAndPunctuation(string $name): string
    {
        $cleaned = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', ' ', $name);
        $cleaned = preg_replace('/\b(bpk|bapak|ibu|hj|haji|dra|dr|st|se|kom|spd|mpd|skep)\b/i', ' ', $cleaned);
        return preg_replace('/\s+/', ' ', trim(strtolower($cleaned)));
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
