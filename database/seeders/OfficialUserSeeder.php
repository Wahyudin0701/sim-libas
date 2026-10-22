<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OfficialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi akun pejabat dan pengurus utama RT 15 SIM-LiBas
     * dengan kredensial aman yang bersumber dari konfigurasi environment (.env).
     */
    public function run(): void
    {
        $accounts = config('seeder.accounts', []);

        foreach ($accounts as $roleKey => $accConfig) {
            $email = $accConfig['email'];
            $name = $accConfig['name'];
            $phone = $accConfig['phone'] ?? null;
            $roles = $accConfig['roles'] ?? [];

            $resolvedPassword = $this->resolvePassword($roleKey, $accConfig['password'] ?? null);

            // Gunakan firstOrCreate agar idempoten dan tidak menimpa password yang sudah diubah di production
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'password' => Hash::make($resolvedPassword),
                    'status' => 'aktif',
                ]
            );

            // Pastikan data profil dan role ter-sinkronisasi
            if ($user->wasRecentlyCreated) {
                $this->command?->info("✓ Akun pengurus [{$email}] berhasil diinisialisasi.");
            } else {
                $user->update([
                    'name' => $name,
                    'phone' => $phone,
                    'status' => 'aktif',
                ]);
            }

            if (!empty($roles)) {
                $user->syncRoles($roles);
            }
        }
    }

    /**
     * Memvalidasi dan memastikan kekuatan password sesuai lingkungan eksekusi (environment).
     * Mencegah penggunaan password bawaan yang lemah di lingkungan production.
     *
     * @throws \RuntimeException jika password kosong atau lemah di production
     */
    protected function resolvePassword(string $roleKey, ?string $specificPassword): string
    {
        $password = !empty($specificPassword) ? $specificPassword : config('seeder.default_password');

        // Proteksi ketat lingkungan production: Larang keras penggunaan password lemah atau bawaan umum
        if (app()->environment('production')) {
            $weakPasswords = ['password', '123456', '12345678', 'admin', 'simlibas', 'secret', 'qwerty', 'root', 'pass123', ''];
            if (empty($password) || in_array(strtolower(trim($password)), $weakPasswords, true)) {
                throw new \RuntimeException(
                    "❌ [SECURITY VIOLATION] Gagal menjalankan seeder untuk akun pengurus '{$roleKey}'. " .
                    "Di lingkungan production, Anda WAJIB menentukan SEEDER_DEFAULT_PASSWORD atau SEEDER_" . strtoupper($roleKey) . "_PASSWORD " .
                    "pada file .env dengan kombinasi yang kuat dan unik (tidak boleh kosong atau menggunakan kata sandi lemah default '{$password}')."
                );
            }
        }

        // Untuk lingkungan non-production (local, testing), jika belum ditentukan di .env, fallback ke 'password'
        return !empty($password) ? $password : 'password';
    }
}
