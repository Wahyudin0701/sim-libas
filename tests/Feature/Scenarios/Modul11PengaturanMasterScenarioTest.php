<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\LetterTypes\Pages\CreateLetterType;
use App\Filament\Resources\RtSettings\Pages\EditRtSetting;
use App\Models\LetterType;
use App\Models\RtSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 11: Pengaturan Sistem Wilayah & Master Data
 * Skenario: TC-141 s/d TC-148
 */
class Modul11PengaturanMasterScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $bendahara;
    protected RtSetting $setting;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superAdmin = User::where('email', 'admin@simlibas.id')->first();
        $this->ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->first();

        $this->setting = RtSetting::first() ?? RtSetting::create([
            'nama_rt' => 'RT 15',
            'nama_ketua_rt' => 'Bpk. H. Ahmad Sudirman, S.E.',
            'alamat' => 'Jl. Pinang Masak RT 15',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'kota' => 'Kota Jambi',
            'provinsi' => 'Jambi',
            'kode_pos' => '36129',
            'telepon' => '0812-7400-1515',
            'email' => 'rt15pematangsulur@gmail.com',
            'format_nomor_surat' => '{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}',
        ]);
    }

    /**
     * TC-141: Super Admin mengubah nama pimpinan, kontak, dan alamat sekretariat RT
     */
    public function test_tc_141_super_admin_mengubah_pengaturan_rt(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(EditRtSetting::class, ['record' => $this->setting->id])
            ->fillForm([
                'nama_ketua_rt' => 'Bpk. H. Ahmad Sudirman, M.M.',
                'telepon' => '0812-7400-9999',
                'alamat' => 'Jl. Kenanga Utama No. 15, RT 15 Pematang Sulur',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->setting->refresh();
        $this->assertEquals('Bpk. H. Ahmad Sudirman, M.M.', $this->setting->nama_ketua_rt);
        $this->assertEquals('0812-7400-9999', $this->setting->telepon);
        $this->assertEquals('Jl. Kenanga Utama No. 15, RT 15 Pematang Sulur', $this->setting->alamat);
    }

    /**
     * TC-142: Super Admin upload file stempel resmi RT dan tanda tangan digital
     */
    public function test_tc_142_super_admin_upload_stempel_dan_ttd_digital(): void
    {
        Storage::fake('public');

        $stempelFile = UploadedFile::fake()->image('stempel_rt15.png', 400, 400);
        $ttdFile = UploadedFile::fake()->image('ttd_ketua_rt.png', 400, 200);

        Livewire::actingAs($this->superAdmin)
            ->test(EditRtSetting::class, ['record' => $this->setting->id])
            ->fillForm([
                'stempel_path' => $stempelFile,
                'tanda_tangan_path' => $ttdFile,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->setting->refresh();
        $this->assertNotNull($this->setting->stempel_path);
        $this->assertNotNull($this->setting->tanda_tangan_path);
    }

    /**
     * TC-143: Super Admin mengubah pola format nomor surat pengantar
     */
    public function test_tc_143_super_admin_mengubah_format_nomor_surat(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(EditRtSetting::class, ['record' => $this->setting->id])
            ->fillForm([
                'format_nomor_surat' => '{NO}/SP-KAMPUNG/{BULAN_ROMAWI}/{TAHUN}',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->setting->refresh();
        $this->assertEquals('{NO}/SP-KAMPUNG/{BULAN_ROMAWI}/{TAHUN}', $this->setting->format_nomor_surat);
    }

    /**
     * TC-144: Ketua RT dilarang mengakses menu Pengaturan Sistem
     */
    public function test_tc_144_ketua_rt_dilarang_akses_pengaturan_sistem(): void
    {
        $response = $this->actingAs($this->ketuaRt)->get('/admin/rt-settings');
        $response->assertForbidden();
    }

    /**
     * TC-145: Sekretaris RT dilarang mengakses menu Pengaturan Sistem
     */
    public function test_tc_145_sekretaris_dilarang_akses_pengaturan_sistem(): void
    {
        $response = $this->actingAs($this->sekretaris)->get('/admin/rt-settings');
        $response->assertForbidden();
    }

    /**
     * TC-146: Bendahara RT dilarang mengakses menu Pengaturan Sistem
     */
    public function test_tc_146_bendahara_dilarang_akses_pengaturan_sistem(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/rt-settings');
        $response->assertForbidden();
    }

    /**
     * TC-147: Sekretaris mengelola master jenis dan template surat
     */
    public function test_tc_147_sekretaris_mengelola_master_jenis_surat(): void
    {
        $response = $this->actingAs($this->sekretaris)->get('/admin/letter-types');
        $response->assertOk();
        $response->assertSee('Master Jenis Surat');

        Livewire::actingAs($this->sekretaris)
            ->test(CreateLetterType::class)
            ->fillForm([
                'nama' => 'Surat Keterangan Belum Menikah',
                'kode' => 'SKBM',
                'deskripsi' => 'Keterangan status belum menikah untuk syarat pernikahan KUA.',
                'format_nomor' => '{NO}/SKBM/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['KTP Pemohon', 'KK Pemohon', 'Surat Pernyataan'],
                'is_active' => true,
                'urutan' => 99,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('letter_types', [
            'nama' => 'Surat Keterangan Belum Menikah',
            'kode' => 'SKBM',
            'is_active' => true,
        ]);
    }

    /**
     * TC-148: Pengurus mengedit profil akun dan kontak via EditProfile
     */
    public function test_tc_148_pengurus_mengedit_profil_akun(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(EditProfile::class)
            ->fillForm([
                'name' => 'Bambang Irawan, S.Kom., M.Cs.',
                'phone' => '081274009876',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->sekretaris->refresh();
        $this->assertEquals('Bambang Irawan, S.Kom., M.Cs.', $this->sekretaris->name);
        $this->assertEquals('081274009876', $this->sekretaris->phone);
    }
}
