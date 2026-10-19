<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\MsmeProfiles\Pages\CreateMsmeProfile;
use App\Filament\Resources\MsmeProfiles\Pages\EditMsmeProfile;
use App\Models\Household;
use App\Models\MsmeProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 10: UMKM Warga & Katalog Produk
 * Skenario: TC-131 s/d TC-140
 */
class Modul10UmkmKatalogScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $bendahara;
    protected User $koorSampah;
    protected User $koorKeamanan;
    protected User $rwKelurahan;
    protected Household $householdA;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->first();
        $this->koorSampah = User::where('email', 'banksampah@simlibas.id')->first();
        $this->koorKeamanan = User::where('email', 'keamanan@simlibas.id')->first();

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

        $this->householdA = Household::first();
    }

    /**
     * TC-131: Sekretaris mendaftarkan profil usaha UMKM keluarga baru
     */
    public function test_tc_131_ketua_rt_mendaftarkan_profil_umkm(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateMsmeProfile::class)
            ->fillForm([
                'household_id' => $this->householdA->id,
                'nama_usaha' => 'Warung Kopi Barokah RT 15',
                'deskripsi' => 'Menyediakan kopi tubruk dan gorengan hangat.',
                'produk' => 'Kopi Robusta, Pisang Goreng, Bakwan',
                'kategori' => 'kuliner',
                'alamat' => 'Jl. Kenanga Blok A No. 15',
                'telepon' => '081277889900',
                'whatsapp' => '081277889900',
                'status_moderasi' => 'pending',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('msme_profiles', [
            'nama_usaha' => 'Warung Kopi Barokah RT 15',
            'kategori' => 'kuliner',
            'status_moderasi' => 'pending',
        ]);
    }

    /**
     * TC-132: Sekretaris menyetujui moderasi usaha UMKM warga
     */
    public function test_tc_132_ketua_rt_menyetujui_moderasi_umkm(): void
    {
        $umkm = MsmeProfile::create([
            'household_id' => $this->householdA->id,
            'nama_usaha' => 'Jahit Busana Muslimah Bu Siti',
            'deskripsi' => 'Menerima pesanan jahit baju kurung dan gamis.',
            'produk' => 'Gamis, Baju Kurung, Hijab',
            'kategori' => 'jasa',
            'alamat' => 'Jl. Kenanga No. 8',
            'telepon' => '081299881122',
            'status_moderasi' => 'pending',
            'is_active' => true,
        ]);

        Livewire::actingAs($this->sekretaris)
            ->test(EditMsmeProfile::class, ['record' => $umkm->id])
            ->fillForm([
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Memenuhi persyaratan usaha warga RT 15.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $umkm->refresh();
        $this->assertEquals('disetujui', $umkm->status_moderasi);
        $this->assertEquals('Memenuhi persyaratan usaha warga RT 15.', $umkm->catatan_moderasi);
    }

    /**
     * TC-133: UMKM yang disetujui tayang di etalase katalog publik /katalog-umkm
     */
    public function test_tc_133_umkm_disetujui_tayang_di_katalog_publik(): void
    {
        MsmeProfile::create([
            'household_id' => $this->householdA->id,
            'nama_usaha' => 'Pempek Asli Jambi Mak Cik',
            'deskripsi' => 'Pempek ikan tenggiri segar dengan cuko pedas manis.',
            'produk' => 'Pempek Kapal Selam, Lenjer, Kulit',
            'kategori' => 'kuliner',
            'alamat' => 'Blok B No. 3 RT 15',
            'telepon' => '081399887766',
            'whatsapp' => '081399887766',
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $response = $this->get('/katalog-umkm');
        $response->assertOk();
        $response->assertSee('Pempek Asli Jambi Mak Cik');
    }

    /**
     * TC-134: Ketua RT menolak pendaftaran UMKM (tidak tampil di publik)
     */
    public function test_tc_134_ketua_rt_menolak_moderasi_umkm(): void
    {
        $umkm = MsmeProfile::create([
            'household_id' => $this->householdA->id,
            'nama_usaha' => 'Toko Vape Ilegal',
            'deskripsi' => 'Usaha tanpa izin.',
            'produk' => 'Rokok elektrik',
            'kategori' => 'lainnya',
            'alamat' => 'Blok X',
            'telepon' => '081122334455',
            'status_moderasi' => 'ditolak',
            'catatan_moderasi' => 'Jenis produk tidak diperkenankan di lingkungan RT 15.',
            'is_active' => false,
        ]);

        $response = $this->get('/katalog-umkm');
        $response->assertOk();
        $response->assertDontSee('Toko Vape Ilegal');
    }

    /**
     * TC-135: Publik menghubungi UMKM melalui tautan WhatsApp
     */
    public function test_tc_135_publik_menghubungi_umkm_via_whatsapp(): void
    {
        MsmeProfile::create([
            'household_id' => $this->householdA->id,
            'nama_usaha' => 'Keripik Tempe Renyah Gurih',
            'deskripsi' => 'Camilan sehat buatan rumah.',
            'produk' => 'Keripik Tempe Aneka Rasa',
            'kategori' => 'kuliner',
            'alamat' => 'Blok C No. 9',
            'telepon' => '081277112233',
            'whatsapp' => '081277112233',
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        $response = $this->get('/katalog-umkm');
        $response->assertOk();
        $response->assertSee('https://wa.me/6281277112233', false);
        $response->assertSee('Hubungi via WhatsApp');
    }

    /**
     * TC-136: Ketua RT dilarang mengakses modul Profil UMKM (didelegasikan ke Sekretaris)
     */
    public function test_tc_136_sekretaris_dilarang_akses_umkm(): void
    {
        $response = $this->actingAs($this->ketuaRt)->get('/admin/msme-profiles');
        $response->assertForbidden();
    }

    /**
     * TC-137: Bendahara dilarang mengakses modul Profil UMKM
     */
    public function test_tc_137_bendahara_dilarang_akses_umkm(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/msme-profiles');
        $response->assertForbidden();
    }

    /**
     * TC-138: Koordinator Bank Sampah dilarang mengakses Profil UMKM
     */
    public function test_tc_138_koor_sampah_dilarang_akses_umkm(): void
    {
        $response = $this->actingAs($this->koorSampah)->get('/admin/msme-profiles');
        $response->assertForbidden();
    }

    /**
     * TC-139: Koordinator Keamanan dilarang mengakses Profil UMKM
     */
    public function test_tc_139_koor_keamanan_dilarang_akses_umkm(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin/msme-profiles');
        $response->assertForbidden();
    }

    /**
     * TC-140: RW / Kelurahan dilarang mengakses Profil UMKM
     */
    public function test_tc_140_rw_kelurahan_dilarang_akses_umkm(): void
    {
        $response = $this->actingAs($this->rwKelurahan)->get('/admin/msme-profiles');
        $response->assertForbidden();
    }
}
