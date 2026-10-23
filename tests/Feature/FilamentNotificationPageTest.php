<?php

namespace Tests\Feature;

use App\Filament\Pages\Notifications;
use App\Models\Notification;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentNotificationPageTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $wargaUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->adminUser = User::where('email', 'admin@simlibas.com')->firstOrFail();
        $this->wargaUser = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        Notification::truncate();
    }

    /**
     * Test: Pengurus berhak mengakses halaman /admin/notifikasi.
     */
    public function test_admin_can_access_notification_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/notifikasi');

        $response->assertOk()
            ->assertSee('Pusat Notifikasi')
            ->assertSee('KOMUNIKASI WARGA RT 15')
            ->assertSee('Tandai Semua Telah Dibaca')
            ->assertDontSee('TOTAL NOTIFIKASI')
            ->assertDontSee('Cari permohonan');
    }

    /**
     * Test: Warga biasa ditolak mengakses halaman notifikasi panel pengurus.
     */
    public function test_citizen_cannot_access_notification_page(): void
    {
        $response = $this->actingAs($this->wargaUser)->get('/admin/notifikasi');

        $response->assertForbidden();
    }

    /**
     * Test: Livewire merender daftar notifikasi dan 3 filter tab (Semua, Belum Dibaca, Surat Pengantar).
     */
    public function test_livewire_renders_notifications_and_three_filters(): void
    {
        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Pengajuan Surat Baru: Surat Pengantar SKCK',
            'isi' => 'Warga mengajukan permohonan surat pengantar.',
            'url' => '/admin/letters',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Aduan Warga: Jalan Rusak',
            'isi' => 'Laporan aspal berlubang di RT 15.',
            'url' => '/admin/complaints',
            'is_read' => true,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->assertSee('Semua')
            ->assertSee('Belum Dibaca')
            ->assertSee('Surat Pengantar')
            ->assertSee('Pengajuan Surat Baru: Surat Pengantar SKCK')
            ->assertSee('Aduan Warga: Jalan Rusak')
            ->assertDontSee('TOTAL NOTIFIKASI')
            ->assertDontSee('Cari permohonan');
    }

    /**
     * Test: Tandai satu notifikasi sebagai telah dibaca.
     */
    public function test_can_mark_single_notification_as_read_and_unread(): void
    {
        $notif = Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Konfirmasi Iuran Masuk',
            'isi' => 'Iuran warga telah ditransfer.',
            'url' => '/admin/payments',
            'is_read' => false,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('markAsRead', $notif->id);

        $this->assertTrue($notif->fresh()->is_read);

        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('markAsUnread', $notif->id);

        $this->assertFalse($notif->fresh()->is_read);
    }

    /**
     * Test: Tandai seluruh notifikasi telah dibaca secara massal.
     */
    public function test_can_mark_all_notifications_as_read(): void
    {
        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Notif 1',
            'isi' => 'Isi 1',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Notif 2',
            'isi' => 'Isi 2',
            'is_read' => false,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('markAllAsRead');

        $this->assertEquals(0, Notification::where('user_id', $this->adminUser->id)->unread()->count());
    }

    /**
     * Test: Filter 3 tab berfungsi: Semua, Belum Dibaca, dan Surat Pengantar.
     */
    public function test_three_filter_tabs_work_correctly(): void
    {
        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Pengajuan Surat Pindah',
            'isi' => 'Pemohon ingin pindah domisili.',
            'url' => '/admin/letters',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Pengaduan Lampu Padam',
            'isi' => 'Lampu jalan mati di gang 2.',
            'url' => '/admin/complaints',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $this->adminUser->id,
            'judul' => 'Pengajuan Surat Usaha',
            'isi' => 'Surat keterangan usaha UMKM.',
            'url' => '/admin/letters',
            'is_read' => true,
        ]);

        // 1. Tab Semua: menampilkan semua notifikasi
        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('setTab', 'all')
            ->assertSee('Pengajuan Surat Pindah')
            ->assertSee('Pengaduan Lampu Padam')
            ->assertSee('Pengajuan Surat Usaha');

        // 2. Tab Belum Dibaca: hanya menampilkan yang belum dibaca
        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('setTab', 'unread')
            ->assertSee('Pengajuan Surat Pindah')
            ->assertSee('Pengaduan Lampu Padam')
            ->assertDontSee('Pengajuan Surat Usaha');

        // 3. Tab Surat Pengantar: hanya menampilkan kategori surat
        Livewire::actingAs($this->adminUser)
            ->test(Notifications::class)
            ->call('setTab', 'letter')
            ->assertSee('Pengajuan Surat Pindah')
            ->assertSee('Pengajuan Surat Usaha')
            ->assertDontSee('Pengaduan Lampu Padam');
    }
}
