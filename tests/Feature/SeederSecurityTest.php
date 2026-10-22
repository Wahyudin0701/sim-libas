<?php

namespace Tests\Feature;

use App\Models\FeeType;
use App\Models\LetterType;
use App\Models\PatrolSchedule;
use App\Models\RtSetting;
use App\Models\User;
use App\Models\WasteType;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\OfficialUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use ReflectionMethod;
use Tests\TestCase;

class SeederSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Memverifikasi bahwa seluruh 7 akun pengurus awal berhasil dibentuk
     * dengan password yang di-hash dengan aman dan role yang tepat.
     */
    public function test_all_official_accounts_are_seeded_with_hashed_passwords_and_roles(): void
    {
        $expectedAccounts = [
            'admin@simlibas.com' => ['super_admin'],
            'rt@simlibas.com' => ['ketua_rt'],
            'sekretaris@simlibas.com' => ['sekretaris'],
            'bendahara@simlibas.com' => ['bendahara'],
            'banksampah@simlibas.com' => ['koordinator_bank_sampah', 'warga'],
            'keamanan@simlibas.com' => ['koordinator_keamanan', 'warga'],
            'rw@simlibas.com' => ['rw_kelurahan'],
        ];

        foreach ($expectedAccounts as $email => $expectedRoles) {
            $user = User::where('email', $email)->first();

            $this->assertNotNull($user, "Akun pengurus {$email} harus terdaftar di database.");
            $this->assertEquals('aktif', $user->status, "Status akun {$email} harus aktif.");

            // Pastikan password tersimpan dalam format hash yang valid
            $this->assertTrue(
                Hash::check('password', $user->password),
                "Password akun {$email} harus sesuai dengan konfigurasi default seeder dan ter-hash."
            );

            // Pastikan role tersinkronisasi
            foreach ($expectedRoles as $role) {
                $this->assertTrue(
                    $user->hasRole($role),
                    "Akun {$email} harus memiliki role [{$role}]."
                );
            }
        }
    }

    /**
     * Memverifikasi bahwa proses seeding bersifat idempoten (dapat dijalankan berulang
     * tanpa menimbulkan kegagalan integritas atau duplikasi data).
     */
    public function test_database_seeder_is_idempotent_on_rerun(): void
    {
        // Jalankan seeder sekali lagi
        $this->seed(DatabaseSeeder::class);

        // Pastikan jumlah akun pengurus tetap tepat 7
        $officialCount = User::whereIn('email', [
            'admin@simlibas.com',
            'rt@simlibas.com',
            'sekretaris@simlibas.com',
            'bendahara@simlibas.com',
            'banksampah@simlibas.com',
            'keamanan@simlibas.com',
            'rw@simlibas.com',
        ])->count();

        $this->assertEquals(7, $officialCount, 'Jumlah akun pengurus tidak boleh menduplikasi saat re-seeding.');
    }

    /**
     * Memverifikasi bahwa seluruh data referensi master (RT Setting, Jenis Surat, Jenis Iuran,
     * Jenis Sampah, dan Master Ronda 7 Hari) terbentuk lengkap.
     */
    public function test_master_reference_data_and_patrol_schedules_exist(): void
    {
        $this->assertNotNull(RtSetting::first(), 'Master profil RT harus ada.');
        $this->assertGreaterThanOrEqual(8, LetterType::count(), 'Minimal 8 jenis surat pengantar master harus ada.');
        $this->assertGreaterThanOrEqual(6, FeeType::count(), 'Minimal 6 jenis iuran warga master harus ada.');
        $this->assertGreaterThanOrEqual(9, WasteType::count(), 'Minimal 9 jenis komoditas sampah master harus ada.');
        $this->assertEquals(7, PatrolSchedule::count(), 'Master jadwal ronda harus tepat 7 hari (Senin-Minggu).');
    }

    /**
     * Memverifikasi perlindungan keamanan: Seeder MENOLAK keras penggunaan password
     * lemah atau bawaan default di lingkungan production.
     */
    public function test_official_user_seeder_blocks_weak_passwords_in_production(): void
    {
        $seeder = new OfficialUserSeeder();
        $method = new ReflectionMethod($seeder, 'resolvePassword');
        $method->setAccessible(true);

        // Simulasikan lingkungan production
        $originalEnv = app()['env'];
        app()['env'] = 'production';
        config(['seeder.default_password' => 'password']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/SECURITY VIOLATION/');

        try {
            $method->invoke($seeder, 'super_admin', null);
        } finally {
            app()['env'] = $originalEnv;
        }
    }
}
