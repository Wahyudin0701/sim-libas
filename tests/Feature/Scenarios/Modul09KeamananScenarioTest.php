<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\GuestReports\Pages\CreateGuestReport;
use App\Filament\Resources\PatrolSchedules\Pages\CreatePatrolSchedule;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\Notification;
use App\Models\PatrolMember;
use App\Models\PatrolSchedule;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 9: Keamanan Lingkungan, Ronda & Lapor Tamu
 * Skenario: TC-120 s/d TC-130
 */
class Modul09KeamananScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $koorKeamanan;
    protected User $bendahara;
    protected User $koorSampah;
    protected User $rwKelurahan;
    protected User $sekretaris;
    protected Household $householdA;
    protected Resident $residentA;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->koorKeamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $this->koorSampah = User::where('email', 'banksampah@simlibas.com')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();

        // RW / Kelurahan
        $rw = User::where('email', 'rw@simlibas.com')->first();
        if (!$rw) {
            $rw = User::create([
                'name' => 'Bpk. RW 04 Kelurahan',
                'email' => 'rw@simlibas.com',
                'password' => bcrypt('password'),
                'status' => 'aktif',
            ]);
            $rw->assignRole('rw_kelurahan');
        }
        $this->rwKelurahan = $rw;

        $this->householdA = Household::first();
        $this->residentA = $this->householdA->residents()->first();
    }

    /**
     * TC-120: Koordinator Keamanan membuat jadwal ronda malam baru
     */
    public function test_tc_120_koor_keamanan_membuat_jadwal_ronda(): void
    {
        // Hapus hari_ke 1 jika sudah ada dari seeder agar dapat dibuat melalui form
        PatrolSchedule::where('hari_ke', 1)->delete();

        Livewire::actingAs($this->koorKeamanan)
            ->test(CreatePatrolSchedule::class)
            ->fillForm([
                'hari_ke' => 1,
                'regu' => 'Regu Garuda Jaya',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Fokus patroli pada lorong tembus blok C',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('patrol_schedules', [
            'hari_ke' => 1,
            'regu' => 'Regu Garuda Jaya',
            'catatan' => 'Fokus patroli pada lorong tembus blok C',
        ]);
    }

    /**
     * TC-121: Verifikasi analitik pada dashboard Koordinator Keamanan
     */
    public function test_tc_121_dashboard_koordinator_keamanan(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin');
        $response->assertOk();
        $response->assertSee('Koordinator Keamanan');
        $response->assertSee('Keamanan');
    }

    /**
     * TC-122: Menautkan warga yang bertugas ronda ke jadwal
     */
    public function test_tc_122_menautkan_anggota_regu_ke_jadwal_ronda(): void
    {
        $schedule = PatrolSchedule::updateOrCreate(
            ['hari_ke' => 2],
            [
                'regu' => 'Regu Rajawali',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Ronda Barat',
                'catatan' => 'Ronda gabungan',
                'is_aktif' => true,
            ]
        );

        $patrolMember = PatrolMember::create([
            'patrol_schedule_id' => $schedule->id,
            'resident_id' => $this->residentA->id,
            'peran' => 'anggota',
        ]);

        $this->assertDatabaseHas('patrol_members', [
            'patrol_schedule_id' => $schedule->id,
            'resident_id' => $this->residentA->id,
            'peran' => 'anggota',
        ]);

        $this->assertTrue($schedule->refresh()->members->pluck('resident_id')->contains($this->residentA->id));
    }

    /**
     * TC-123: Pencatatan tamu menginap 1x24 jam oleh pengurus
     */
    public function test_tc_123_pencatatan_lapor_tamu_oleh_pengurus(): void
    {
        Livewire::actingAs($this->koorKeamanan)
            ->test(CreateGuestReport::class)
            ->fillForm([
                'household_id' => $this->householdA->id,
                'nama_tamu' => 'Bpk. Hendro Siswanto',
                'nik_tamu' => '1571029988770001',
                'asal' => 'Palembang',
                'tujuan' => 'Kunjungan keluarga silaturahmi lebaran',
                'tanggal_datang' => now()->format('Y-m-d'),
                'tanggal_pulang' => now()->addDays(3)->format('Y-m-d'),
                'dilaporkan_oleh' => $this->koorKeamanan->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('guest_reports', [
            'household_id' => $this->householdA->id,
            'nama_tamu' => 'Bpk. Hendro Siswanto',
            'asal' => 'Palembang',
        ]);
    }

    /**
     * TC-124: Koordinator Keamanan menerima notifikasi laporan tamu
     */
    public function test_tc_124_koor_keamanan_menerima_notifikasi_tamu(): void
    {
        $guestReport = GuestReport::create([
            'household_id' => $this->householdA->id,
            'nama_tamu' => 'Bpk. Syaiful Anwar',
            'asal' => 'Padang',
            'tujuan' => 'Menghadiri pernikahan',
            'tanggal_datang' => now(),
            'tanggal_pulang' => now()->addDays(2),
            'dilaporkan_oleh' => $this->sekretaris->id,
        ]);

        \App\Services\NotificationService::notifyNewGuestReport($guestReport);

        $this->assertTrue(
            Notification::where('user_id', $this->koorKeamanan->id)
                ->where('judul', 'like', '%Tamu%')
                ->exists()
        );
    }

    /**
     * TC-125: Memantau daftar tamu yang masih aktif menginap
     */
    public function test_tc_125_monitoring_tamu_aktif_menginap(): void
    {
        GuestReport::create([
            'household_id' => $this->householdA->id,
            'nama_tamu' => 'Tamu Menginap Aktif',
            'asal' => 'Jakarta',
            'tujuan' => 'Dinas kerja',
            'tanggal_datang' => now()->subDay(),
            'tanggal_pulang' => now()->addDays(2),
            'dilaporkan_oleh' => $this->koorKeamanan->id,
        ]);

        $response = $this->actingAs($this->koorKeamanan)->get('/admin/guest-reports');
        $response->assertOk();
        $response->assertSee('Tamu Menginap Aktif');
    }

    /**
     * TC-126: Koordinator Keamanan dilarang mengakses Kas RT
     */
    public function test_tc_126_koor_keamanan_dilarang_akses_kas(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin/cash-transactions');
        $response->assertForbidden();
    }

    /**
     * TC-127: Koordinator Keamanan dilarang mengakses Surat Pengantar
     */
    public function test_tc_127_koor_keamanan_dilarang_akses_surat(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin/letters');
        $response->assertForbidden();
    }

    /**
     * TC-128: Bendahara RT dilarang mengakses Jadwal Ronda
     */
    public function test_tc_128_bendahara_dilarang_akses_jadwal_ronda(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/patrol-schedules');
        $response->assertForbidden();
    }

    /**
     * TC-129: Koordinator Bank Sampah dilarang mengakses Lapor Tamu
     */
    public function test_tc_129_koor_sampah_dilarang_akses_lapor_tamu(): void
    {
        $response = $this->actingAs($this->koorSampah)->get('/admin/guest-reports');
        $response->assertForbidden();
    }

    /**
     * TC-130: RW / Kelurahan dilarang mengakses Jadwal Ronda
     */
    public function test_tc_130_rw_kelurahan_dilarang_akses_jadwal_ronda(): void
    {
        $response = $this->actingAs($this->rwKelurahan)->get('/admin/patrol-schedules');
        $response->assertForbidden();
    }
}
