<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\Letters\Pages\ListLetters;
use App\Models\Household;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 4: Layanan Surat Pengantar RT (End-to-End)
 * Skenario: TC-045 s/d TC-060
 */
class Modul04SuratPengantarScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $bendahara;
    protected User $wargaKepala;
    protected User $wargaAnggota;
    protected User $wargaLain;
    protected Household $householdA;
    protected Household $householdB;
    protected Resident $residentKepala;
    protected Resident $residentIstri;
    protected Resident $residentAnak;
    protected Resident $residentLain;
    protected LetterType $letterType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.com')->first();

        // Setup KK A (Kepala: Dedi, Istri: Siti, Anak: Rizky)
        $this->wargaKepala = User::where('email', 'warga.dedi@simlibas.com')->first();
        $this->residentKepala = Resident::where('user_id', $this->wargaKepala->id)->first();
        $this->householdA = $this->residentKepala->household;

        $this->residentIstri = Resident::where('household_id', $this->householdA->id)
            ->where('hubungan_keluarga', 'istri')
            ->first();

        $this->residentAnak = Resident::where('household_id', $this->householdA->id)
            ->where('hubungan_keluarga', 'anak')
            ->first();

        // Pastikan anak memiliki akun user terpisah (Anggota Keluarga Mandiri)
        if (!$this->residentAnak->user_id) {
            $userAnak = User::create([
                'name' => $this->residentAnak->nama,
                'email' => $this->residentAnak->nik . '@warga.simlibas.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            $userAnak->assignRole('warga');
            $this->residentAnak->update(['user_id' => $userAnak->id]);
            $this->wargaAnggota = $userAnak;
        } else {
            $this->wargaAnggota = $this->residentAnak->user;
        }

        // Setup KK B (Warga Lain)
        $this->householdB = Household::where('id', '!=', $this->householdA->id)->first();
        $this->residentLain = $this->householdB->residents()->first();
        if (!$this->residentLain->user_id) {
            $userLain = User::create([
                'name' => $this->residentLain->nama,
                'email' => $this->residentLain->nik . '@warga.simlibas.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            $userLain->assignRole('warga');
            $this->residentLain->update(['user_id' => $userLain->id]);
            $this->wargaLain = $userLain;
        } else {
            $this->wargaLain = $this->residentLain->user;
        }

        $this->letterType = LetterType::first() ?? LetterType::create([
            'kode_surat' => 'SKD',
            'nama' => 'Surat Keterangan Domisili',
            'deskripsi' => 'Keterangan bertempat tinggal di RT 15',
            'syarat' => 'KTP dan KK',
            'is_active' => true,
            'urutan' => 1,
        ]);
    }

    /**
     * TC-045: Pengajuan surat pengantar oleh Kepala Keluarga untuk anggota keluarga
     */
    public function test_tc_045_pengajuan_surat_oleh_kepala_keluarga_untuk_anggota(): void
    {
        $response = $this->actingAs($this->wargaKepala)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentAnak->id,
            'keperluan' => 'Permohonan surat keterangan untuk pendaftaran sekolah',
        ]);

        $response->assertRedirect(route('warga.surat'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('letters', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentAnak->id,
            'status' => 'diajukan',
            'keperluan' => 'Permohonan surat keterangan untuk pendaftaran sekolah',
        ]);

        $letter = Letter::where('resident_id', $this->residentAnak->id)->latest()->first();
        $this->assertNotNull($letter->qr_token);

        // Memastikan notifikasi terkirim ke Sekretaris atau Ketua RT
        $this->assertDatabaseHas('notifications', [
            'judul' => 'Pengajuan Surat: ' . $this->letterType->nama,
        ]);
    }

    /**
     * TC-046: Pengajuan surat oleh Anggota Keluarga Mandiri untuk dirinya sendiri
     */
    public function test_tc_046_pengajuan_surat_oleh_anggota_mandiri_untuk_diri_sendiri(): void
    {
        $response = $this->actingAs($this->wargaAnggota)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentAnak->id,
            'keperluan' => 'Surat keterangan magang kuliah',
        ]);

        $response->assertRedirect(route('warga.surat'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('letters', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentAnak->id,
            'status' => 'diajukan',
            'keperluan' => 'Surat keterangan magang kuliah',
        ]);
    }

    /**
     * TC-047: Anggota Keluarga Mandiri mencoba mengajukan surat untuk anggota lain dalam KK
     */
    public function test_tc_047_anggota_mandiri_dilarang_mengajukan_surat_untuk_anggota_lain(): void
    {
        $response = $this->actingAs($this->wargaAnggota)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Mencoba mengajukan untuk ayah saya',
        ]);

        $response->assertSessionHasErrors(['resident_id']);
        $this->assertDatabaseMissing('letters', [
            'keperluan' => 'Mencoba mengajukan untuk ayah saya',
        ]);
    }

    /**
     * TC-048: Warga mencoba mengajukan surat untuk warga di luar KK miliknya
     */
    public function test_tc_048_warga_dilarang_mengajukan_surat_untuk_warga_kk_lain(): void
    {
        $response = $this->actingAs($this->wargaKepala)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentLain->id,
            'keperluan' => 'Pengajuan ilegal untuk tetangga',
        ]);

        $response->assertSessionHasErrors(['resident_id']);
        $this->assertDatabaseMissing('letters', [
            'keperluan' => 'Pengajuan ilegal untuk tetangga',
        ]);
    }

    /**
     * TC-049: Pengajuan surat dengan field keperluan kosong atau kurang dari batas minimal
     */
    public function test_tc_049_validasi_input_keperluan_surat_wajib_diisi(): void
    {
        $response = $this->actingAs($this->wargaKepala)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => '',
        ]);

        $response->assertSessionHasErrors(['keperluan']);
    }

    /**
     * TC-050: Notifikasi pengajuan surat masuk diterima oleh Sekretaris dan Ketua RT
     */
    public function test_tc_050_notifikasi_pengajuan_surat_masuk_ke_pengurus(): void
    {
        $this->actingAs($this->wargaKepala)->post('/warga/surat', [
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Keperluan perpanjangan SIM di kepolisian',
        ]);

        $latestNotif = Notification::where('judul', 'like', 'Pengajuan Surat%')->latest()->first();
        $this->assertNotNull($latestNotif);
        $this->assertStringContainsString('Dedi Kurniawan', $latestNotif->isi);
    }

    /**
     * TC-051: Sekretaris memeriksa daftar permohonan surat di panel Filament
     */
    public function test_tc_051_sekretaris_memeriksa_daftar_surat(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Uji pemeriksaan surat oleh sekretaris',
            'status' => 'diajukan',
            'qr_token' => 'TOKEN_SEKRETARIS_CHECK',
        ]);

        $response = $this->actingAs($this->sekretaris)->get('/admin/letters');

        $response->assertOk();
        $response->assertSee('Surat Pengantar');
        $response->assertSee('Dedi Kurniawan');
    }

    /**
     * TC-052: Ketua RT menyetujui permohonan surat pengantar
     */
    public function test_tc_052_ketua_rt_menyetujui_permohonan_surat(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Persetujuan pembuatan KTP baru',
            'status' => 'diajukan',
            'qr_token' => 'TOKEN_APPROVE_TEST',
        ]);

        Livewire::actingAs($this->ketuaRt)
            ->test(ListLetters::class)
            ->callTableAction('setujui', $letter);

        $letter->refresh();
        $this->assertEquals('disetujui', $letter->status);
        $this->assertNotNull($letter->nomor_surat);
        $this->assertStringContainsString('/RT.15/PMS/', $letter->nomor_surat);
        $this->assertEquals($this->ketuaRt->id, $letter->disetujui_oleh);

        // Notifikasi approval terkirim ke warga
        $this->assertTrue(Notification::where('user_id', $this->wargaKepala->id)->where('judul', 'like', '%Disetujui%')->exists());
    }

    /**
     * TC-053: Ketua RT menolak permohonan surat dengan alasan
     */
    public function test_tc_053_ketua_rt_menolak_permohonan_surat_dengan_alasan(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Pengajuan yang ditolak karena data kurang',
            'status' => 'diajukan',
            'qr_token' => 'TOKEN_REJECT_TEST',
        ]);

        Livewire::actingAs($this->ketuaRt)
            ->test(ListLetters::class)
            ->callTableAction('tolak', $letter, [
                'alasan_tolak' => 'Lampiran fotokopi KK lama belum diserahkan ke pengurus RT.',
            ]);

        $letter->refresh();
        $this->assertEquals('ditolak', $letter->status);
        $this->assertEquals('Lampiran fotokopi KK lama belum diserahkan ke pengurus RT.', $letter->alasan_tolak);

        // Notifikasi penolakan terkirim ke warga
        $this->assertTrue(Notification::where('user_id', $this->wargaKepala->id)->where('judul', 'like', '%Ditolak%')->exists());
    }

    /**
     * TC-054: Uji otorisasi surat pada Sekretaris
     */
    public function test_tc_054_uji_otorisasi_surat_sekretaris(): void
    {
        // Sekretaris dapat mengakses halaman daftar surat
        $response = $this->actingAs($this->sekretaris)->get('/admin/letters');
        $response->assertOk();

        // Verifikasi bahwa halaman render tanpa crash
        $this->assertTrue($this->sekretaris->can('periksa_surat') || $this->sekretaris->hasRole('sekretaris'));
    }

    /**
     * TC-055: Warga menerima notifikasi persetujuan surat
     */
    public function test_tc_055_warga_menerima_notifikasi_persetujuan_surat(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Pengajuan surat selesai',
            'status' => 'disetujui',
            'nomor_surat' => '077/RT.15/PMS/X/2026',
            'disetujui_oleh' => $this->ketuaRt->id,
            'tanggal_disetujui' => now(),
            'qr_token' => 'TOKEN_NOTIF_CITIZEN',
        ]);

        \App\Services\NotificationService::notifyLetterApproved($letter);

        $response = $this->actingAs($this->wargaKepala)->get('/warga/notifikasi');
        $response->assertOk();
        $this->assertStringContainsString('Surat Pengantar Disetujui', $response->getContent());
    }

    /**
     * TC-056: Warga mencetak lembar resmi surat pengantar yang telah disetujui
     */
    public function test_tc_056_warga_mencetak_surat_disetujui(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Pencetakan surat disetujui',
            'status' => 'disetujui',
            'nomor_surat' => '088/RT.15/PMS/X/2026',
            'disetujui_oleh' => $this->ketuaRt->id,
            'tanggal_disetujui' => now(),
            'qr_token' => 'TOKEN_PRINT_OFFICIAL',
        ]);

        $response = $this->actingAs($this->wargaKepala)->get(route('letters.print', $letter));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        $htmlResponse = $this->actingAs($this->wargaKepala)->get(route('letters.print', ['letter' => $letter, 'format' => 'html']));
        $htmlResponse->assertOk();
        $htmlResponse->assertSee('RUKUN TETANGGA 15');
        $htmlResponse->assertSee('088/RT.15/PMS/X/2026');
        $htmlResponse->assertSee(strtoupper($this->residentKepala->nama));
    }

    /**
     * TC-057: Keamanan cetak: Surat berstatus diajukan tidak menampilkan tombol cetak
     */
    public function test_tc_057_surat_status_diajukan_tidak_ada_tombol_cetak_di_portal_warga(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Surat masih diajukan tidak boleh ada tautan cetak',
            'status' => 'diajukan',
            'qr_token' => 'TOKEN_PENDING_NO_PRINT',
        ]);

        $response = $this->actingAs($this->wargaKepala)->get('/warga/surat');
        $response->assertOk();
        $response->assertDontSee(route('letters.print', $letter));
    }

    /**
     * TC-058: Keamanan antar-warga: Warga dilarang mencetak surat milik KK lain
     */
    public function test_tc_058_warga_dilarang_mencetak_surat_kk_lain(): void
    {
        $letterLain = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentLain->id,
            'keperluan' => 'Surat milik warga KK lain',
            'status' => 'disetujui',
            'nomor_surat' => '099/RT.15/PMS/X/2026',
            'disetujui_oleh' => $this->ketuaRt->id,
            'tanggal_disetujui' => now(),
            'qr_token' => 'TOKEN_KK_LAIN_SECURE',
        ]);

        $response = $this->actingAs($this->wargaKepala)->get(route('letters.print', $letterLain));
        $response->assertForbidden();
    }

    /**
     * TC-059: Pengurus (Ketua RT / Sekretaris) mencetak surat warga dari panel
     */
    public function test_tc_059_pengurus_mencetak_surat_warga_dari_panel(): void
    {
        $letter = Letter::create([
            'letter_type_id' => $this->letterType->id,
            'resident_id' => $this->residentKepala->id,
            'keperluan' => 'Cetak oleh Ketua RT',
            'status' => 'disetujui',
            'nomor_surat' => '100/RT.15/PMS/X/2026',
            'disetujui_oleh' => $this->ketuaRt->id,
            'tanggal_disetujui' => now(),
            'qr_token' => 'TOKEN_ADMIN_PRINT',
        ]);

        $response = $this->actingAs($this->ketuaRt)->get(route('letters.print', $letter));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * TC-060: Bendahara RT dilarang mengakses modul Surat Pengantar
     */
    public function test_tc_060_bendahara_dilarang_akses_modul_surat(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/letters');
        $response->assertForbidden();
    }
}
