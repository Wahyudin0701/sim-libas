<?php

namespace Tests\Feature;

use App\Filament\Widgets\StatsOverview;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoleDashboardAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test: Dashboard Super Administrator
     */
    public function test_super_admin_dashboard_renders_system_and_audit_metrics(): void
    {
        $admin = User::where('email', 'admin@simlibas.com')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Super Administrator');
        $response->assertSee('Pengguna Sistem');
        $response->assertSee('Audit Log');

        Livewire::actingAs($admin)
            ->test(StatsOverview::class)
            ->assertSee('Pengguna Sistem')
            ->assertSee('Log Aktivitas')
            ->assertSee('Log Audit Sistem Terkini');
    }

    /**
     * Test: Dashboard Ketua RT
     */
    public function test_ketua_rt_dashboard_renders_executive_approvals_and_no_forbidden_links(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $this->assertNotNull($ketuaRt);

        $response = $this->actingAs($ketuaRt)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Ketua RT 15');
        $response->assertSee('Perlu Persetujuan');
        $response->assertSee('Pengaduan Aktif');
        $response->assertSee('Total Kependudukan');
        $response->assertSee('Saldo Kas RT 15');
        $response->assertSee('Bank Sampah Lestari');

        // Pastikan TIDAK ADA link yang dilarang untuk Ketua RT (invoices / waste-deposits)
        $content = $response->getContent();
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/invoices"', $content);
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/waste-deposits"', $content);
        $this->assertStringNotContainsString('href="/admin/invoices"', $content);
        $this->assertStringNotContainsString('href="/admin/waste-deposits"', $content);

        Livewire::actingAs($ketuaRt)
            ->test(StatsOverview::class)
            ->assertSee('Perlu Persetujuan')
            ->assertSee('Surat Menunggu Persetujuan')
            ->assertSee('Pengaduan Warga Aktif');
    }

    /**
     * Test: Dashboard Bendahara
     */
    public function test_bendahara_dashboard_renders_financial_metrics_and_pending_validations(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $this->assertNotNull($bendahara);

        $response = $this->actingAs($bendahara)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Bendahara RT 15');
        $response->assertSee('Saldo Kas Bersih');
        $response->assertSee('Perlu Validasi');
        $response->assertSee('Total Pemasukan');
        $response->assertSee('Total Pengeluaran');
        $response->assertSee('Tagihan Tertunda');

        Livewire::actingAs($bendahara)
            ->test(StatsOverview::class)
            ->assertSee('Saldo Kas Bersih')
            ->assertSee('Perlu Validasi')
            ->assertSee('Konfirmasi Pembayaran Warga')
            ->assertSee('Buku Kas Terkini');
    }

    /**
     * Test: Dashboard Sekretaris
     */
    public function test_sekretaris_dashboard_renders_letter_verification_and_announcements(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $this->assertNotNull($sekretaris);

        $response = $this->actingAs($sekretaris)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Sekretaris RT 15');
        $response->assertSee('Perlu Verifikasi');
        $response->assertSee('Surat Diterbitkan');
        $response->assertSee('Total Warga');
        $response->assertSee('Kartu Keluarga');

        Livewire::actingAs($sekretaris)
            ->test(StatsOverview::class)
            ->assertSee('Perlu Verifikasi')
            ->assertSee('Surat Diterbitkan')
            ->assertSee('Permohonan Surat Masuk')
            ->assertSee('Pengumuman')
            ->assertSee('Agenda Warga');
    }

    /**
     * Test: Dashboard Koordinator Keamanan
     */
    public function test_koordinator_keamanan_dashboard_renders_patrol_guest_reports_and_no_complaint_link(): void
    {
        $keamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $this->assertNotNull($keamanan);

        $response = $this->actingAs($keamanan)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Koordinator Keamanan');
        $response->assertSee('Ronda Malam Ini');
        $response->assertSee('Agenda Ronda');
        $response->assertSee('Tamu Menginap');
        $response->assertSee('Total Lapor Tamu');

        // Pastikan TIDAK ADA link yang mengarah ke /admin/complaints (karena dilarang)
        $content = $response->getContent();
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/complaints"', $content);
        $this->assertStringNotContainsString('href="/admin/complaints"', $content);

        Livewire::actingAs($keamanan)
            ->test(StatsOverview::class)
            ->assertSee('Ronda Malam Ini')
            ->assertSee('Laporan Tamu 1x24 Jam')
            ->assertSee('Jadwal Ronda Terkini');
    }

    /**
     * Test: Dashboard Koordinator Bank Sampah
     */
    public function test_koordinator_bank_sampah_dashboard_renders_waste_metrics(): void
    {
        $bankSampah = User::where('email', 'banksampah@simlibas.com')->first();
        $this->assertNotNull($bankSampah);

        $response = $this->actingAs($bankSampah)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Koordinator Bank Sampah');
        $response->assertSee('Total Sampah');
        $response->assertSee('Total Setoran');
        $response->assertSee('Nasabah Aktif');
        $response->assertSee('Tabungan Sampah');

        Livewire::actingAs($bankSampah)
            ->test(StatsOverview::class)
            ->assertSee('Total Sampah')
            ->assertSee('Setoran Sampah Terkini')
            ->assertSee('Katalog Harga Sampah');
    }

    /**
     * Test: Dashboard RW / Kelurahan
     */
    public function test_rw_kelurahan_dashboard_renders_macro_supervisory_and_no_forbidden_links(): void
    {
        $rw = User::where('email', 'rw@simlibas.com')->first();
        $this->assertNotNull($rw);

        $response = $this->actingAs($rw)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('RW / Kelurahan');
        $response->assertSee('Saldo Kas RT 15');
        $response->assertSee('Pemasukan Kas');
        $response->assertSee('Pengeluaran Kas');
        $response->assertSee('Sampah Terkelola');
        $response->assertSee('Tabungan Sampah');
        $response->assertSee('Agregat Warga');

        // Pastikan TIDAK ADA link yang terlarang untuk RW (residents, households, letters, complaints, invoices)
        $content = $response->getContent();
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/residents"', $content);
        $this->assertStringNotContainsString('href="/admin/residents"', $content);
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/households"', $content);
        $this->assertStringNotContainsString('href="/admin/households"', $content);
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/letters"', $content);
        $this->assertStringNotContainsString('href="/admin/letters"', $content);
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/complaints"', $content);
        $this->assertStringNotContainsString('href="/admin/complaints"', $content);
        $this->assertStringNotContainsString('href="http://localhost:8080/admin/invoices"', $content);
        $this->assertStringNotContainsString('href="/admin/invoices"', $content);

        // Pastikan link yang sah ada
        $response->assertSee('/admin/cash-transactions');
        $response->assertSee('/admin/waste-deposits');

        Livewire::actingAs($rw)
            ->test(StatsOverview::class)
            ->assertSee('Saldo Kas RT 15')
            ->assertSee('Transparansi Kas RT 15')
            ->assertSee('Setoran Sampah RT 15');
    }
}
