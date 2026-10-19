<?php

namespace Tests\Feature\Scenarios;

use App\Models\Letter;
use App\Models\MsmeProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Modul02PublicScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * TC-016: Menampilkan informasi profil wilayah RT 15 di beranda publik
     */
    public function test_tc_016_beranda_publik_menampilkan_profil_wilayah(): void
    {
        $response = $this->get(route('public.home'));

        $response->assertOk();
        $response->assertSee('SIM-LiBas');
        $response->assertSee('RT 15');
        $response->assertSee('Pematang Sulur');
    }

    /**
     * TC-017: Menampilkan agregasi statistik ringkas publik
     */
    public function test_tc_017_beranda_publik_menampilkan_statistik_ringkas(): void
    {
        $response = $this->get(route('public.home'));

        $response->assertOk();
        $response->assertSee('Kartu Keluarga');
        $response->assertSee('Warga');
    }

    /**
     * TC-018: Menampilkan direktori seluruh layanan warga
     */
    public function test_tc_018_direktori_seluruh_layanan_publik(): void
    {
        $response = $this->get(route('public.layanan.index'));

        $response->assertOk();
        $response->assertSee('Layanan');
    }

    /**
     * TC-019: Menampilkan detail rincian persyaratan layanan warga
     */
    public function test_tc_019_detail_rincian_persyaratan_layanan(): void
    {
        $response = $this->get(route('public.layanan.detail', ['slug' => 'surat-pengantar']));

        $response->assertOk();
        $response->assertSee('Surat Pengantar');
    }

    /**
     * TC-020: Katalog UMKM hanya menampilkan UMKM yang berstatus disetujui
     */
    public function test_tc_020_katalog_umkm_hanya_menampilkan_yang_disetujui(): void
    {
        $approvedUmkm = MsmeProfile::where('status_moderasi', 'disetujui')->where('is_active', true)->first();
        $this->assertNotNull($approvedUmkm);

        $pendingUmkm = MsmeProfile::create([
            'household_id' => $approvedUmkm->household_id,
            'nama_usaha' => 'Usaha Gelap Pending',
            'produk' => 'Barang Rahasia',
            'telepon' => '08111222333',
            'status_moderasi' => 'pending',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.umkm'));

        $response->assertOk();
        $response->assertSee($approvedUmkm->nama_usaha);
        $response->assertDontSee($pendingUmkm->nama_usaha);
    }

    /**
     * TC-021: Pencarian dan filter kategori UMKM publik
     */
    public function test_tc_021_pencarian_dan_filter_kategori_umkm(): void
    {
        $response = $this->get(route('public.umkm', ['q' => 'Pempek']));

        $response->assertOk();
        $response->assertSee('Pempek');
    }

    /**
     * TC-022: Menampilkan edukasi dan daftar harga komoditas bank sampah
     */
    public function test_tc_022_edukasi_dan_harga_komoditas_bank_sampah(): void
    {
        $response = $this->get(route('public.bank-sampah'));

        $response->assertOk();
        $response->assertSee('Bank Sampah');
        $response->assertSee('Pinang Masak Lestari');
    }

    /**
     * TC-023: Verifikasi surat QR code valid (kepatuhan UU PDP: TANPA NIK)
     */
    public function test_tc_023_verifikasi_surat_qr_valid_tanpa_nik(): void
    {
        $letter = Letter::where('status', 'disetujui')->first();
        $this->assertNotNull($letter);
        $this->assertNotNull($letter->qr_token);

        $response = $this->get(route('public.verify-letter', ['token' => $letter->qr_token]));

        $response->assertOk();
        $response->assertSee('Surat Pengantar');
        $response->assertSee($letter->resident->nama);
        // Sesuai UU PDP: NIK asli 16 digit tidak boleh terpapar secara telanjang di halaman publik
        $response->assertDontSee($letter->resident->nik);
    }

    /**
     * TC-024: Verifikasi surat dengan token tidak valid / palsu
     */
    public function test_tc_024_verifikasi_surat_token_invalid(): void
    {
        $response = $this->get(route('public.verify-letter', ['token' => 'token-palsu-12345']));

        $response->assertOk();
        $response->assertSee('Surat Tidak Terdaftar');
    }
}
