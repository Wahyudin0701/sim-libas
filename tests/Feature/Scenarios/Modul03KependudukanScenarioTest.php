<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\Households\Pages\CreateHousehold;
use App\Filament\Resources\Households\Pages\EditHousehold;
use App\Filament\Resources\Households\Pages\ViewHousehold;
use App\Filament\Resources\Residents\Pages\ListResidents;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class Modul03KependudukanScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $sekretaris;
    protected User $bendahara;
    protected User $koorBankSampah;
    protected User $wargaUser;
    protected Household $household;
    protected Resident $kepalaKeluarga;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->firstOrFail();
        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->firstOrFail();
        $this->koorBankSampah = User::where('email', 'banksampah@simlibas.id')->firstOrFail();
        $this->wargaUser = User::where('email', 'warga.dedi@simlibas.id')->firstOrFail();
        $this->household = Household::firstOrFail();
        $this->kepalaKeluarga = $this->household->kepalaKeluarga;
    }

    /**
     * TC-025: Sekretaris dapat melihat daftar seluruh Kartu Keluarga
     */
    public function test_tc_025_sekretaris_dapat_melihat_daftar_kk(): void
    {
        $response = $this->actingAs($this->sekretaris)->get('/admin/households');

        $response->assertOk();
        $response->assertSee('Kartu Keluarga');
    }

    /**
     * TC-026: Tambah KK lengkap dengan anggota dan pembuatan otomatis akun warga
     */
    public function test_tc_026_tambah_kk_lengkap_dengan_anggota_dan_auto_user(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', '1571019909990099')
            ->set('alamat', 'Jl. Kenanga Blok C No. 99')
            ->set('status_hunian', 'tetap')
            ->set('kepala_nama', 'Bpk. Ridwan Kamil')
            ->set('kepala_nik', '1571011112220099')
            ->set('kepala_telepon', '081299990099')
            ->set('kepala_tempat_lahir', 'Bandung')
            ->set('kepala_tanggal_lahir', '1982-05-10')
            ->set('anggota_keluarga', [
                [
                    'nama' => 'Ibu Atalia',
                    'nik' => '1571015556660099',
                    'hubungan_keluarga' => 'istri',
                    'jenis_kelamin' => 'P',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1984-11-20',
                    'agama' => 'Islam',
                    'pekerjaan' => 'Wirausaha',
                    'pendidikan_terakhir' => 'S1',
                    'status_perkawinan' => 'Kawin',
                    'telepon' => '081299990098',
                ]
            ])
            ->call('saveHousehold')
            ->assertHasNoErrors();

        $savedKk = Household::where('alamat', 'Jl. Kenanga Blok C No. 99')->first();
        $this->assertNotNull($savedKk);
        $this->assertEquals('1571019909990099', $savedKk->no_kk);
        $this->assertDatabaseHas('residents', ['nama' => 'Bpk. Ridwan Kamil', 'is_kepala_keluarga' => true]);
        $this->assertDatabaseHas('residents', ['nama' => 'Ibu Atalia', 'is_kepala_keluarga' => false]);
        $this->assertDatabaseHas('users', ['email' => '1571011112220099@warga.simlibas.id']);
        $this->assertDatabaseHas('users', ['email' => '1571015556660099@warga.simlibas.id']);
    }

    /**
     * TC-027: Validasi nomor KK harus 16 digit angka
     */
    public function test_tc_027_validasi_nomor_kk_harus_16_digit(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', '12345') // Kurang dari 16 digit
            ->set('alamat', 'Jl. Kenanga')
            ->set('kepala_nama', 'Warga Test')
            ->set('kepala_nik', '1571011112223334')
            ->call('saveHousehold')
            ->assertHasErrors(['no_kk']);
    }

    /**
     * TC-028: Validasi nomor KK unik (mencegah duplikasi nomor KK terdaftar)
     */
    public function test_tc_028_validasi_nomor_kk_unik(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', $this->household->no_kk) // Nomor KK yang sudah ada
            ->set('alamat', 'Jl. Kenanga')
            ->set('kepala_nama', 'Warga Test Duplikat')
            ->set('kepala_nik', '1571019998887776')
            ->call('saveHousehold')
            ->assertHasErrors(['no_kk']);
    }

    /**
     * TC-029: Validasi NIK kepala keluarga wajib 16 digit angka
     */
    public function test_tc_029_validasi_nik_kepala_keluarga_16_digit(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', '1571018889990001')
            ->set('alamat', 'Jl. Kenanga')
            ->set('kepala_nama', 'Warga NIK Salah')
            ->set('kepala_nik', '123') // Kurang dari 16 digit
            ->call('saveHousehold')
            ->assertHasErrors(['kepala_nik']);
    }

    /**
     * TC-030: Mencegah pendaftaran NIK duplikat via SHA-256 hash
     */
    public function test_tc_030_mencegah_pendaftaran_nik_duplikat(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', '1571018889990002')
            ->set('alamat', 'Jl. Kenanga')
            ->set('kepala_nama', 'Warga Duplikat NIK')
            ->set('kepala_nik', $this->kepalaKeluarga->nik) // NIK Dedi Kurniawan yang sudah ada
            ->call('saveHousehold')
            ->assertHasErrors(['kepala_nik']);
    }

    /**
     * TC-031: Upload dokumen scan fisik Kartu Keluarga
     */
    public function test_tc_031_upload_dokumen_scan_fisik_kk(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('kk-asli.jpg', 600, 400);

        Livewire::actingAs($this->sekretaris)
            ->test(CreateHousehold::class)
            ->set('no_kk', '1571017778880003')
            ->set('alamat', 'Jl. Kenanga Blok D No. 12')
            ->set('status_hunian', 'tetap')
            ->set('foto_kk', $file)
            ->set('kepala_nama', 'Bpk. Surya Paloh')
            ->set('kepala_nik', '1571017778880001')
            ->call('saveHousehold')
            ->assertHasNoErrors();

        $savedHousehold = Household::where('alamat', 'Jl. Kenanga Blok D No. 12')->first();
        $this->assertNotNull($savedHousehold);
        $this->assertEquals('1571017778880003', $savedHousehold->no_kk);
        $this->assertNotNull($savedHousehold->foto_kk);
        Storage::disk('public')->assertExists($savedHousehold->foto_kk);
    }

    /**
     * TC-032: Menampilkan detail KK dan relasi anggotanya
     */
    public function test_tc_032_menampilkan_detail_kk_dan_anggota(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(ViewHousehold::class, ['record' => $this->household->id])
            ->assertSee($this->household->no_kk)
            ->assertSee($this->kepalaKeluarga->nama);
    }

    /**
     * TC-033: Edit alamat dan status hunian KK
     */
    public function test_tc_033_edit_alamat_dan_status_hunian_kk(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(EditHousehold::class, ['record' => $this->household->id])
            ->set('alamat', 'Jl. Pinang Masak No. 999 Baru')
            ->set('status_hunian', 'kontrak')
            ->call('updateHousehold')
            ->assertHasNoErrors();

        $this->household->refresh();
        $this->assertEquals('Jl. Pinang Masak No. 999 Baru', $this->household->alamat);
        $this->assertEquals('kontrak', $this->household->status_hunian);
    }

    /**
     * TC-034: Menampilkan daftar seluruh warga RT 15
     */
    public function test_tc_034_menampilkan_daftar_seluruh_warga(): void
    {
        $response = $this->actingAs($this->sekretaris)->get('/admin/residents');

        $response->assertOk();
        $response->assertSee('Data Warga');
        $response->assertSee('KEPENDUDUKAN RT 15');
    }

    /**
     * TC-035: Pencarian warga berdasarkan nama, telepon, atau NIK hash
     */
    public function test_tc_035_pencarian_warga_berdasarkan_nama(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->set('search', 'Dedi')
            ->assertSee('Dedi Kurniawan');
    }

    /**
     * TC-036: Ekspor data seluruh warga ke format CSV
     */
    public function test_tc_036_ekspor_data_warga_ke_csv(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('export')
            ->assertFileDownloaded('data-warga-rt15-' . date('Y-m-d') . '.csv');
    }

    /**
     * TC-037: Edit identitas warga dan sinkronisasi akun user
     */
    public function test_tc_037_edit_identitas_warga_dan_sinkronisasi_user(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('openEditResident', $this->kepalaKeluarga->id)
            ->set('edit_pekerjaan', 'Konsultan IT Senior')
            ->set('edit_telepon', '0812-7777-6666')
            ->call('saveResident')
            ->assertHasNoErrors();

        $this->kepalaKeluarga->refresh();
        $this->assertEquals('Konsultan IT Senior', $this->kepalaKeluarga->pekerjaan);
        $this->assertEquals('0812-7777-6666', $this->kepalaKeluarga->telepon);
        $this->assertEquals('0812-7777-6666', $this->kepalaKeluarga->user->phone);
    }

    /**
     * TC-037b: Pengurus dapat mengubah dan mereset kata sandi warga via modal Ubah Data Warga
     */
    public function test_tc_037b_reset_password_warga_via_modal_edit_resident(): void
    {
        // 1. Pengurus set password baru manual
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('openEditResident', $this->kepalaKeluarga->id)
            ->assertSet('has_user_account', true)
            ->set('edit_password', 'rahasia12345')
            ->call('saveResident')
            ->assertHasNoErrors();

        $this->kepalaKeluarga->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('rahasia12345', $this->kepalaKeluarga->user->password));

        // 2. Pengurus klik tombol reset ke kata sandi bawaan 'password'
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('openEditResident', $this->kepalaKeluarga->id)
            ->call('resetPasswordToDefault')
            ->assertSet('edit_password', 'password')
            ->call('saveResident')
            ->assertHasNoErrors();

        $this->kepalaKeluarga->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $this->kepalaKeluarga->user->password));
    }

    /**
     * TC-038: Penunjukan Kepala Keluarga baru dalam suatu KK
     */
    public function test_tc_038_penunjukan_kepala_keluarga_baru_dalam_kk(): void
    {
        $istri = Resident::where('household_id', $this->household->id)
            ->where('hubungan_keluarga', 'istri')
            ->firstOrFail();

        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('openEditResident', $istri->id)
            ->set('edit_hubungan_keluarga', 'kepala_keluarga')
            ->call('saveResident')
            ->assertHasNoErrors();

        $istri->refresh();
        $this->kepalaKeluarga->refresh();

        $this->assertTrue((bool) $istri->is_kepala_keluarga);
        // Kepala keluarga sebelumnya otomatis dinonaktifkan status kepalanya
        $this->assertFalse((bool) $this->kepalaKeluarga->is_kepala_keluarga);
    }

    /**
     * TC-039: Mutasi status kependudukan warga (pindah/meninggal)
     */
    public function test_tc_039_mutasi_status_kependudukan_warga(): void
    {
        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('openEditResident', $this->kepalaKeluarga->id)
            ->set('edit_status_kependudukan', 'pindah')
            ->call('saveResident')
            ->assertHasNoErrors();

        $this->kepalaKeluarga->refresh();
        $this->assertEquals('pindah', $this->kepalaKeluarga->status_kependudukan);
    }

    /**
     * TC-040: Proteksi pencegahan hapus Kepala Keluarga yang masih punya anggota
     */
    public function test_tc_040_proteksi_cegah_hapus_kepala_keluarga_aktif(): void
    {
        // Pastikan KK memiliki lebih dari 1 anggota
        $this->assertGreaterThan(1, $this->household->residents()->count());

        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('deleteResident', $this->kepalaKeluarga->id);

        // Kepala keluarga TIDAK boleh terhapus
        $this->assertDatabaseHas('residents', [
            'id' => $this->kepalaKeluarga->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * TC-041: Soft delete anggota keluarga biasa
     */
    public function test_tc_041_soft_delete_anggota_keluarga_biasa(): void
    {
        $anak = Resident::where('household_id', $this->household->id)
            ->where('hubungan_keluarga', 'anak')
            ->firstOrFail();

        Livewire::actingAs($this->sekretaris)
            ->test(ListResidents::class)
            ->call('deleteResident', $anak->id);

        $this->assertSoftDeleted('residents', ['id' => $anak->id]);
    }

    /**
     * TC-042: Bendahara dilarang mengakses modul kependudukan (403)
     */
    public function test_tc_042_bendahara_dilarang_akses_kependudukan(): void
    {
        $this->actingAs($this->bendahara)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($this->bendahara)->get('/admin/households')->assertStatus(403);
    }

    /**
     * TC-043: Koordinator Bank Sampah dilarang mengakses modul kependudukan (403)
     */
    public function test_tc_043_koor_bank_sampah_dilarang_akses_kependudukan(): void
    {
        $this->actingAs($this->koorBankSampah)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($this->koorBankSampah)->get('/admin/households')->assertStatus(403);
    }

    /**
     * TC-044: Warga melihat data Kartu Keluarga miliknya sendiri
     */
    public function test_tc_044_warga_melihat_kartu_keluarga_miliknya(): void
    {
        $response = $this->actingAs($this->wargaUser)->get(route('warga.kartu-keluarga'));

        $response->assertOk();
        $response->assertSee($this->household->no_kk);
        $response->assertSee($this->kepalaKeluarga->nama);
    }
}
