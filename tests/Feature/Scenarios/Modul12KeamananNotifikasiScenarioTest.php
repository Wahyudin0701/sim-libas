<?php

namespace Tests\Feature\Scenarios;

use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\LetterType;
use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Modul12KeamananNotifikasiScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    /**
     * TC-149: Pengurus fetch notifikasi in-app via Topbar
     */
    public function test_tc_149_pengurus_fetch_notifikasi_in_app_via_topbar(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->firstOrFail();

        Notification::create([
            'user_id' => $sekretaris->id,
            'judul' => 'Pengajuan Surat Baru',
            'isi' => 'Warga mengajukan surat pengantar baru.',
            'url' => '/admin/letters',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        Notification::create([
            'user_id' => $sekretaris->id,
            'judul' => 'Pengaduan Selesai',
            'isi' => 'Pengaduan telah ditandai selesai.',
            'url' => '/admin/complaints',
            'is_read' => true,
            'read_at' => now(),
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($sekretaris)->getJson('/admin/api/notifikasi');

        $response->assertOk()
            ->assertJsonStructure([
                'unread_count',
                'notifications' => [
                    '*' => ['id', 'judul', 'isi', 'url', 'is_read', 'category', 'time_ago'],
                ],
            ]);

        $this->assertEquals(1, $response->json('unread_count'));
        $this->assertCount(2, $response->json('notifications'));
    }

    /**
     * TC-150: Pengurus tandai 1 notifikasi dibaca
     */
    public function test_tc_150_pengurus_tandai_1_notifikasi_dibaca(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->firstOrFail();

        $notif = Notification::create([
            'user_id' => $sekretaris->id,
            'judul' => 'Notifikasi Pengurus 1',
            'isi' => 'Isi notifikasi pengurus.',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($sekretaris)->postJson("/admin/api/notifikasi/{$notif->id}/baca");

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertTrue((bool) $notif->fresh()->is_read);
        $this->assertNotNull($notif->fresh()->read_at);
    }

    /**
     * TC-151: Pengurus tandai seluruh notifikasi dibaca
     */
    public function test_tc_151_pengurus_tandai_seluruh_notifikasi_dibaca(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->firstOrFail();

        Notification::create([
            'user_id' => $sekretaris->id,
            'judul' => 'Notif 1',
            'isi' => 'Isi 1',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        Notification::create([
            'user_id' => $sekretaris->id,
            'judul' => 'Notif 2',
            'isi' => 'Isi 2',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($sekretaris)->postJson('/admin/api/notifikasi/baca-semua');

        $response->assertOk()
            ->assertJson(['success' => true]);

        $unreadCount = Notification::where('user_id', $sekretaris->id)->unread()->count();
        $this->assertEquals(0, $unreadCount);
    }

    /**
     * TC-152: Warga dilarang akses API notifikasi admin
     */
    public function test_tc_152_warga_dilarang_akses_api_notifikasi_admin(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        $response = $this->actingAs($warga)->getJson('/admin/api/notifikasi');

        $response->assertForbidden();
    }

    /**
     * TC-153: Pengurus dilarang tandai notif pengurus lain
     */
    public function test_tc_153_pengurus_dilarang_tandai_notif_pengurus_lain(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->firstOrFail();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->firstOrFail();

        $notifKetua = Notification::create([
            'user_id' => $ketuaRt->id,
            'judul' => 'Notif Rahasia Ketua',
            'isi' => 'Hanya untuk Ketua RT',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($sekretaris)->postJson("/admin/api/notifikasi/{$notifKetua->id}/baca");

        $response->assertForbidden();
        $this->assertFalse((bool) $notifKetua->fresh()->is_read);
    }

    /**
     * TC-154: Warga fetch notifikasi in-app portal warga
     */
    public function test_tc_154_warga_fetch_notifikasi_in_app_portal_warga(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Surat Pengantar Disetujui',
            'isi' => 'Surat pengantar Anda telah disetujui.',
            'url' => '/warga/surat',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($warga)->getJson('/warga/notifikasi');

        $response->assertOk()
            ->assertJsonStructure([
                'unread_count',
                'notifications' => [
                    '*' => ['id', 'judul', 'isi', 'url', 'is_read', 'time_ago'],
                ],
            ]);

        $this->assertGreaterThanOrEqual(1, $response->json('unread_count'));
    }

    /**
     * TC-155: Warga tandai 1 notifikasi telah dibaca
     */
    public function test_tc_155_warga_tandai_1_notifikasi_telah_dibaca(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        $notif = Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Tagihan Baru',
            'isi' => 'Tagihan iuran bulan ini sudah terbit.',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($warga)->postJson("/warga/notifikasi/{$notif->id}/baca");

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertTrue((bool) $notif->fresh()->is_read);
    }

    /**
     * TC-156: Warga dilarang baca notifikasi warga lain
     */
    public function test_tc_156_warga_dilarang_baca_notifikasi_warga_lain(): void
    {
        $wargaA = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $otherUser = User::create([
            'name' => 'Warga Lain',
            'email' => 'wargalain@simlibas.com',
            'password' => bcrypt('password'),
        ]);

        $notifOther = Notification::create([
            'user_id' => $otherUser->id,
            'judul' => 'Pribadi Lain',
            'isi' => 'Pesan privat.',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($wargaA)->postJson("/warga/notifikasi/{$notifOther->id}/baca");

        $response->assertForbidden();
        $this->assertFalse((bool) $notifOther->fresh()->is_read);
    }

    /**
     * TC-157: Warga tandai seluruh notifikasinya dibaca
     */
    public function test_tc_157_warga_tandai_seluruh_notifikasinya_dibaca(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif Warga 1',
            'isi' => 'Deskripsi 1',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif Warga 2',
            'isi' => 'Deskripsi 2',
            'is_read' => false,
            'kanal' => 'web',
        ]);

        $response = $this->actingAs($warga)->postJson('/warga/notifikasi/baca-semua');

        $response->assertOk()
            ->assertJson(['success' => true]);

        $unreadCount = Notification::where('user_id', $warga->id)->unread()->count();
        $this->assertEquals(0, $unreadCount);
    }

    /**
     * TC-158: Uji SQL Injection pada seluruh input search
     */
    public function test_tc_158_uji_sql_injection_pada_seluruh_input_search(): void
    {
        $sqlPayload = "' OR '1'='1' --";

        $responseUmkm = $this->get('/katalog-umkm?search=' . urlencode($sqlPayload));
        $responseUmkm->assertOk();

        $responseVerify = $this->get('/verifikasi-surat?token=' . urlencode($sqlPayload));
        $responseVerify->assertOk();
    }

    /**
     * TC-159: Uji XSS (Cross-Site Scripting) pada form textarea
     */
    public function test_tc_159_uji_xss_pada_form_textarea(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();

        $xssPayload = "<script>alert('XSS-TEST-ALERT')</script>";

        $response = $this->actingAs($warga)->post('/warga/pengaduan', [
            'judul' => 'Pengaduan ' . $xssPayload,
            'kategori' => 'keamanan',
            'deskripsi' => 'Deskripsi keluhan warga RT 15.',
            'is_anonim' => false,
        ]);

        $response->assertRedirect('/warga/pengaduan');

        $this->assertDatabaseHas('complaints', [
            'judul' => 'Pengaduan ' . $xssPayload,
        ]);

        $pageResponse = $this->actingAs($warga)->get('/warga/pengaduan');
        $pageResponse->assertOk();
        // Memastikan tidak dieksekusi sebagai tag script mentah tanpa escape
        $pageResponse->assertDontSee($xssPayload, false);
        $pageResponse->assertSee('&lt;script&gt;', false);
    }

    /**
     * TC-160: Uji perlindungan CSRF token pada POST request
     */
    public function test_tc_160_uji_perlindungan_csrf_token_pada_post_request(): void
    {
        $middleware = new class(app(), app('encrypter')) extends ValidateCsrfToken {
            protected function runningUnitTests() {
                return false;
            }
        };

        $request = Request::create('/masuk', 'POST', [
            'login' => 'warga',
            'password' => 'password',
        ]);
        $session = app('session')->driver();
        $session->setId('test-session');
        $session->start();
        $session->put('_token', 'token-asli-sistem');
        $request->setLaravelSession($session);

        // Tanpa token CSRF yang cocok, harus melempar TokenMismatchException (HTTP 419)
        $this->expectException(TokenMismatchException::class);
        $middleware->handle($request, function () {
            return response('OK');
        });
    }

    /**
     * TC-161: Uji upload berkas ilegal (PHP/eksekusi)
     */
    public function test_tc_161_uji_upload_berkas_ilegal(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $household = $warga->resident->household;
        $feeType = FeeType::firstOrFail();

        $invoice = Invoice::create([
            'household_id' => $household->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 11,
            'periode_tahun' => 2030,
            'jumlah' => 35000,
            'status' => 'belum_bayar',
        ]);

        $illegalFile = UploadedFile::fake()->create('exploit.php', 10, 'application/x-php');

        $response = $this->actingAs($warga)->post("/warga/iuran/{$invoice->id}/bayar", [
            'jumlah' => 35000,
            'metode' => 'transfer',
            'bukti' => $illegalFile,
        ]);

        $response->assertSessionHasErrors('bukti');
    }

    /**
     * TC-162: Uji pembatasan ukuran file maksimal 2MB
     */
    public function test_tc_162_uji_pembatasan_ukuran_file_maksimal_2mb(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $household = $warga->resident->household;
        $feeType = FeeType::firstOrFail();

        $invoice = Invoice::create([
            'household_id' => $household->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 12,
            'periode_tahun' => 2030,
            'jumlah' => 35000,
            'status' => 'belum_bayar',
        ]);

        // Upload gambar berukuran 3000KB (> 2048KB)
        $largeFile = UploadedFile::fake()->image('bukti_besar.jpg')->size(3000);

        $response = $this->actingAs($warga)->post("/warga/iuran/{$invoice->id}/bayar", [
            'jumlah' => 35000,
            'metode' => 'transfer',
            'bukti' => $largeFile,
        ]);

        $response->assertSessionHasErrors('bukti');
    }

    /**
     * TC-163: Pengguna memiliki multi-role (Koor + Warga)
     */
    public function test_tc_163_pengguna_memiliki_multi_role_koor_dan_warga(): void
    {
        $koorSampah = User::where('email', 'banksampah@simlibas.com')->firstOrFail();

        $this->assertTrue($koorSampah->hasRole('koordinator_bank_sampah'));
        $this->assertTrue($koorSampah->hasRole('warga'));

        // Akses panel admin filament
        $responseAdmin = $this->actingAs($koorSampah)->get('/admin');
        $this->assertTrue(in_array($responseAdmin->status(), [200, 302]));

        // Akses portal mandiri warga
        $responseWarga = $this->actingAs($koorSampah)->get('/warga/dashboard');
        $responseWarga->assertOk();
    }

    /**
     * TC-164: Isolasi horizontal data KK antar-warga
     */
    public function test_tc_164_isolasi_horizontal_data_kk_antar_warga(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $householdWarga = $warga->resident->household;

        // Buat household lain
        $otherHousehold = Household::create([
            'no_kk' => '1571000000009999',
            'alamat' => 'Jl. Lingkungan Lain No. 99',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
        ]);

        $otherResident = Resident::create([
            'household_id' => $otherHousehold->id,
            'id_warga' => 'W-9999',
            'nik' => '1571000000009999',
            'nama' => 'Warga Keluarga Lain Rahasia',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1985-01-01',
            'agama' => 'Islam',
            'status_perkawinan' => 'kawin',
            'pekerjaan' => 'Swasta',
            'status_kependudukan' => 'aktif',
            'hubungan_keluarga' => 'kepala_keluarga',
            'is_kepala_keluarga' => true,
        ]);

        $response = $this->actingAs($warga)->get('/warga/kartu-keluarga');

        $response->assertOk()
            ->assertSee($householdWarga->no_kk)
            ->assertDontSee($otherHousehold->no_kk)
            ->assertDontSee('Warga Keluarga Lain Rahasia');
    }

    /**
     * TC-165: Isolasi warga status pindah/meninggal
     */
    public function test_tc_165_isolasi_warga_status_pindah_meninggal(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->firstOrFail();
        $household = $warga->resident->household;
        $letterType = LetterType::firstOrFail();

        // Buat warga dengan status pindah/meninggal di KK yang sama
        $movedResident = Resident::create([
            'household_id' => $household->id,
            'id_warga' => 'W-8888',
            'nik' => '1571000000008888',
            'nama' => 'Warga Telah Pindah Keluar',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1990-01-01',
            'agama' => 'Islam',
            'status_perkawinan' => 'kawin',
            'pekerjaan' => 'Swasta',
            'status_kependudukan' => 'pindah',
            'hubungan_keluarga' => 'anak',
            'is_kepala_keluarga' => false,
        ]);

        $response = $this->actingAs($warga)->post('/warga/surat', [
            'letter_type_id' => $letterType->id,
            'resident_id' => $movedResident->id,
            'keperluan' => 'Permohonan surat warga sudah pindah',
        ]);

        $response->assertSessionHasErrors('resident_id');

        $this->assertDatabaseMissing('letters', [
            'resident_id' => $movedResident->id,
            'keperluan' => 'Permohonan surat warga sudah pindah',
        ]);
    }
}
