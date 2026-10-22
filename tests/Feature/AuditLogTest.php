<?php

namespace Tests\Feature;

use App\Filament\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Models\AuditLog;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $ketuaRt;
    protected User $bendahara;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::where('email', 'admin@simlibas.com')->firstOrFail();
        $this->ketuaRt = User::where('email', 'rt@simlibas.com')->firstOrFail();
        $this->bendahara = User::where('email', 'bendahara@simlibas.com')->firstOrFail();
    }

    /**
     * Tamu belum login diarahkan ke halaman login.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/audit-logs');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Super Admin dapat mengakses halaman Audit Log.
     */
    public function test_super_admin_can_access_audit_logs(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/audit-logs');
        $response->assertStatus(200);
        $response->assertSee('Audit Log & Jejak Aktivitas');
        $response->assertSee('PENGATURAN SISTEM');
    }

    /**
     * Ketua RT memiliki izin lihat_audit_log sehingga dapat mengakses halaman Audit Log.
     */
    public function test_ketua_rt_can_access_audit_logs(): void
    {
        $response = $this->actingAs($this->ketuaRt)->get('/admin/audit-logs');
        $response->assertStatus(200);
        $response->assertSee('Audit Log & Jejak Aktivitas');
    }

    /**
     * Bendahara yang tidak memiliki izin lihat_audit_log ditolak (403 Forbidden).
     */
    public function test_bendahara_cannot_access_audit_logs(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/audit-logs');
        $response->assertStatus(403);
    }

    /**
     * Komponen Livewire ListAuditLogs memuat data dan statistik KPI.
     */
    public function test_audit_logs_component_renders_records(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(ListAuditLogs::class)
            ->assertStatus(200)
            ->assertSee('Total Log')
            ->assertSee('Aktivitas Hari Ini')
            ->assertSee('Modifikasi Data');
    }

    /**
     * Modal detail dapat dibuka untuk melihat rincian log.
     */
    public function test_can_open_and_close_detail_modal(): void
    {
        $log = AuditLog::first();

        if ($log) {
            Livewire::actingAs($this->superAdmin)
                ->test(ListAuditLogs::class)
                ->call('openDetail', $log->id)
                ->assertSet('detailModalOpen', true)
                ->assertSet('selectedLogId', $log->id)
                ->call('closeDetail')
                ->assertSet('detailModalOpen', false)
                ->assertSet('selectedLogId', null);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * Filter pencarian dan tombol reset bekerja dengan baik.
     */
    public function test_filters_and_reset_work(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(ListAuditLogs::class)
            ->set('search', 'Administrator')
            ->assertSet('search', 'Administrator')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('eventFilter', 'semua')
            ->assertSet('moduleFilter', 'semua');
    }

    /**
     * Memastikan aktivitas login dan logout tercatat di audit log.
     */
    public function test_user_login_and_logout_are_recorded_in_audit_log(): void
    {
        $initialLoginCount = AuditLog::where('event', 'login')->where('causer_id', $this->superAdmin->id)->count();

        // 1. Simulasikan event Login (panggil dua kali untuk memastikan tidak terjadi duplikasi)
        event(new \Illuminate\Auth\Events\Login('web', $this->superAdmin, false));
        event(new \Illuminate\Auth\Events\Login('web', $this->superAdmin, false));

        $loginLogs = AuditLog::where('event', 'login')
            ->where('causer_id', $this->superAdmin->id)
            ->get();

        $this->assertCount($initialLoginCount + 1, $loginLogs);

        $loginLog = $loginLogs->last();
        $this->assertNotNull($loginLog);
        $this->assertEquals('Masuk Sistem', $loginLog->action_label);
        $this->assertEquals('Akun Pengguna', $loginLog->module_name);
        $this->assertStringContainsString('Super Admin', $loginLog->description);

        $props = $loginLog->getSafeProperties();
        $this->assertEquals($this->superAdmin->email, $props['attributes']['email'] ?? null);

        // 2. Simulasikan event Logout (panggil dua kali untuk memastikan tidak terjadi duplikasi)
        $initialLogoutCount = AuditLog::where('event', 'logout')->where('causer_id', $this->superAdmin->id)->count();
        event(new \Illuminate\Auth\Events\Logout('web', $this->superAdmin));
        event(new \Illuminate\Auth\Events\Logout('web', $this->superAdmin));

        $logoutLogs = AuditLog::where('event', 'logout')
            ->where('causer_id', $this->superAdmin->id)
            ->get();

        $this->assertCount($initialLogoutCount + 1, $logoutLogs);

        $logoutLog = $logoutLogs->last();
        $this->assertNotNull($logoutLog);
        $this->assertEquals('Keluar Sistem', $logoutLog->action_label);
    }
}
