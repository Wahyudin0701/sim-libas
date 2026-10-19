<?php

namespace Tests\Feature\Scenarios;

use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Modul01AuthScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $wargaUser;
    protected Resident $resident;
    protected User $bendahara;
    protected User $sekretaris;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->wargaUser = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $this->resident = Resident::where('user_id', $this->wargaUser->id)->firstOrFail();
        $this->bendahara = User::where('email', 'bendahara@simlibas.com')->firstOrFail();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.com')->firstOrFail();
    }

    /**
     * TC-001: Login warga dengan NIK 16 digit valid
     */
    public function test_tc_001_login_warga_dengan_nik_valid(): void
    {
        $response = $this->post(route('citizen.login.post'), [
            'login' => $this->resident->nik,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($this->wargaUser);
    }

    /**
     * TC-002: Login warga dengan email terdaftar
     */
    public function test_tc_002_login_warga_dengan_email(): void
    {
        $response = $this->post(route('citizen.login.post'), [
            'login' => $this->wargaUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($this->wargaUser);
    }

    /**
     * TC-003: Login warga dengan nomor telepon terdaftar
     */
    public function test_tc_003_login_warga_dengan_telepon(): void
    {
        $response = $this->post(route('citizen.login.post'), [
            'login' => $this->wargaUser->phone,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($this->wargaUser);
    }

    /**
     * TC-004: Login warga dengan kata sandi salah
     */
    public function test_tc_004_login_warga_password_salah(): void
    {
        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'login' => $this->resident->nik,
            'password' => 'password-yang-salah',
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * TC-005: Login akun warga yang dinonaktifkan
     */
    public function test_tc_005_login_akun_warga_nonaktif(): void
    {
        $this->wargaUser->update(['status' => 'nonaktif']);

        $response = $this->from(route('citizen.login'))->post(route('citizen.login.post'), [
            'login' => $this->wargaUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * TC-006: Pengurus login via portal warga diarahkan ke admin
     */
    public function test_tc_006_pengurus_login_portal_warga_redirect_ke_admin(): void
    {
        $response = $this->post(route('citizen.login.post'), [
            'login' => $this->bendahara->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->bendahara);
    }

    /**
     * TC-007: Logout warga
     */
    public function test_tc_007_logout_warga(): void
    {
        $response = $this->actingAs($this->wargaUser)->post(route('citizen.logout'));

        $response->assertRedirect(route('public.home'));
        $this->assertGuest();
    }

    /**
     * TC-008: Login pengurus ke Filament panel
     */
    public function test_tc_008_login_pengurus_ke_filament_panel(): void
    {
        $loginPage = $this->get('/admin/login');
        $loginPage->assertOk();

        $panelAccess = $this->actingAs($this->sekretaris)->get('/admin');
        $panelAccess->assertOk();
    }

    /**
     * TC-009: Warga biasa dilarang masuk panel /admin (403)
     */
    public function test_tc_009_warga_biasa_dilarang_akses_panel_admin(): void
    {
        $response = $this->actingAs($this->wargaUser)->get('/admin');
        $response->assertStatus(403);
    }

    /**
     * TC-010: Akses portal warga tanpa login diredirect
     */
    public function test_tc_010_akses_portal_warga_tanpa_login_redirect(): void
    {
        $response = $this->get(route('warga.dashboard'));
        $response->assertRedirect(route('citizen.login'));
    }

    /**
     * TC-011: Memperbarui email warga dengan password benar
     */
    public function test_tc_011_update_email_warga_valid(): void
    {
        $newEmail = 'dedi.baru@simlibas.com';
        $response = $this->actingAs($this->wargaUser)->put(route('warga.profile.email'), [
            'email' => $newEmail,
            'current_password' => 'password',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $this->wargaUser->id, 'email' => $newEmail]);
        $this->assertDatabaseHas('residents', ['id' => $this->resident->id, 'email' => $newEmail]);
    }

    /**
     * TC-012: Memperbarui email warga dengan password salah
     */
    public function test_tc_012_update_email_password_salah(): void
    {
        $response = $this->actingAs($this->wargaUser)->put(route('warga.profile.email'), [
            'email' => 'dedi.gagal@simlibas.com',
            'current_password' => 'wrong-pass',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    /**
     * TC-013: Memperbarui email dengan email duplikat akun lain
     */
    public function test_tc_013_update_email_duplikat(): void
    {
        $response = $this->actingAs($this->wargaUser)->put(route('warga.profile.email'), [
            'email' => $this->bendahara->email,
            'current_password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-014: Memperbarui kata sandi akun warga
     */
    public function test_tc_014_update_password_akun_warga(): void
    {
        $response = $this->actingAs($this->wargaUser)->put(route('warga.profile.password'), [
            'current_password' => 'password',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHas('success');
        $this->wargaUser->refresh();
        $this->assertTrue(Hash::check('passwordBaru123', $this->wargaUser->password));
    }

    /**
     * TC-015: Memperbarui nomor telepon warga
     */
    public function test_tc_015_update_telepon_warga(): void
    {
        $response = $this->actingAs($this->wargaUser)->put(route('warga.profile.phone'), [
            'phone' => '0812-9988-7766',
        ]);

        $response->assertSessionHas('success');
        $cleanPhone = '0812-9988-7766';
        $this->assertDatabaseHas('users', ['id' => $this->wargaUser->id, 'phone' => $cleanPhone]);
        $this->assertDatabaseHas('residents', ['id' => $this->resident->id, 'telepon' => $cleanPhone]);
    }
}
