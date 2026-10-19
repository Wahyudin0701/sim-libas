<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\Announcements\Pages\CreateAnnouncement;
use App\Filament\Resources\Announcements\Pages\EditAnnouncement;
use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 8: Komunikasi Warga, Pengumuman & Agenda Kegiatan
 * Skenario: TC-109 s/d TC-119
 */
class Modul08KomunikasiAgendaScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $sekretaris;
    protected User $koorSampah;
    protected User $rwKelurahan;
    protected User $warga;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->first();
        $this->koorSampah = User::where('email', 'banksampah@simlibas.id')->first();

        // RW / Kelurahan
        $rw = User::where('email', 'rw@simlibas.id')->first();
        if (!$rw) {
            $rw = User::create([
                'name' => 'Bpk. RW 04 Kelurahan',
                'email' => 'rw@simlibas.id',
                'password' => bcrypt('password'),
                'status' => 'aktif',
            ]);
            $rw->assignRole('rw_kelurahan');
        }
        $this->rwKelurahan = $rw;

        $this->warga = User::where('email', 'warga.dedi@simlibas.id')->first();
    }

    /**
     * TC-109: Sekretaris membuat pengumuman publik yang di-pin
     */
    public function test_tc_109_sekretaris_membuat_pengumuman_publik(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateAnnouncement::class)
            ->fillForm([
                'judul' => 'Pengumuman Kerja Bakti Lingkungan RT 15',
                'isi' => 'Diharapkan seluruh kepala keluarga hadir membawa cangkul dan sapu lidi.',
                'target' => 'semua',
                'is_pinned' => true,
                'is_published' => true,
                'tanggal_tayang' => now()->format('Y-m-d H:i:s'),
                'dibuat_oleh' => $this->sekretaris->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('announcements', [
            'judul' => 'Pengumuman Kerja Bakti Lingkungan RT 15',
            'target' => 'semua',
            'is_pinned' => true,
            'is_published' => true,
        ]);
    }

    /**
     * TC-110: Pengumuman publik tampil di halaman beranda /
     */
    public function test_tc_110_pengumuman_publik_tampil_di_beranda(): void
    {
        $announcement = Announcement::create([
            'judul' => 'Sosialisasi Pencegahan Demam Berdarah',
            'isi' => 'Pemeriksaan jentik nyamuk dan fogging serentak.',
            'target' => 'semua',
            'is_pinned' => true,
            'is_published' => true,
            'tanggal_tayang' => now(),
            'dibuat_oleh' => $this->sekretaris->id,
        ]);
        $announcement->created_at = now()->addHour();
        $announcement->save();

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Sosialisasi Pencegahan Demam Berdarah');
    }

    /**
     * TC-111: Pengumuman tampil di dashboard warga
     */
    public function test_tc_111_pengumuman_tampil_di_dashboard_warga(): void
    {
        $announcement = Announcement::create([
            'judul' => 'Jadwal Ronda Malam Periode Baru',
            'isi' => 'Ronda malam mulai aktif jam 22.00 WIB.',
            'target' => 'warga',
            'is_pinned' => true,
            'is_published' => true,
            'tanggal_tayang' => now(),
            'dibuat_oleh' => $this->sekretaris->id,
        ]);
        // Set created_at agar berada di posisi paling atas
        $announcement->created_at = now()->addHour();
        $announcement->save();

        $response = $this->actingAs($this->warga)->get('/warga/dashboard');
        $response->assertOk();
        $response->assertSee('Jadwal Ronda Malam Periode Baru');
    }

    /**
     * TC-112: Menyembunyikan pengumuman (Unpublish)
     */
    public function test_tc_112_menyembunyikan_pengumuman_unpublish(): void
    {
        $announcement = Announcement::create([
            'judul' => 'Pengumuman Rahasia Yang Ditarik',
            'isi' => 'Isi pengumuman ditarik.',
            'target' => 'semua',
            'is_published' => true,
            'is_pinned' => false,
            'dibuat_oleh' => $this->sekretaris->id,
        ]);

        Livewire::actingAs($this->sekretaris)
            ->test(EditAnnouncement::class, ['record' => $announcement->id])
            ->fillForm([
                'is_published' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $announcement->refresh();
        $this->assertFalse((bool) $announcement->is_published);

        $responsePublic = $this->get('/');
        $responsePublic->assertDontSee('Pengumuman Rahasia Yang Ditarik');

        $responseCitizen = $this->actingAs($this->warga)->get('/warga/dashboard');
        $responseCitizen->assertDontSee('Pengumuman Rahasia Yang Ditarik');
    }

    /**
     * TC-113: Sekretaris membuat jadwal agenda kegiatan baru (Event)
     */
    public function test_tc_113_sekretaris_membuat_agenda_kegiatan(): void
    {
        $startDate = now()->addDays(3)->format('Y-m-d H:i:s');
        $endDate = now()->addDays(3)->addHours(3)->format('Y-m-d H:i:s');

        Livewire::actingAs($this->sekretaris)
            ->test(CreateEvent::class)
            ->fillForm([
                'judul' => 'Gotong Royong Bersih Selokan RT 15',
                'deskripsi' => 'Pembersihan parit saluran pembuangan air utama.',
                'lokasi' => 'Sepanjang Jalan Kenanga',
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'kategori' => 'kerja_bakti',
                'is_recurring' => false,
                'dibuat_oleh' => $this->sekretaris->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('events', [
            'judul' => 'Gotong Royong Bersih Selokan RT 15',
            'kategori' => 'kerja_bakti',
            'lokasi' => 'Sepanjang Jalan Kenanga',
        ]);
    }

    /**
     * TC-114: Agenda mendatang tampil di beranda publik /
     */
    public function test_tc_114_agenda_mendatang_tampil_di_beranda(): void
    {
        Event::create([
            'judul' => 'Rapat Pleno Warga Akhir Tahun',
            'deskripsi' => 'Pembahasan program pembangunan RT 15.',
            'lokasi' => 'Balai Pertemuan Warga',
            'tanggal_mulai' => now()->addMinutes(10),
            'kategori' => 'rapat',
            'is_recurring' => false,
            'dibuat_oleh' => $this->sekretaris->id,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Rapat Pleno Warga Akhir Tahun');
    }

    /**
     * TC-115: Agenda kegiatan tampil di dashboard warga
     */
    public function test_tc_115_agenda_kegiatan_tampil_di_dashboard_warga(): void
    {
        Event::create([
            'judul' => 'Pengajian Rutin Bulanan Bapak-Bapak',
            'deskripsi' => 'Kajian ba\'da Isya.',
            'lokasi' => 'Masjid Nurul Huda',
            'tanggal_mulai' => now()->addMinutes(5),
            'kategori' => 'pengajian',
            'is_recurring' => false,
            'dibuat_oleh' => $this->sekretaris->id,
        ]);

        $response = $this->actingAs($this->warga)->get('/warga/dashboard');
        $response->assertOk();
        $response->assertSee('Pengajian Rutin Bulanan Bapak-Bapak');
    }

    /**
     * TC-116: Sekretaris memperbarui jadwal dan lokasi kegiatan
     */
    public function test_tc_116_sekretaris_mengubah_agenda_kegiatan(): void
    {
        $event = Event::create([
            'judul' => 'Senam Pagi Sehat Warga',
            'deskripsi' => 'Senam bersama instruktur.',
            'lokasi' => 'Lapangan RT',
            'tanggal_mulai' => now()->addDays(4),
            'kategori' => 'perayaan',
            'is_recurring' => false,
            'dibuat_oleh' => $this->sekretaris->id,
        ]);

        Livewire::actingAs($this->sekretaris)
            ->test(EditEvent::class, ['record' => $event->id])
            ->fillForm([
                'lokasi' => 'Halaman Kantor Sekretariat RT 15',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $event->refresh();
        $this->assertEquals('Halaman Kantor Sekretariat RT 15', $event->lokasi);
    }

    /**
     * TC-117: Sekretaris menghapus agenda kegiatan
     */
    public function test_tc_117_sekretaris_menghapus_agenda_kegiatan(): void
    {
        $event = Event::create([
            'judul' => 'Agenda Yang Dibatalkan',
            'deskripsi' => 'Dibatalkan karena cuaca.',
            'lokasi' => 'Pos Kamling',
            'tanggal_mulai' => now()->addDays(7),
            'kategori' => 'lainnya',
            'is_recurring' => false,
            'dibuat_oleh' => $this->sekretaris->id,
        ]);

        Livewire::actingAs($this->sekretaris)
            ->test(EditEvent::class, ['record' => $event->id])
            ->callAction('delete');

        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    /**
     * TC-118: Koordinator Bank Sampah dilarang mengakses Agenda Kegiatan
     */
    public function test_tc_118_koor_sampah_dilarang_akses_agenda(): void
    {
        $response = $this->actingAs($this->koorSampah)->get('/admin/events');
        $response->assertForbidden();
    }

    /**
     * TC-119: RW / Kelurahan dilarang mengelola Pengumuman
     */
    public function test_tc_119_rw_kelurahan_dilarang_akses_pengumuman(): void
    {
        $response = $this->actingAs($this->rwKelurahan)->get('/admin/announcements');
        $response->assertForbidden();
    }
}
