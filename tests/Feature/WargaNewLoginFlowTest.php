<?php

namespace Tests\Feature;

use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WargaNewLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $wargaUser;
    protected Resident $resident;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->wargaUser = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $this->resident = Resident::where('user_id', $this->wargaUser->id)->firstOrFail();

        // Sinkronkan password warga dengan NIK-nya sesuai mekanisme baru
        $this->wargaUser->update([
            'name' => $this->resident->nama,
            'password' => Hash::make($this->resident->nik),
            'status' => 'aktif',
        ]);

        $this->adminUser = User::where('email', 'admin@simlibas.com')->firstOrFail();
    }

    /**
     * 1. Halaman login warga menampilkan label Nama KTP, NIK, dan petunjuk ramah lansia
     */
    public function test_login_page_renders_new_labels_and_elderly_instructions(): void
    {
        $response = $this->get(route('citizen.login'));

        $response->assertOk();
        $response->assertSee('Nama Lengkap Sesuai KTP');
        $response->assertSee('Nomor Induk Kependudukan (NIK)');
        $response->assertSee('Petunjuk Masuk Warga');
        $response->assertSee('Masuk ke Portal Warga');
    }

    /**
     * 2. Warga berhasil login dengan Nama Lengkap sesuai KTP dan NIK valid
     */
    public function test_citizen_can_login_with_valid_name_and_nik(): void
    {
        $response = $this->post(route('citizen.login.post'), [
            'nama' => $this->resident->nama,
            'nik' => $this->resident->nik,
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($this->wargaUser);
    }

    /**
     * 3. Warga dapat login secara case-insensitive dan dengan spasi ekstra
     */
    public function test_citizen_can_login_case_insensitive_and_extra_spaces(): void
    {
        $variedName = '  ' . strtolower($this->resident->nama) . '   ';

        $response = $this->post(route('citizen.login.post'), [
            'nama' => $variedName,
            'nik' => $this->resident->nik,
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($this->wargaUser);
    }

    /**
     * 4. Dua warga dengan nama yang sama persis tidak mengalami salah autentikasi (ambiguitas tertangani oleh NIK unik)
     */
    public function test_duplicate_names_authenticate_to_correct_individual_accounts(): void
    {
        $household = Household::first();

        // Warga 1: Siti Nurhaliza (NIK 1)
        $nik1 = '1571010101900001';
        $user1 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti1@warga.simlibas.com',
            'password' => Hash::make($nik1),
            'status' => 'aktif',
        ]);
        $user1->assignRole('warga');

        $resident1 = Resident::create([
            'household_id' => $household?->id,
            'user_id' => $user1->id,
            'nama' => 'Siti Nurhaliza',
            'nik' => $nik1,
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'hubungan_keluarga' => 'istri',
            'kategori_warga' => 'memiliki_kk',
            'status_kependudukan' => 'aktif',
        ]);

        // Warga 2: Siti Nurhaliza (NIK 2) - nama sama persis, NIK berbeda
        $nik2 = '1571010202950002';
        $user2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti2@warga.simlibas.com',
            'password' => Hash::make($nik2),
            'status' => 'aktif',
        ]);
        $user2->assignRole('warga');

        $resident2 = Resident::create([
            'household_id' => $household?->id,
            'user_id' => $user2->id,
            'nama' => 'Siti Nurhaliza',
            'nik' => $nik2,
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1995-02-02',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'hubungan_keluarga' => 'anak',
            'kategori_warga' => 'memiliki_kk',
            'status_kependudukan' => 'aktif',
        ]);

        // Login Warga 1
        $response1 = $this->post(route('citizen.login.post'), [
            'nama' => 'Siti Nurhaliza',
            'nik' => $nik1,
        ]);
        $response1->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($user1);

        // Logout
        $this->post(route('citizen.logout'));
        $this->assertGuest();

        // Login Warga 2
        $response2 = $this->post(route('citizen.login.post'), [
            'nama' => 'Siti Nurhaliza',
            'nik' => $nik2,
        ]);
        $response2->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($user2);
    }

    /**
     * 5. Login ditolak jika nama benar tapi NIK salah
     */
    public function test_login_rejected_when_name_correct_but_nik_wrong(): void
    {
        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'nama' => $this->resident->nama,
            'nik' => '1571019999999999', // NIK salah / tidak terdaftar
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('nama');
        $this->assertGuest();
    }

    /**
     * 6. Login ditolak jika NIK valid tapi nama milik orang lain
     */
    public function test_login_rejected_when_nik_belongs_to_different_name(): void
    {
        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'nama' => 'Nama Orang Lain Yang Salah',
            'nik' => $this->resident->nik,
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('nama');
        $this->assertGuest();
    }

    /**
     * 7. Akun warga yang dinonaktifkan ditolak login
     */
    public function test_inactive_citizen_account_cannot_login(): void
    {
        $this->wargaUser->update(['status' => 'nonaktif']);

        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'nama' => $this->resident->nama,
            'nik' => $this->resident->nik,
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('nama');
        $this->assertGuest();
    }

    /**
     * 8. Format NIK tidak 16 digit ditolak dengan validasi yang jelas
     */
    public function test_invalid_nik_digits_rejected(): void
    {
        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'nama' => $this->resident->nama,
            'nik' => '12345', // kurang dari 16 digit
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    /**
     * 9. Pengurus login via form portal diarahkan ke panel admin
     */
    public function test_official_login_via_portal_redirects_to_admin(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->firstOrFail();

        $response = $this->post(route('citizen.login.post'), [
            'nama' => $bendahara->email,
            'nik' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($bendahara);
    }

    /**
     * 10. Pengurus login langsung di panel admin Filament tetap berfungsi normal
     */
    public function test_admin_filament_login_page_remains_intact(): void
    {
        $response = $this->get('/admin/login');
        $response->assertOk();
        $response->assertSee('Portal Pengurus RT 15');
        $response->assertSee('Alamat Email Pengurus');
        $response->assertSee('Kata Sandi Akun');
    }
}
