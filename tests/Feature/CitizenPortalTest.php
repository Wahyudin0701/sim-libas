<?php

namespace Tests\Feature;

use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Beranda publik dapat diakses dengan sukses.
     */
    public function test_public_home_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Satu Portal, Banyak Layanan');
        $response->assertSee('SIM-');
        $response->assertSee('LiBas');
        $response->assertSee('Kampung Digital Pinang Masak Lestari');
    }

    /**
     * Katalog UMKM warga dapat diakses.
     */
    public function test_public_umkm_catalogue_is_accessible(): void
    {
        $response = $this->get('/katalog-umkm');

        $response->assertStatus(200);
        $response->assertSee('Katalog UMKM Warga RT 15');
        $response->assertSee('Pempek & Tekwan Pinang Masak 15');
        $response->assertSee('Hubungi via WhatsApp');
    }

    /**
     * Katalog UMKM dapat difilter menggunakan query search SSR dan menjaga atribut input.
     */
    public function test_public_umkm_search_with_ssr_parameter(): void
    {
        $response = $this->get('/katalog-umkm?search=Pempek');

        $response->assertStatus(200);
        $response->assertSee('Pempek & Tekwan Pinang Masak 15');
        $response->assertSee('name="search"', false);
        $response->assertSee('value="Pempek"', false);
    }

    /**
     * Halaman Bank Sampah publik dapat diakses dan menampilkan daftar harga sampah.
     */
    public function test_public_bank_sampah_page_is_accessible(): void
    {
        $response = $this->get('/bank-sampah');

        $response->assertStatus(200);
        $response->assertSee('Bank Sampah Pinang Masak Lestari');
        $response->assertSee('Daftar Harga Beli Sampah Terkini');
        $response->assertSee('Kardus / Karton Coklat');
    }

    /**
     * Halaman Kontak & Sekretariat RT publik dapat diakses.
     */
    public function test_public_contact_page_is_accessible(): void
    {
        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertSee('Kontak & Sekretariat RT 15');
        $response->assertSee('0812-7400-1515');
        $response->assertSee('Pematang Sulur');
    }

    /**
     * Verifikasi surat via QR menampilkan status SAH jika token valid.
     */
    public function test_qr_letter_verification_shows_valid_status_for_approved_letter(): void
    {
        $resident = Resident::first();
        $type = LetterType::first();

        $letter = Letter::create([
            'nomor_surat' => '001/RT.15/PMS/X/2026',
            'letter_type_id' => $type->id,
            'resident_id' => $resident->id,
            'keperluan' => 'Pengurusan KTP',
            'status' => 'disetujui',
            'qr_token' => 'VALID_TEST_TOKEN_123',
            'tanggal_disetujui' => now(),
        ]);

        $response = $this->get('/verifikasi-surat/VALID_TEST_TOKEN_123');

        $response->assertStatus(200);
        $response->assertSee('Dokumen Asli Rukun Tetangga 15');
        $response->assertSee('001/RT.15/PMS/X/2026');
        $response->assertSee($resident->nama);
        // Pastikan NIK TIDAK ditampilkan sesuai UU PDP
        $response->assertDontSee($resident->nik);
    }

    /**
     * Verifikasi surat menampilkan status TIDAK DITEMUKAN jika token salah.
     */
    public function test_qr_letter_verification_shows_not_found_for_invalid_token(): void
    {
        $response = $this->get('/verifikasi-surat/TOKEN_PALSU_999');

        $response->assertStatus(200);
        $response->assertSee('DOKUMEN TIDAK DITEMUKAN / TIDAK SAH');
        $response->assertSee('TOKEN_PALSU_999');
    }

    /**
     * Warga dapat login ke portal warga.
     */
    public function test_citizen_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/masuk', [
            'login' => 'warga.dedi@simlibas.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/warga/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Warga dapat login menggunakan 16 digit NIK resmi.
     */
    public function test_citizen_can_login_with_nik(): void
    {
        $resident = Resident::where('nama', 'Dedi Kurniawan')->first();

        $response = $this->post('/masuk', [
            'login' => $resident->nik,
            'password' => 'password',
        ]);

        $response->assertRedirect('/warga/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals(auth()->id(), $resident->user_id);
    }

    /**
     * Anggota keluarga (bukan kepala keluarga) seperti Rizky dapat login dengan NIK-nya.
     */
    public function test_family_member_can_login_with_nik(): void
    {
        $resident = Resident::where('nama', 'like', '%Rizky%')->first();
        $this->assertNotNull($resident);
        $this->assertNotNull($resident->user_id);

        $response = $this->post('/masuk', [
            'login' => $resident->nik,
            'password' => 'password',
        ]);

        $response->assertRedirect('/warga/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals(auth()->id(), $resident->user_id);
    }

    /**
     * Warga yang login dapat mengakses dashboard warga.
     */
    public function test_citizen_can_access_dashboard(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/warga/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Halo, '.$warga->name);
        $response->assertSee('Tabungan Sampah');
        $response->assertSee('Nomor Kartu Keluarga');
        $response->assertSee(route('warga.kartu-keluarga'));
        $response->assertSee('id="user-menu-button"', false);
        $response->assertSee('id="user-menu-dropdown"', false);
        $response->assertSee('Profil Saya');
        $response->assertSee(route('warga.profile'));
        $response->assertSee(route('citizen.logout'));
        $response->assertSee('Keluar');
    }

    /**
     * Warga dapat mengakses halaman khusus detail Kartu Keluarga.
     */
    public function test_citizen_can_access_kartu_keluarga_page(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/warga/kartu-keluarga');

        $response->assertStatus(200);
        $response->assertSee('Kartu Keluarga:');
        $response->assertSee('Daftar Anggota Keluarga Terdaftar');
        $response->assertSee('Nomor Kartu Keluarga (16 Digit)');
        $response->assertSee('Status Tempat Tinggal');
        $response->assertSee('Dedi Kurniawan');
        $response->assertSee('Siti Aminah');
    }

    /**
     * Warga dapat mengakses halaman khusus profil akun dan biodata.
     */
    public function test_citizen_can_access_profile_page(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/warga/profil');

        $response->assertStatus(200);
        $response->assertSee('Profil Akun Warga');
        $response->assertSee('Kredensial Akun Portal');
        $response->assertSee('Data Kependudukan Resmi');
        $response->assertSee('Dedi Kurniawan');
        $response->assertSee('warga.dedi@simlibas.com');
        $response->assertSee('Warga Aktif');
    }

    /**
     * Warga dapat mengajukan surat pengantar baru.
     */
    public function test_citizen_can_submit_letter_request(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $resident = Resident::where('user_id', $warga->id)->first();
        $type = LetterType::first();

        $response = $this->actingAs($warga)->post('/warga/surat', [
            'letter_type_id' => $type->id,
            'resident_id' => $resident->id,
            'keperluan' => 'Permohonan surat keterangan domisili untuk melamar pekerjaan',
        ]);

        $response->assertRedirect(route('warga.surat'));
        $this->assertDatabaseHas('letters', [
            'letter_type_id' => $type->id,
            'resident_id' => $resident->id,
            'status' => 'diajukan',
        ]);
    }

    /**
     * Warga dapat membuka lembar cetak surat pengantar resmi yang telah disetujui.
     */
    public function test_citizen_can_print_approved_letter(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $resident = Resident::where('user_id', $warga->id)->first();
        $type = LetterType::first();

        $letter = Letter::create([
            'nomor_surat' => '005/RT.15/PMS/X/2026',
            'letter_type_id' => $type->id,
            'resident_id' => $resident->id,
            'keperluan' => 'Keperluan melamar pekerjaan',
            'status' => 'disetujui',
            'qr_token' => 'VALID_TOKEN_PRINT_TEST',
            'tanggal_disetujui' => now(),
        ]);

        $response = $this->actingAs($warga)->get(route('letters.print', $letter));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        $htmlResponse = $this->actingAs($warga)->get(route('letters.print', ['letter' => $letter, 'format' => 'html']));
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('RUKUN TETANGGA 15');
        $htmlResponse->assertSee('005/RT.15/PMS/X/2026');
        $htmlResponse->assertSee(strtoupper($resident->nama));
        $htmlResponse->assertSee($resident->nik);
        $htmlResponse->assertSee('Keperluan melamar pekerjaan');
    }

    /**
     * Warga dapat mengirim laporan pengaduan.
     */
    public function test_citizen_can_submit_complaint(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->post('/warga/pengaduan', [
            'judul' => 'Lampu jalan padam di blok A',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Depan rumah No 15',
            'deskripsi' => 'Lampu penerangan jalan umum mati sejak kemarin malam sehingga jalan gelap.',
            'is_anonim' => 1,
        ]);

        $response->assertRedirect(route('warga.pengaduan'));
        $this->assertDatabaseHas('complaints', [
            'user_id' => $warga->id,
            'judul' => 'Lampu jalan padam di blok A',
            'is_anonim' => true,
            'status' => 'diterima',
        ]);
    }

    /**
     * Warga dapat mengakses halaman riwayat pengaduan warga.
     */
    public function test_citizen_can_access_complaints_page(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/warga/pengaduan');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pengaduan Lingkungan');
        $response->assertSee('Daftar Laporan Pengaduan');
        $response->assertSee('Tulis Laporan Baru');
    }

    /**
     * Warga dapat mengakses halaman iuran bulanan dan tagihan.
     */
    public function test_citizen_can_access_invoices_page(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/warga/iuran');

        $response->assertStatus(200);
        $response->assertSee('Iuran Bulanan');
        $response->assertSee('Daftar Tagihan Iuran');
    }

    /**
     * Warga dapat mengonfirmasi pembayaran iuran.
     */
    public function test_citizen_can_submit_invoice_payment_confirmation(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $household = $warga->resident?->household;
        $feeType = FeeType::first();

        $invoice = Invoice::firstOrCreate(
            ['household_id' => $household->id, 'fee_type_id' => $feeType->id, 'periode_bulan' => 10, 'periode_tahun' => 2026],
            ['jumlah' => 25000, 'status' => 'belum_bayar']
        );
        $invoice->update(['status' => 'belum_bayar']);
        $invoice->payments()->delete();

        $response = $this->actingAs($warga)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => $invoice->jumlah,
            'metode' => 'transfer',
            'catatan' => 'Uji konfirmasi bayar iuran transfer',
        ]);

        $response->assertRedirect(route('warga.iuran'));
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'metode' => 'transfer',
            'status' => 'pending',
        ]);
    }

    /**
     * Pengurus atau Admin yang login lewat halaman masuk warga diarahkan ke admin panel tanpa error.
     */
    public function test_admin_login_on_citizen_portal_redirects_to_admin(): void
    {
        $response = $this->post('/masuk', [
            'login' => 'admin@simlibas.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    /**
     * Warga dapat memperbarui alamat email akun dengan memasukkan kata sandi saat ini yang benar.
     */
    public function test_citizen_can_update_email_with_valid_password(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->put(route('warga.profile.email'), [
            'email' => 'dedi.kurniawan.baru@gmail.com',
            'current_password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $warga->id,
            'email' => 'dedi.kurniawan.baru@gmail.com',
        ]);

        if ($warga->resident) {
            $this->assertEquals('dedi.kurniawan.baru@gmail.com', $warga->resident->fresh()->email);
        }
    }

    /**
     * Warga gagal memperbarui email jika kata sandi saat ini salah.
     */
    public function test_citizen_cannot_update_email_with_invalid_password(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->put(route('warga.profile.email'), [
            'email' => 'dedi.kurniawan.gagal@gmail.com',
            'current_password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertDatabaseMissing('users', [
            'email' => 'dedi.kurniawan.gagal@gmail.com',
        ]);
    }

    /**
     * Warga gagal memperbarui email jika email baru sudah terdaftar pada akun lain.
     */
    public function test_citizen_cannot_update_email_with_duplicate_email(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->put(route('warga.profile.email'), [
            'email' => 'admin@simlibas.com',
            'current_password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Warga dapat memperbarui kata sandi akun dan login menggunakan kata sandi baru.
     */
    public function test_citizen_can_update_password_and_login_with_new_password(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->put(route('warga.profile.password'), [
            'current_password' => 'password',
            'password' => 'new-secret-password-123',
            'password_confirmation' => 'new-secret-password-123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Logout dan login kembali menggunakan kata sandi baru
        auth()->logout();

        $loginResponse = $this->post('/masuk', [
            'login' => 'warga.dedi@simlibas.com',
            'password' => 'new-secret-password-123',
        ]);

        $loginResponse->assertRedirect('/warga/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals($warga->id, auth()->id());
    }

    /**
     * Warga gagal memperbarui kata sandi jika kata sandi saat ini salah atau konfirmasi tidak cocok.
     */
    public function test_citizen_cannot_update_password_with_invalid_credentials(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        // 1. Kata sandi lama salah
        $response1 = $this->actingAs($warga)->put(route('warga.profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secret-password-123',
            'password_confirmation' => 'new-secret-password-123',
        ]);
        $response1->assertSessionHasErrors('current_password_password');

        // 2. Konfirmasi password tidak cocok
        $response2 = $this->actingAs($warga)->put(route('warga.profile.password'), [
            'current_password' => 'password',
            'password' => 'new-secret-password-123',
            'password_confirmation' => 'different-password-123',
        ]);
        $response2->assertSessionHasErrors('password');
    }

    /**
     * Warga dapat memperbarui nomor telepon / WhatsApp dan tersinkronisasi ke data warga.
     */
    public function test_citizen_can_update_phone_number(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->put(route('warga.profile.phone'), [
            'phone' => '0812-9988-7766',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $warga->refresh();
        $this->assertEquals('0812-9988-7766', $warga->phone);

        if ($warga->resident) {
            $warga->resident->refresh();
            $this->assertEquals('0812-9988-7766', $warga->resident->telepon);
        }
    }

    /**
     * Warga gagal memperbarui nomor telepon jika format tidak valid.
     */
    public function test_citizen_cannot_update_phone_with_invalid_format(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        // 1. Karakter alfabet tidak valid
        $response1 = $this->actingAs($warga)->put(route('warga.profile.phone'), [
            'phone' => 'nomor-hp-bukan-angka',
        ]);
        $response1->assertSessionHasErrors('phone');

        // 2. Kosong / required
        $response2 = $this->actingAs($warga)->put(route('warga.profile.phone'), [
            'phone' => '',
        ]);
        $response2->assertSessionHasErrors('phone');
    }

    /**
     * Warga dapat mengambil daftar notifikasi in-app via API JSON.
     */
    public function test_citizen_can_fetch_notifications_via_api(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Uji Notifikasi RT',
            'isi' => 'Isi pesan notifikasi simulasi untuk warga.',
            'kanal' => 'web',
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->getJson(route('warga.notifications'));
        $response->assertOk();
        $response->assertJsonStructure([
            'unread_count',
            'notifications' => [
                '*' => ['id', 'judul', 'isi', 'url', 'is_read', 'time_ago'],
            ],
        ]);
        $this->assertGreaterThanOrEqual(1, $response->json('unread_count'));
    }

    /**
     * Warga dapat menandai notifikasi sebagai sudah dibaca.
     */
    public function test_citizen_can_mark_notification_as_read(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $notif = Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Surat Pengantar Disetujui',
            'isi' => 'Surat pengantar Anda telah disetujui.',
            'kanal' => 'web',
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->postJson(route('warga.notifications.read', $notif));
        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertTrue($notif->fresh()->is_read);
    }

    /**
     * Warga dapat mengakses tampilan halaman penuh Pusat Notifikasi via browser.
     */
    public function test_citizen_can_access_notification_page_via_web(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Surat Pengantar Disetujui',
            'isi' => 'Surat pengantar Anda telah disetujui RT.',
            'url' => route('warga.surat'),
            'kanal' => 'web',
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->get(route('warga.notifications'));
        $response->assertOk();
        $response->assertSee('Pusat Notifikasi');
        $response->assertSee('Semua');
        $response->assertSee('Belum Dibaca');
        $response->assertSee('Surat Pengantar');
        $response->assertSee('Tandai Semua Telah Dibaca');
        $response->assertSee('Surat Pengantar Disetujui');
        $response->assertSee('citizen-topbar-back-btn', false);
        $response->assertSee('warga-page-back-btn', false);
        $response->assertSee('Kembali');
    }

    /**
     * Warga dapat menandai seluruh notifikasi telah dibaca via tombol web.
     */
    public function test_citizen_can_mark_all_notifications_as_read_via_web(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif 1',
            'isi' => 'Isi 1',
            'is_read' => false,
        ]);
        Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif 2',
            'isi' => 'Isi 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->post(route('warga.notifications.read-all'));
        $response->assertRedirect();
        $this->assertEquals(0, Notification::where('user_id', $warga->id)->unread()->count());
    }

    /**
     * Warga dapat membuka notifikasi, otomatis ditandai dibaca, dan diredirect ke URL tujuan.
     */
    public function test_citizen_can_open_notification_and_redirects(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $notif = Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Tagihan Iuran Baru',
            'isi' => 'Tagihan iuran bulan berjalan tersedia.',
            'url' => route('warga.iuran'),
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->get(route('warga.notifications.open', $notif));
        $response->assertRedirect(route('warga.iuran'));
        $this->assertTrue($notif->fresh()->is_read);
    }

    /**
     * Warga dapat membersihkan riwayat notifikasi yang sudah dibaca.
     */
    public function test_citizen_can_clear_read_notifications(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $readNotif = Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif Lama',
            'isi' => 'Isi lama.',
            'is_read' => true,
        ]);
        $unreadNotif = Notification::create([
            'user_id' => $warga->id,
            'judul' => 'Notif Baru',
            'isi' => 'Isi baru.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($warga)->post(route('warga.notifications.clear-read'));
        $response->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $readNotif->id]);
        $this->assertDatabaseHas('notifications', ['id' => $unreadNotif->id]);
    }

    /**
     * Warga tidak dapat membayar tagihan yang sudah lunas.
     */
    public function test_citizen_cannot_pay_invoice_if_already_paid(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $household = $warga->resident?->household;
        $feeType = FeeType::first();

        $invoice = Invoice::firstOrCreate(
            ['household_id' => $household->id, 'fee_type_id' => $feeType->id, 'periode_bulan' => 11, 'periode_tahun' => 2026],
            ['jumlah' => 25000, 'status' => 'lunas']
        );
        $invoice->update(['status' => 'lunas']);

        $response = $this->actingAs($warga)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => 25000,
            'metode' => 'transfer',
        ]);

        $response->assertSessionHasErrors('pembayaran');
    }

    /**
     * Warga tidak dapat mengirim bukti bayar ganda jika bukti sebelumnya masih pending.
     */
    public function test_citizen_cannot_pay_invoice_if_payment_pending(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();
        $household = $warga->resident?->household;
        $feeType = FeeType::first();

        $invoice = Invoice::firstOrCreate(
            ['household_id' => $household->id, 'fee_type_id' => $feeType->id, 'periode_bulan' => 12, 'periode_tahun' => 2026],
            ['jumlah' => 25000, 'status' => 'belum_bayar']
        );
        $invoice->update(['status' => 'belum_bayar']);

        // Buat pembayaran pending
        Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 25000,
            'metode' => 'transfer',
            'tanggal_bayar' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($warga)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => 25000,
            'metode' => 'transfer',
        ]);

        $response->assertSessionHasErrors('pembayaran');
    }

    /**
     * Anggota keluarga (bukan kepala keluarga / istri) hanya dapat mengajukan surat untuk dirinya sendiri.
     */
    public function test_family_member_cannot_apply_letter_for_other_members(): void
    {
        // Ambil warga yang berstatus anak / bukan kepala keluarga
        $childResident = Resident::where('hubungan_keluarga', 'anak')->first();
        if (! $childResident) {
            $this->markTestSkipped('Tidak ada resident berstatus anak.');
        }

        // Pastikan child resident punya user
        $childUser = $childResident->user ?? User::factory()->create([
            'email' => 'anak.uji@simlibas.com',
            'status' => 'aktif',
        ]);
        $childResident->update(['user_id' => $childUser->id, 'is_kepala_keluarga' => false]);

        $letterType = LetterType::first();
        $household = $childResident->household;
        $otherResident = $household->residents->where('id', '!=', $childResident->id)->first();

        if (! $otherResident) {
            $this->markTestSkipped('Tidak ada anggota keluarga lain di KK yang sama.');
        }

        $response = $this->actingAs($childUser)->post(route('warga.surat.store'), [
            'letter_type_id' => $letterType->id,
            'resident_id' => $otherResident->id, // Mencoba ajukan untuk orang lain
            'keperluan' => 'Permohonan surat uji otorisasi status keluarga',
        ]);

        $response->assertSessionHasErrors('resident_id');
    }
}
