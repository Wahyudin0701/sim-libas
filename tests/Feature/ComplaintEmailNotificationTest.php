<?php

namespace Tests\Feature;

use App\Filament\Resources\Complaints\Pages\ListComplaints;
use App\Mail\NewComplaintNotificationMail;
use App\Models\Complaint;
use App\Models\RtSetting;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ComplaintEmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $ketuaRt;
    protected User $warga;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        NotificationService::resetSentComplaintEmailTracker();

        $this->ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $this->warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        if (!$this->warga) {
            $this->warga = User::role('warga')->first();
        }
    }

    /**
     * Uji 1: Warga berhasil mengirim pengaduan, pengaduan tersimpan, tampil di admin,
     * dan notifikasi email terkirim ke alamat email Ketua RT yang dikonfigurasi di .env.
     */
    public function test_warga_submits_complaint_and_email_is_sent_to_configured_ketua_rt(): void
    {
        Mail::fake();

        $configuredEmail = 'ketua.rt.resmi@simlibas.id';
        config(['mail.ketua_rt_email' => $configuredEmail]);

        $payload = [
            'judul' => 'Lampu Penerangan Jalan Rusak di Blok B',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Depan Rumah No. 12 Blok B',
            'deskripsi' => 'Lampu jalan sudah padam selama 3 malam berturut-turut, mohon penggantian bohlam.',
            'is_anonim' => 0,
        ];

        $response = $this->actingAs($this->warga)->post(route('warga.pengaduan.store'), $payload);

        $response->assertRedirect(route('warga.pengaduan'));
        $response->assertSessionHas('success', 'Laporan pengaduan berhasil dikirim ke pengurus RT 15.');

        // 1. Data tetap tersimpan di database
        $this->assertDatabaseHas('complaints', [
            'user_id' => $this->warga->id,
            'judul' => $payload['judul'],
            'kategori' => 'infrastruktur',
            'status' => 'diterima',
            'is_anonim' => false,
        ]);

        $complaint = Complaint::where('judul', $payload['judul'])->first();
        $this->assertNotNull($complaint);
        $this->assertNotNull($complaint->email_sent_at);

        // 2. Email terkirim ke alamat yang dikonfigurasi
        Mail::assertSent(NewComplaintNotificationMail::class, function (NewComplaintNotificationMail $mail) use ($configuredEmail, $payload) {
            return $mail->hasTo($configuredEmail)
                && $mail->complaint->judul === $payload['judul']
                && $mail->complaint->kategori === 'infrastruktur'
                && $mail->pelaporName === $this->warga->name
                && !empty($mail->detailUrl);
        });

        // 3. Tampil pada halaman pengurus (Filament admin)
        Livewire::actingAs($this->ketuaRt)
            ->test(ListComplaints::class)
            ->assertSee($payload['judul']);
    }

    /**
     * Uji 2: Pengaduan berstatus anonim tetap mengirim email dengan pelapor berlabel anonim.
     */
    public function test_anonymous_complaint_sends_email_with_anonymous_reporter_label(): void
    {
        Mail::fake();

        config(['mail.ketua_rt_email' => 'rt.notif@example.com']);

        $payload = [
            'judul' => 'Hewan Peliharaan Berkeliaran Tanpa Pengawasan',
            'kategori' => 'kebisingan',
            'lokasi' => 'Gang Melati',
            'deskripsi' => 'Anjing tetangga menggonggong tengah malam dan mengganggu kenyamanan.',
            'is_anonim' => 1,
        ];

        $response = $this->actingAs($this->warga)->post(route('warga.pengaduan.store'), $payload);
        $response->assertRedirect(route('warga.pengaduan'));

        $this->assertDatabaseHas('complaints', [
            'judul' => $payload['judul'],
            'is_anonim' => true,
        ]);

        Mail::assertSent(NewComplaintNotificationMail::class, function (NewComplaintNotificationMail $mail) {
            return str_contains($mail->pelaporName, 'Anonim');
        });
    }

    /**
     * Uji 3: Isi email memuat seluruh komponen informasi utama:
     * - Nama pelapor
     * - Judul & Kategori
     * - Deskripsi
     * - Tanggal & waktu pengiriman
     * - Tautan detail pengaduan
     */
    public function test_email_view_contains_all_required_information(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->warga->id,
            'judul' => 'Saluran Air Tersumbat Sampah Plastik',
            'kategori' => 'kebersihan',
            'lokasi' => 'Parit RT 15',
            'deskripsi' => 'Genangan air mulai meluap ke jalan setiap hujan deras turun.',
            'is_anonim' => false,
            'status' => 'diterima',
        ]);

        $mailable = new NewComplaintNotificationMail($complaint);
        $rendered = $mailable->render();

        // 1. Nama warga pelapor
        $this->assertStringContainsString($this->warga->name, $rendered);

        // 2. Judul dan kategori
        $this->assertStringContainsString('Saluran Air Tersumbat Sampah Plastik', $rendered);
        $this->assertStringContainsString('Kebersihan', $rendered);

        // 3. Deskripsi pengaduan
        $this->assertStringContainsString('Genangan air mulai meluap ke jalan setiap hujan deras turun.', $rendered);

        // 4. Tanggal dan waktu pengaduan (WIB)
        $this->assertStringContainsString('WIB', $rendered);

        // 5. Tautan detail pengaduan di sistem
        $this->assertStringContainsString($mailable->detailUrl, $rendered);
    }

    /**
     * Uji 4: Resolusi email Ketua RT ketika KETUA_RT_EMAIL tidak diatur di .env
     * Sistem menggunakan fallback akun Ketua RT aktif atau konfigurasi RT tanpa mengarang.
     */
    public function test_email_falls_back_to_existing_rt_configuration_when_env_is_empty(): void
    {
        Mail::fake();

        config(['mail.ketua_rt_email' => null]);

        $resolvedEmail = NotificationService::getKetuaRtEmail();
        $this->assertNotEmpty($resolvedEmail);
        $this->assertEquals('rt@simlibas.com', $resolvedEmail);

        $payload = [
            'judul' => 'Pohon Tumbang Menghalangi Akses Gang',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Gang Mawar',
            'deskripsi' => 'Ranting besar patah dan perlu dievakuasi bersama.',
            'is_anonim' => 0,
        ];

        $this->actingAs($this->warga)->post(route('warga.pengaduan.store'), $payload);

        Mail::assertSent(NewComplaintNotificationMail::class, function (NewComplaintNotificationMail $mail) use ($resolvedEmail) {
            return $mail->hasTo($resolvedEmail);
        });
    }

    /**
     * Uji 5: Pencegahan pengiriman email ganda (deduplication) untuk pengaduan yang sama.
     */
    public function test_duplicate_email_sending_is_prevented_for_single_complaint(): void
    {
        Mail::fake();

        config(['mail.ketua_rt_email' => 'rt@simlibas.com']);

        $complaint = Complaint::create([
            'user_id' => $this->warga->id,
            'judul' => 'Kerusakan Jalan Paving Blok',
            'kategori' => 'infrastruktur',
            'deskripsi' => 'Paving blok amblas di persimpangan jalan.',
            'status' => 'diterima',
        ]);

        // Panggilan pertama: Email harus terkirim
        $firstCall = NotificationService::sendComplaintEmailToKetuaRt($complaint);
        $this->assertTrue($firstCall);
        Mail::assertSentCount(1);

        // Panggilan kedua: Harus diabaikan (deduplikasi aktif)
        $secondCall = NotificationService::sendComplaintEmailToKetuaRt($complaint);
        $this->assertFalse($secondCall);
        Mail::assertSentCount(1);
    }

    /**
     * Uji 6: Kegagalan pengiriman email (SMTP error / Exception) TIDAK menggagalkan
     * penyimpanan pengaduan warga ke database dan alur sistem tetap berjalan normal.
     */
    public function test_complaint_saved_successfully_even_when_email_sending_fails(): void
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'Gagal mengirim email notifikasi pengaduan warga');
            });

        Log::shouldReceive('info')->zeroOrMoreTimes();
        Log::shouldReceive('debug')->zeroOrMoreTimes();
        Log::shouldReceive('warning')->zeroOrMoreTimes();

        // Simulasikan kegagalan transport mailer dengan melempar Exception
        Mail::shouldReceive('to')
            ->andReturnSelf();
        Mail::shouldReceive('send')
            ->andThrow(new \RuntimeException('Connection refused: SMTP server unreachable'));

        config(['mail.ketua_rt_email' => 'rt@simlibas.com']);

        $payload = [
            'judul' => 'Got Mampet Menimbulkan Bau',
            'kategori' => 'kebersihan',
            'lokasi' => 'Depan rumah warga no 5',
            'deskripsi' => 'Got tersumbat daun kering dan air tidak mengalir.',
            'is_anonim' => 0,
        ];

        $response = $this->actingAs($this->warga)->post(route('warga.pengaduan.store'), $payload);

        // Pengaduan tetap berhasil diarahkan dan sukses
        $response->assertRedirect(route('warga.pengaduan'));
        $response->assertSessionHas('success');

        // Pengaduan tersimpan di database
        $this->assertDatabaseHas('complaints', [
            'judul' => $payload['judul'],
            'kategori' => 'kebersihan',
            'status' => 'diterima',
        ]);
    }

    /**
     * Uji 7: Penanganan kondisi ketika konfigurasi email Ketua RT tidak tersedia.
     * Sistem mencatat peringatan dan pengaduan tetap berhasil disimpan tanpa crash.
     */
    public function test_handling_when_no_ketua_rt_email_configured(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'Alamat email Ketua RT belum dikonfigurasi');
            });

        Log::shouldReceive('info')->zeroOrMoreTimes();
        Log::shouldReceive('debug')->zeroOrMoreTimes();
        Log::shouldReceive('error')->never();

        // Kosongkan konfigurasi email
        config(['mail.ketua_rt_email' => null]);
        config(['seeder.accounts.ketua_rt.email' => null]);

        // Nonaktifkan sementara role ketua_rt dan kosongkan email di rt_settings
        User::role('ketua_rt')->get()->each(function (User $user) {
            $user->syncRoles([]);
        });
        RtSetting::query()->update(['email' => null]);

        $payload = [
            'judul' => 'Laporan Kerusakan Fasilitas Pos Ronda',
            'kategori' => 'keamanan',
            'deskripsi' => 'Plafon pos ronda jebol karena hujan angin.',
            'is_anonim' => 0,
        ];

        $response = $this->actingAs($this->warga)->post(route('warga.pengaduan.store'), $payload);

        $response->assertRedirect(route('warga.pengaduan'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'judul' => $payload['judul'],
            'status' => 'diterima',
        ]);
    }
}
