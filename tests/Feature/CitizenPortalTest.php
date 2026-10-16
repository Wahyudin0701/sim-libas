<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\LetterType;
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
            'login' => 'warga.dedi@simlibas.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/warga/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Warga yang login dapat mengakses dashboard warga.
     */
    public function test_citizen_can_access_dashboard(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.id')->first();

        $response = $this->actingAs($warga)->get('/warga/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Halo, ' . $warga->name);
        $response->assertSee('Tabungan Sampah');
    }

    /**
     * Warga dapat mengajukan surat pengantar baru.
     */
    public function test_citizen_can_submit_letter_request(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.id')->first();
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
     * Warga dapat mengirim laporan pengaduan.
     */
    public function test_citizen_can_submit_complaint(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.id')->first();

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
     * Pengurus atau Admin yang login lewat halaman masuk warga diarahkan ke admin panel tanpa error.
     */
    public function test_admin_login_on_citizen_portal_redirects_to_admin(): void
    {
        $response = $this->post('/masuk', [
            'login' => 'admin@simlibas.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }
}
