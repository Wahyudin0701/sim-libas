<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\LetterType;
use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $bendahara;
    protected User $koorKeamanan;
    protected User $koorBankSampah;
    protected User $wargaUser;
    protected Resident $resident;
    protected LetterType $letterType;
    protected Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data lengkap sistem SIM-LiBas
        $this->seed(DatabaseSeeder::class);

        // Ambil akun-akun pengurus yang di-seed
        $this->superAdmin = User::where('email', 'admin@simlibas.id')->firstOrFail();
        $this->ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->firstOrFail();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->firstOrFail();
        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->firstOrFail();
        $this->koorKeamanan = User::where('email', 'keamanan@simlibas.id')->firstOrFail();
        $this->koorBankSampah = User::where('email', 'banksampah@simlibas.id')->firstOrFail();

        // Ambil akun warga pemohon
        $this->wargaUser = User::where('email', 'warga.dedi@simlibas.id')->firstOrFail();
        $this->resident = Resident::where('user_id', $this->wargaUser->id)->firstOrFail();

        $this->letterType = LetterType::where('is_active', true)->firstOrFail();
        $this->invoice = Invoice::where('household_id', $this->resident->household_id)->firstOrFail();

        // Bersihkan notifikasi awal agar pengujian independen
        Notification::truncate();
    }

    /**
     * Test: Warga mengajukan surat -> notifikasi hanya diterima oleh Sekretaris, Ketua RT, dan Super Admin.
     */
    public function test_letter_submission_notifies_only_authorized_letter_managers(): void
    {
        $response = $this->actingAs($this->wargaUser)->post(route('warga.surat.store'), [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->resident->id,
            'keperluan' => 'Permohonan pembuatan KTP elektronik baru',
        ]);

        $response->assertRedirect(route('warga.surat'));

        // Sekretaris harus menerima notifikasi
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->sekretaris->id,
            'judul' => "Pengajuan Surat: {$this->letterType->nama}",
        ]);

        // Ketua RT harus menerima notifikasi
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->ketuaRt->id,
            'judul' => "Pengajuan Surat: {$this->letterType->nama}",
        ]);

        // Super Admin harus menerima notifikasi
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->superAdmin->id,
            'judul' => "Pengajuan Surat: {$this->letterType->nama}",
        ]);

        // Bendahara dan Koordinator Keamanan TIDAK boleh menerima notifikasi surat
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->bendahara->id,
            'judul' => "Pengajuan Surat: {$this->letterType->nama}",
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->koorKeamanan->id,
            'judul' => "Pengajuan Surat: {$this->letterType->nama}",
        ]);
    }

    /**
     * Test: Warga mengirim pengaduan keamanan -> diterima oleh Koordinator Keamanan, Ketua RT, Sekretaris, Super Admin.
     */
    public function test_security_complaint_notifies_security_coordinator_and_core_administrators(): void
    {
        $response = $this->actingAs($this->wargaUser)->post(route('warga.pengaduan.store'), [
            'judul' => 'Lampu Penerangan Gang Padam',
            'kategori' => 'keamanan',
            'deskripsi' => 'Lampu di lorong 3 mati sehingga jalan sangat gelap di malam hari.',
            'lokasi' => 'Lorong Kenanga 3 RT 15',
            'is_anonim' => false,
        ]);

        $response->assertRedirect(route('warga.pengaduan'));

        // Koordinator Keamanan harus menerima
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->koorKeamanan->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);

        // Ketua RT & Sekretaris harus menerima
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->ketuaRt->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->sekretaris->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);

        // Super Admin menerima
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->superAdmin->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);

        // Bendahara dan Koordinator Bank Sampah TIDAK menerima
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->bendahara->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->koorBankSampah->id,
            'judul' => 'Pengaduan Baru [Keamanan]: Lampu Penerangan Gang Padam',
        ]);
    }

    /**
     * Test: Warga mengirim pengaduan kebersihan -> diterima oleh Koordinator Bank Sampah, Ketua RT, Sekretaris, Super Admin.
     */
    public function test_waste_complaint_notifies_bank_sampah_coordinator(): void
    {
        $response = $this->actingAs($this->wargaUser)->post(route('warga.pengaduan.store'), [
            'judul' => 'Sampah Dahan Pohon Menumpuk',
            'kategori' => 'kebersihan',
            'deskripsi' => 'Ada tumpukan ranting pohon kering di tepi jalan utama.',
            'lokasi' => 'Jalan Utama Blok B',
            'is_anonim' => true,
        ]);

        $response->assertRedirect(route('warga.pengaduan'));

        // Koordinator Bank Sampah harus menerima
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->koorBankSampah->id,
            'judul' => 'Pengaduan Baru [Kebersihan]: Sampah Dahan Pohon Menumpuk',
        ]);

        // Koordinator Keamanan & Bendahara TIDAK menerima
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->koorKeamanan->id,
            'judul' => 'Pengaduan Baru [Kebersihan]: Sampah Dahan Pohon Menumpuk',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->bendahara->id,
            'judul' => 'Pengaduan Baru [Kebersihan]: Sampah Dahan Pohon Menumpuk',
        ]);
    }

    /**
     * Test: Warga konfirmasi pembayaran iuran -> diterima oleh Bendahara, Ketua RT, Super Admin.
     */
    public function test_payment_submission_notifies_bendahara_and_core_leaders(): void
    {
        // Pastikan invoice belum lunas
        $this->invoice->update(['status' => 'belum_bayar']);

        $response = $this->actingAs($this->wargaUser)->post(route('warga.iuran.pay', $this->invoice), [
            'jumlah' => $this->invoice->jumlah,
            'metode' => 'transfer',
            'catatan' => 'Transfer via Bank Mandiri',
        ]);

        $response->assertRedirect(route('warga.iuran'));

        // Bendahara harus menerima notifikasi
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->bendahara->id,
            'judul' => "Konfirmasi Pembayaran: {$this->invoice->feeType->nama}",
        ]);

        // Ketua RT & Super Admin menerima
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->ketuaRt->id,
            'judul' => "Konfirmasi Pembayaran: {$this->invoice->feeType->nama}",
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->superAdmin->id,
            'judul' => "Konfirmasi Pembayaran: {$this->invoice->feeType->nama}",
        ]);

        // Koordinator Keamanan dan Bank Sampah TIDAK menerima notifikasi pembayaran
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->koorKeamanan->id,
            'judul' => "Konfirmasi Pembayaran: {$this->invoice->feeType->nama}",
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->koorBankSampah->id,
            'judul' => "Konfirmasi Pembayaran: {$this->invoice->feeType->nama}",
        ]);
    }

    /**
     * Test: Pengurus dapat mengambil daftar notifikasi via API Admin.
     */
    public function test_admin_can_fetch_notifications_via_api(): void
    {
        Notification::create([
            'user_id' => $this->sekretaris->id,
            'judul' => 'Pengajuan Surat: Surat Pengantar KTP',
            'isi' => 'Rizky Pratama mengajukan surat pengantar',
            'url' => '/admin/letters',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->sekretaris)->getJson(route('admin.api.notifications.index'));

        $response->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonPath('notifications.0.category', 'letter');
    }

    /**
     * Test: Warga biasa tidak boleh mengakses API notifikasi pengurus (403 Forbidden).
     */
    public function test_citizen_cannot_access_admin_notification_api(): void
    {
        $response = $this->actingAs($this->wargaUser)->getJson(route('admin.api.notifications.index'));

        $response->assertForbidden();
    }

    /**
     * Test: Pengurus dapat menandai notifikasi sebagai telah dibaca.
     */
    public function test_admin_can_mark_notification_as_read(): void
    {
        $notif = Notification::create([
            'user_id' => $this->sekretaris->id,
            'judul' => 'Pengajuan Surat: Surat Pengantar KTP',
            'isi' => 'Rizky Pratama mengajukan surat pengantar',
            'url' => '/admin/letters',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->sekretaris)->postJson(route('admin.api.notifications.read', $notif));

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertTrue($notif->fresh()->is_read);
    }

    /**
     * Test: Pengurus dapat menandai seluruh notifikasi sebagai telah dibaca.
     */
    public function test_admin_can_mark_all_notifications_as_read(): void
    {
        Notification::create([
            'user_id' => $this->sekretaris->id,
            'judul' => 'Notif 1',
            'isi' => 'Isi 1',
            'is_read' => false,
        ]);
        Notification::create([
            'user_id' => $this->sekretaris->id,
            'judul' => 'Notif 2',
            'isi' => 'Isi 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->sekretaris)->postJson(route('admin.api.notifications.read-all'));

        $response->assertOk()->assertJson(['success' => true, 'unread_count' => 0]);

        $this->assertEquals(0, Notification::where('user_id', $this->sekretaris->id)->unread()->count());
    }

    /**
     * Test: Ketika pengurus menyetujui surat pengantar, warga pemohon menerima notifikasi.
     */
    public function test_approving_letter_sends_notification_to_citizen(): void
    {
        $letter = \App\Models\Letter::create([
            'resident_id' => $this->resident->id,
            'letter_type_id' => $this->letterType->id,
            'keperluan' => 'Permohonan surat keterangan untuk keperluan bank',
            'status' => 'diajukan',
            'qr_token' => bin2hex(random_bytes(16)),
        ]);

        \Livewire\Livewire::actingAs($this->ketuaRt)
            ->test(\App\Filament\Resources\Letters\Pages\ListLetters::class)
            ->call('openApproveLetter', $letter->id)
            ->set('approve_nomor_surat', '099/SK/RT.15/X/2026')
            ->call('confirmApproveLetter');

        $this->assertEquals('disetujui', $letter->fresh()->status);

        // Warga pemohon harus menerima notifikasi persetujuan
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->wargaUser->id,
            'judul' => "Surat Pengantar Disetujui ({$this->letterType->nama})",
        ]);
    }

    /**
     * Test: Ketika pengurus menolak surat pengantar, warga pemohon menerima notifikasi beserta alasannya.
     */
    public function test_rejecting_letter_sends_notification_to_citizen(): void
    {
        $letter = \App\Models\Letter::create([
            'resident_id' => $this->resident->id,
            'letter_type_id' => $this->letterType->id,
            'keperluan' => 'Permohonan surat keterangan serbaguna',
            'status' => 'diajukan',
            'qr_token' => bin2hex(random_bytes(16)),
        ]);

        \Livewire\Livewire::actingAs($this->sekretaris)
            ->test(\App\Filament\Resources\Letters\Pages\ListLetters::class)
            ->call('openRejectLetter', $letter->id)
            ->set('reject_alasan', 'Data NIK dan dokumen pendukung belum lengkap.')
            ->call('confirmRejectLetter');

        $this->assertEquals('ditolak', $letter->fresh()->status);

        // Warga pemohon harus menerima notifikasi penolakan
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->wargaUser->id,
            'judul' => "Permohonan Surat Ditolak ({$this->letterType->nama})",
        ]);
    }
}
