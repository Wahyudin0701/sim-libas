<?php

namespace Tests\Feature;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportResidentsFromExcelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memverifikasi bahwa mode dry-run membaca dan mengaudit seluruh data Excel
     * tanpa menulis satu pun record ke dalam database.
     */
    public function test_import_command_runs_dry_run_without_saving_to_database(): void
    {
        $this->artisan('simlibas:import-residents', ['--dry-run' => true])
            ->expectsOutputToContain('DRY-RUN / SIMULASI')
            ->expectsOutputToContain('Terbaca 19 data Kartu Keluarga')
            ->expectsOutputToContain('Terbaca 59 data Warga')
            ->expectsOutputToContain('[DRY-RUN SELESAI]')
            ->assertSuccessful();

        $this->assertEquals(0, Household::count(), 'Dry-run tidak boleh menyimpan Household ke database.');
        $this->assertEquals(0, Resident::count(), 'Dry-run tidak boleh menyimpan Resident ke database.');
    }

    /**
     * Memverifikasi proses import live: seluruh 19 KK dan 59 Warga berhasil disimpan
     * dengan relasi yang tepat, enkripsi data terjaga, dan integritas data valid.
     */
    public function test_import_command_imports_all_19_kks_and_59_residents_accurately(): void
    {
        $this->artisan('simlibas:import-residents', ['--force' => true])
            ->expectsOutputToContain('[IMPORT SUKSES]')
            ->assertSuccessful();

        $this->assertEquals(19, Household::count(), 'Tepat 19 Kartu Keluarga harus terdaftar.');
        $this->assertEquals(59, Resident::count(), 'Tepat 59 Warga harus terdaftar.');

        // Verifikasi Kepala Keluarga
        $kk001 = Household::where('id_kk', 'KK001')->first();
        $this->assertNotNull($kk001);
        $this->assertCount(4, $kk001->residents);
        $this->assertEquals('MOHD DODY SETIAWAN', $kk001->kepalaKeluarga?->nama);

        // Verifikasi warga dengan NIK valid (ter-enkripsi dan ter-hash)
        $w005 = Resident::where('id_warga', 'W005')->first();
        $this->assertNotNull($w005);
        $this->assertNotNull($w005->nik);
        $this->assertEquals(16, strlen($w005->nik));
        $this->assertNotNull($w005->nik_hash);
    }

    /**
     * Memverifikasi bahwa script import bersifat idempoten (dapat dijalankan ulang berkali-kali
     * tanpa membuat record ganda).
     */
    public function test_import_command_is_idempotent_on_rerun(): void
    {
        // Jalankan import pertama
        $this->artisan('simlibas:import-residents', ['--force' => true])->assertSuccessful();

        // Jalankan import kedua
        $this->artisan('simlibas:import-residents', ['--force' => true])->assertSuccessful();

        // Jumlah tetap 19 KK dan 59 Warga
        $this->assertEquals(19, Household::count());
        $this->assertEquals(59, Resident::count());
    }

    /**
     * Memverifikasi penanganan NIK kosong: NIK disimpan sebagai NULL dan tidak diisi nomor acak palsu.
     */
    public function test_import_command_handles_residents_without_nik_safely(): void
    {
        $this->artisan('simlibas:import-residents', ['--force' => true])->assertSuccessful();

        // Cek salah satu warga tanpa NIK (W001)
        $w001 = Resident::where('id_warga', 'W001')->first();
        $this->assertNotNull($w001);
        $this->assertNull($w001->nik, 'NIK warga tanpa identitas resmi harus NULL.');
        $this->assertNull($w001->nik_hash, 'nik_hash harus NULL.');
        $this->assertNull($w001->masked_nik, 'masked_nik harus NULL.');
    }

    /**
     * Memverifikasi penanganan duplikasi NIK (KIKI SUHARTI di KK012 vs KK013):
     * NIK pada KK utama tetap aktif, sedangkan NIK pada KK sekunder diset NULL
     * demi menjaga unique constraint database.
     */
    public function test_import_command_resolves_duplicate_nik_safely(): void
    {
        $this->artisan('simlibas:import-residents', ['--force' => true])->assertSuccessful();

        // Record pertama di KK012 (Istri) memiliki NIK aktif
        $w038 = Resident::where('id_warga', 'W038')->first();
        $this->assertNotNull($w038);
        $this->assertEquals('1571015211950022', $w038->nik);

        // Record kedua di KK013 (Anak) NIK diset NULL agar tidak tabrakan unique constraint
        $w042 = Resident::where('id_warga', 'W042')->first();
        $this->assertNotNull($w042);
        $this->assertNull($w042->nik);
        $this->assertNull($w042->nik_hash);
    }
}
