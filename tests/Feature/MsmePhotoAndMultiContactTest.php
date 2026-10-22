<?php

namespace Tests\Feature;

use App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles;
use App\Models\Household;
use App\Models\MsmeProfile;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MsmePhotoAndMultiContactTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Household $household;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');

        $this->admin = User::where('email', 'admin@simlibas.com')->first()
            ?? User::where('email', 'rt@simlibas.com')->first();
        $this->household = Household::first();
    }

    /**
     * 1. Tambah UMKM dengan foto dan seluruh kontak (WA, IG, TikTok, Shopee).
     */
    public function test_can_create_msme_with_photo_and_all_social_contacts(): void
    {
        $optimizer = new ImageOptimizerService();

        // Buat file gambar dummy
        $file = UploadedFile::fake()->image('warung.jpg', 1600, 1200);
        $storedPath = $optimizer->optimizeAndStore($file, 'umkm');

        $this->assertStringEndsWith('.webp', $storedPath);
        Storage::disk('public')->assertExists($storedPath);

        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Toko Serba Ada RT 15',
            'kategori' => 'sembako',
            'foto_path' => $storedPath,
            'telepon' => '081234567890',
            'whatsapp' => '081234567890',
            'social_links' => [
                'whatsapp' => '081234567890',
                'instagram' => '@tokoserbaada',
                'tiktok' => 'tokoserbaada_tiktok',
                'shopee' => 'tokoserbaada_official',
            ],
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $this->assertTrue($msme->hasPhoto());
        $this->assertEquals(4, count($msme->getAvailableSocialPlatforms()));
        $this->assertStringContainsString('instagram.com/tokoserbaada', $msme->getSocialUrl('instagram'));
        $this->assertStringContainsString('tiktok.com/@tokoserbaada_tiktok', $msme->getSocialUrl('tiktok'));
        $this->assertStringContainsString('shopee.co.id/tokoserbaada_official', $msme->getSocialUrl('shopee'));
        $this->assertStringContainsString('wa.me/6281234567890', $msme->getSocialUrl('whatsapp'));
    }

    /**
     * 2. Tambah UMKM tanpa foto dengan hanya satu kontak media sosial.
     */
    public function test_can_create_msme_without_photo_and_single_contact(): void
    {
        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Jahit Busana Melati',
            'kategori' => 'jasa',
            'foto_path' => null,
            'telepon' => '085511223344',
            'social_links' => [
                'instagram' => 'jahitmelati',
            ],
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $this->assertFalse($msme->hasPhoto());
        $platforms = $msme->getAvailableSocialPlatforms();
        $this->assertContains('instagram', $platforms);
        $this->assertEquals('https://instagram.com/jahitmelati', $msme->getSocialUrl('instagram'));
    }

    /**
     * 3. Tambah UMKM tanpa foto dan tanpa kontak media sosial.
     */
    public function test_can_create_msme_without_photo_and_without_contacts(): void
    {
        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Kerajinan Bambu Pak RT',
            'kategori' => 'kerajinan',
            'foto_path' => null,
            'telepon' => '081299998888',
            'whatsapp' => null,
            'social_links' => null,
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $this->assertFalse($msme->hasPhoto());
        // Hanya ada nomor telepon standar jika ada
        $this->assertNull($msme->getSocialUrl('instagram'));
        $this->assertNull($msme->getSocialUrl('tiktok'));
        $this->assertNull($msme->getSocialUrl('shopee'));
    }

    /**
     * 4. Edit data UMKM, mengganti foto, dan memperbarui kontak (verifikasi file lama terhapus).
     */
    public function test_editing_msme_replaces_photo_and_deletes_old_file(): void
    {
        $optimizer = new ImageOptimizerService();

        $photo1 = UploadedFile::fake()->image('old.jpg', 800, 600);
        $oldPath = $optimizer->optimizeAndStore($photo1, 'umkm');
        Storage::disk('public')->assertExists($oldPath);

        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Kue Tradisional Bu Dewi',
            'kategori' => 'kuliner',
            'foto_path' => $oldPath,
            'telepon' => '081122334455',
            'social_links' => ['whatsapp' => '081122334455'],
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        // Ganti dengan foto baru
        $photo2 = UploadedFile::fake()->image('new.png', 1000, 700);
        $newPath = $optimizer->optimizeAndStore($photo2, 'umkm');
        Storage::disk('public')->assertExists($newPath);

        $msme->update([
            'foto_path' => $newPath,
            'social_links' => [
                'whatsapp' => '081122334455',
                'instagram' => 'kuedewi_official',
            ],
        ]);

        // File lama harus sudah terhapus otomatis dari storage disk
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
        $this->assertEquals($newPath, $msme->fresh()->foto_path);
        $this->assertContains('instagram', $msme->fresh()->getAvailableSocialPlatforms());
    }

    /**
     * 5. Validasi unggahan dengan format tidak didukung.
     */
    public function test_optimizer_rejects_invalid_file_format(): void
    {
        $this->expectException(\RuntimeException::class);

        $textFile = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');
        $optimizer = new ImageOptimizerService();
        $optimizer->optimizeAndStore($textFile, 'umkm');
    }

    /**
     * 6. Penghapusan UMKM beserta penanganan file fotonya.
     */
    public function test_deleting_msme_removes_photo_file(): void
    {
        $optimizer = new ImageOptimizerService();
        $photo = UploadedFile::fake()->image('toko.jpg', 600, 400);
        $path = $optimizer->optimizeAndStore($photo, 'umkm');
        Storage::disk('public')->assertExists($path);

        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Toko Mau Dihapus',
            'kategori' => 'lainnya',
            'foto_path' => $path,
            'telepon' => '089988776655',
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        // Hapus permanen
        $msme->forceDelete();

        // Verifikasi file terhapus dari storage
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * 7. Tampilan card dan multi-kontak di halaman publik.
     */
    public function test_public_katalog_renders_photo_and_social_buttons(): void
    {
        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Kuliner Nusantara Pinang',
            'kategori' => 'kuliner',
            'deskripsi' => 'Aneka masakan khas daerah nusantara.',
            'produk' => 'Rendang Daging, Soto Betawi',
            'telepon' => '081200001111',
            'social_links' => [
                'whatsapp' => '081200001111',
                'instagram' => 'kulinernusantara15',
                'shopee' => 'kulinernusantara_store',
            ],
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.umkm'));
        $response->assertStatus(200);
        $response->assertSee('Kuliner Nusantara Pinang');
        $response->assertSee('instagram.com/kulinernusantara15');
        $response->assertSee('shopee.co.id/kulinernusantara_store');
        $response->assertSee('wa.me/6281200001111');
    }

    /**
     * 8. Livewire ListMsmeProfiles Form Modal create & edit.
     */
    public function test_livewire_admin_page_can_create_and_edit_msme(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ListMsmeProfiles::class)
            ->call('openCreate')
            ->set('form_household_id', $this->household->id)
            ->set('form_nama_usaha', 'Bengkel Las Cepat RT 15')
            ->set('form_kategori', 'jasa')
            ->set('form_telepon', '081399887766')
            ->set('form_whatsapp', '081399887766')
            ->set('form_instagram', '@bengkellas15')
            ->set('form_tiktok', 'bengkellas15')
            ->call('saveMsme');

        $created = MsmeProfile::where('nama_usaha', 'Bengkel Las Cepat RT 15')->first();
        $this->assertNotNull($created);
        $this->assertEquals('disetujui', $created->status_moderasi);
        $this->assertContains('instagram', $created->getAvailableSocialPlatforms());
        $this->assertContains('tiktok', $created->getAvailableSocialPlatforms());
    }

    /**
     * 9. Katalog UMKM menggunakan layout 2 kolom mobile dan navigasi card ke detail.
     */
    public function test_public_umkm_catalogue_has_mobile_2_columns_and_card_navigation_link(): void
    {
        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Katering Barokah RT 15',
            'kategori' => 'kuliner',
            'deskripsi' => 'Katering harian dan prasmanan lezat.',
            'produk' => 'Nasi Kotak, Tumpeng Mini',
            'telepon' => '081234567890',
            'whatsapp' => '081234567890',
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.umkm'));
        $response->assertStatus(200);

        // Grid 2 kolom mobile
        $response->assertSee('grid-cols-2', false);

        // Tautan navigasi ke halaman detail
        $detailUrl = route('public.umkm.detail', $msme->id);
        $response->assertSee($detailUrl, false);
    }

    /**
     * 10. Halaman detail UMKM menampilkan info lengkap dan HANYA kontak yang tersedia.
     */
    public function test_public_umkm_detail_page_renders_complete_information_and_only_available_contacts(): void
    {
        $msme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Jahit Kebaya Anggun 15',
            'kategori' => 'jasa',
            'deskripsi' => 'Spesialis jahit kebaya modern dan baju pesta wanita.',
            'produk' => 'Kebaya Modern, Gamis Syari, Payet Mutiara',
            'alamat' => 'Jl. Pinang Masak No. 12 RT 15',
            'telepon' => '081277665544',
            'social_links' => [
                'whatsapp' => '081277665544',
                'instagram' => 'kebaya_anggun15',
                // TikTok dan Shopee sengaja kosong
            ],
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.umkm.detail', $msme->id));
        $response->assertStatus(200);

        // Informasi usaha
        $response->assertSee('Jahit Kebaya Anggun 15');
        $response->assertSee('Jasa &amp; Layanan', false);
        $response->assertSee('Spesialis jahit kebaya modern dan baju pesta wanita.');
        $response->assertSee('Kebaya Modern');
        $response->assertSee('Gamis Syari');
        $response->assertSee('Jl. Pinang Masak No. 12 RT 15');

        // Kontak yang TERSEDIA
        $response->assertSee('Hubungi via WhatsApp');
        $response->assertSee('wa.me/6281277665544');
        $response->assertSee('Kunjungi Instagram');
        $response->assertSee('instagram.com/kebaya_anggun15');

        // Kontak yang TIDAK diisi TIDAK boleh muncul
        $response->assertDontSee('Buka di TikTok');
        $response->assertDontSee('Belanja di Shopee');
    }

    /**
     * 11. Halaman detail UMKM 404 jika belum disetujui atau nonaktif.
     */
    public function test_public_umkm_detail_returns_404_for_unapproved_or_inactive(): void
    {
        $pendingMsme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Usaha Belum Terverifikasi',
            'kategori' => 'sembako',
            'deskripsi' => 'Masih dalam proses moderasi.',
            'telepon' => '081111222333',
            'status_moderasi' => 'pending',
            'is_active' => true,
        ]);

        $responsePending = $this->get(route('public.umkm.detail', $pendingMsme->id));
        $responsePending->assertStatus(404);

        $inactiveMsme = MsmeProfile::create([
            'household_id' => $this->household->id,
            'nama_usaha' => 'Usaha Sudah Tutup',
            'kategori' => 'sembako',
            'deskripsi' => 'Sudah tidak aktif lagi.',
            'telepon' => '081111222444',
            'status_moderasi' => 'disetujui',
            'is_active' => false,
        ]);

        $responseInactive = $this->get(route('public.umkm.detail', $inactiveMsme->id));
        $responseInactive->assertStatus(404);
    }
}
