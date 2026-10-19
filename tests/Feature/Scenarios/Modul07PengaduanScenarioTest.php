<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\Complaints\Pages\ListComplaints;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 7: Pengaduan & Aspirasi Warga (End-to-End)
 * Skenario: TC-095 s/d TC-108
 */
class Modul07PengaduanScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $bendahara;
    protected User $koorKeamanan;
    protected User $koorSampah;
    protected User $wargaA;
    protected User $wargaB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $this->koorKeamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $this->koorSampah = User::where('email', 'banksampah@simlibas.com')->first();

        $this->wargaA = User::where('email', 'warga.dedi@simlibas.com')->first();

        $this->wargaB = User::where('email', '!=', 'warga.dedi@simlibas.com')
            ->role('warga')
            ->first();

        if (!$this->wargaB) {
            $this->wargaB = User::create([
                'name' => 'Warga Tetangga B',
                'email' => 'warga.b@simlibas.com',
                'password' => bcrypt('password'),
                'status' => 'aktif',
            ]);
            $this->wargaB->assignRole('warga');
        }
    }

    /**
     * TC-095: Pengaduan kategori Keamanan non-anonim
     */
    public function test_tc_095_pengaduan_kategori_keamanan_non_anonim(): void
    {
        $response = $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Orang mencurigakan di pos ronda blok A',
            'kategori' => 'keamanan',
            'lokasi' => 'Dekat pos kamling RT 15',
            'deskripsi' => 'Terlihat orang tidak dikenal mondar-mandir larut malam tanpa identitas jelas.',
            'is_anonim' => 0,
        ]);

        $response->assertRedirect(route('warga.pengaduan'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'user_id' => $this->wargaA->id,
            'judul' => 'Orang mencurigakan di pos ronda blok A',
            'kategori' => 'keamanan',
            'is_anonim' => false,
            'status' => 'diterima',
        ]);
    }

    /**
     * TC-096: Pengaduan kategori Kebersihan berstatus Anonim
     */
    public function test_tc_096_pengaduan_kategori_kebersihan_anonim(): void
    {
        $response = $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Sampah liar menumpuk di lahan kosong samping RT',
            'kategori' => 'kebersihan',
            'lokasi' => 'Lahan kosong pojok timur',
            'deskripsi' => 'Tumpukan sampah plastik menimbulkan bau menyengat.',
            'is_anonim' => 1,
        ]);

        $response->assertRedirect(route('warga.pengaduan'));

        $this->assertDatabaseHas('complaints', [
            'user_id' => $this->wargaA->id,
            'judul' => 'Sampah liar menumpuk di lahan kosong samping RT',
            'kategori' => 'kebersihan',
            'is_anonim' => true,
        ]);
    }

    /**
     * TC-097: Validasi pengaduan dengan deskripsi kurang dari 5 karakter
     */
    public function test_tc_097_validasi_deskripsi_pengaduan_kurang_5_karakter(): void
    {
        $response = $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Lampu padam',
            'kategori' => 'infrastruktur',
            'deskripsi' => 'mati',
        ]);

        $response->assertSessionHasErrors(['deskripsi']);
    }

    /**
     * TC-098: Koordinator Keamanan menerima notifikasi pengaduan keamanan
     */
    public function test_tc_098_koordinator_keamanan_menerima_notifikasi(): void
    {
        $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Pintu gerbang portal rusak ditabrak motor',
            'kategori' => 'keamanan',
            'lokasi' => 'Gerbang utama RT 15',
            'deskripsi' => 'Engsel portal bengkok dan perlu perbaikan segera.',
            'is_anonim' => 0,
        ]);

        $this->assertTrue(
            Notification::where('user_id', $this->koorKeamanan->id)
                ->where('judul', 'like', '%Keamanan%')
                ->exists()
        );
    }

    /**
     * TC-099: Koordinator Sampah menerima notifikasi pengaduan kebersihan
     */
    public function test_tc_099_koordinator_sampah_menerima_notifikasi(): void
    {
        $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Bak sampah umum depan gang penuh',
            'kategori' => 'kebersihan',
            'lokasi' => 'Depan gang Kenanga',
            'deskripsi' => 'Sampah meluap ke badan jalan mohon diangkut.',
            'is_anonim' => 0,
        ]);

        $this->assertTrue(
            Notification::where('user_id', $this->koorSampah->id)
                ->where('judul', 'like', '%Kebersihan%')
                ->exists()
        );
    }

    /**
     * TC-100: Isolasi notifikasi: Bendahara tidak menerima notifikasi pengaduan keamanan
     */
    public function test_tc_100_isolasi_notifikasi_bidang_lain(): void
    {
        $this->actingAs($this->wargaA)->post('/warga/pengaduan', [
            'judul' => 'Hewan liar anjing galak berkeliaran',
            'kategori' => 'keamanan',
            'lokasi' => 'Blok Kenanga No 12',
            'deskripsi' => 'Anjing liar mengejar anak-anak yang bermain sepeda.',
            'is_anonim' => 0,
        ]);

        $this->assertFalse(
            Notification::where('user_id', $this->bendahara->id)
                ->where('isi', 'like', '%Hewan liar anjing galak berkeliaran%')
                ->exists()
        );
    }

    /**
     * TC-101: Pengurus menindaklanjuti pengaduan (Status: Diproses)
     */
    public function test_tc_101_pengurus_menindaklanjuti_pengaduan(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->wargaA->id,
            'judul' => 'Genangan air saluran mampet',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Depan rumah warga No 20',
            'deskripsi' => 'Saluran pembuangan tersumbat ranting pohon.',
            'status' => 'diterima',
        ]);

        Livewire::actingAs($this->ketuaRt)
            ->test(ListComplaints::class)
            ->callTableAction('proses', $complaint);

        $complaint->refresh();
        $this->assertEquals('diproses', $complaint->status);
        $this->assertEquals($this->ketuaRt->id, $complaint->penanggung_jawab_id);

        // Notifikasi tindak lanjut ke warga pelapor
        $this->assertTrue(
            Notification::where('user_id', $this->wargaA->id)
                ->where('judul', 'Pengaduan Sedang Diproses')
                ->exists()
        );
    }

    /**
     * TC-102: Pengurus menyelesaikan pengaduan (Status: Selesai)
     */
    public function test_tc_102_pengurus_menyelesaikan_pengaduan(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->wargaA->id,
            'judul' => 'Pohon tumbang menutup jalan setapak',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Jalan samping pos ronda',
            'deskripsi' => 'Dahan pohon mangga patah dan menutupi akses jalan.',
            'status' => 'diproses',
            'penanggung_jawab_id' => $this->ketuaRt->id,
        ]);

        Livewire::actingAs($this->ketuaRt)
            ->test(ListComplaints::class)
            ->callTableAction('selesai', $complaint, [
                'catatan_penanganan' => 'Dahan pohon telah dipotong dan dibersihkan bersama tim ronda.',
            ]);

        $complaint->refresh();
        $this->assertEquals('selesai', $complaint->status);
        $this->assertEquals('Dahan pohon telah dipotong dan dibersihkan bersama tim ronda.', $complaint->catatan_penanganan);
        $this->assertNotNull($complaint->tanggal_selesai);

        // Notifikasi ke warga
        $this->assertTrue(
            Notification::where('user_id', $this->wargaA->id)
                ->where('judul', 'Pengaduan Selesai Ditangani')
                ->exists()
        );
    }

    /**
     * TC-103: Warga memverifikasi status pengaduan selesai di portal warga
     */
    public function test_tc_103_warga_memverifikasi_status_pengaduan_selesai(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->wargaA->id,
            'judul' => 'Perbaikan jalan lubang',
            'kategori' => 'infrastruktur',
            'lokasi' => 'Blok B No 5',
            'deskripsi' => 'Ada lubang aspal cukup dalam.',
            'status' => 'selesai',
            'catatan_penanganan' => 'Sudah ditambal semen oleh pengurus RT.',
            'tanggal_selesai' => now(),
        ]);

        $response = $this->actingAs($this->wargaA)->get('/warga/pengaduan');
        $response->assertOk();
        $response->assertSee('Perbaikan jalan lubang');
        $response->assertSee('Selesai');
    }

    /**
     * TC-104: Isolasi pengaduan: Warga hanya melihat laporannya sendiri
     */
    public function test_tc_104_warga_hanya_melihat_pengaduan_milik_sendiri(): void
    {
        $complaintWargaB = Complaint::create([
            'user_id' => $this->wargaB->id,
            'judul' => 'Pengaduan Rahasia Warga Tetangga B',
            'kategori' => 'keamanan',
            'deskripsi' => 'Laporan rahasia tetangga.',
            'status' => 'diterima',
        ]);

        $response = $this->actingAs($this->wargaA)->get('/warga/pengaduan');
        $response->assertOk();
        $response->assertDontSee('Pengaduan Rahasia Warga Tetangga B');
    }

    /**
     * TC-105: Pengurus dapat memfilter pengaduan berdasarkan kategori & status
     */
    public function test_tc_105_pengurus_memfilter_pengaduan(): void
    {
        $response = $this->actingAs($this->ketuaRt)->get('/admin/complaints?tableFilters[status][value]=diproses');
        $response->assertOk();
        $response->assertSee('Pengaduan Warga');
    }

    /**
     * TC-106: Admin memverifikasi kolom pelapor pada laporan anonim
     */
    public function test_tc_106_tampilan_pelapor_anonim_di_tabel_admin(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->wargaA->id,
            'judul' => 'Laporan Anonim Kebisingan Musik Tengah Malam',
            'kategori' => 'kebisingan',
            'deskripsi' => 'Musik kencang di rumah nomor 18 hingga pukul 2 pagi.',
            'is_anonim' => true,
            'status' => 'diterima',
        ]);

        Livewire::actingAs($this->ketuaRt)
            ->test(ListComplaints::class)
            ->assertSee('Warga (Anonim)')
            ->assertDontSee($this->wargaA->name);
    }

    /**
     * TC-107: Bendahara RT dilarang mengakses modul Pengaduan
     */
    public function test_tc_107_bendahara_dilarang_akses_pengaduan(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/complaints');
        $response->assertForbidden();
    }

    /**
     * TC-108: Koordinator Bank Sampah dilarang mengakses modul Pengaduan
     */
    public function test_tc_108_koor_sampah_dilarang_akses_pengaduan(): void
    {
        $response = $this->actingAs($this->koorSampah)->get('/admin/complaints');
        $response->assertForbidden();
    }
}
