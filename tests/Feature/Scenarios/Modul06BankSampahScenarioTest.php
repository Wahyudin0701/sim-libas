<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits;
use App\Filament\Resources\WasteTypes\Pages\CreateWasteType;
use App\Filament\Resources\WasteTypes\Pages\EditWasteType;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use App\Models\WasteWallet;
use App\Models\WasteWalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 6: Bank Sampah Digital Pinang Masak Lestari (End-to-End)
 * Skenario: TC-079 s/d TC-094
 */
class Modul06BankSampahScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $koorSampah;
    protected User $bendahara;
    protected User $koorKeamanan;
    protected User $rwKelurahan;
    protected User $wargaKepala;
    protected Household $householdA;
    protected WasteType $wasteTypeKardus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->koorSampah = User::where('email', 'banksampah@simlibas.id')->first();
        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->first();
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

        // Warga KK A (Dedi Kurniawan)
        $this->wargaKepala = User::where('email', 'warga.dedi@simlibas.id')->first();
        $resident = Resident::where('user_id', $this->wargaKepala->id)->first();
        $this->householdA = $resident->household;

        $this->wasteTypeKardus = WasteType::first() ?? WasteType::create([
            'nama' => 'Kardus / Karton Coklat',
            'kategori' => 'anorganik',
            'harga_per_kg' => 2000,
            'satuan' => 'kg',
            'is_active' => true,
        ]);
    }

    /**
     * TC-079: Koordinator Sampah menambah jenis dan harga komoditas sampah
     */
    public function test_tc_079_tambah_komoditas_sampah_baru(): void
    {
        Livewire::actingAs($this->koorSampah)
            ->test(CreateWasteType::class)
            ->fillForm([
                'nama' => 'Kardus Tebal Duplek',
                'kategori' => 'anorganik',
                'harga_per_kg' => 2500,
                'satuan' => 'kg',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('waste_types', [
            'nama' => 'Kardus Tebal Duplek',
            'harga_per_kg' => 2500,
            'is_active' => true,
        ]);
    }

    /**
     * TC-080: Koordinator Sampah memperbarui harga pasar komoditas sampah
     */
    public function test_tc_080_update_harga_pasar_komoditas_sampah(): void
    {
        Livewire::actingAs($this->koorSampah)
            ->test(EditWasteType::class, ['record' => $this->wasteTypeKardus->id])
            ->fillForm([
                'nama' => $this->wasteTypeKardus->nama,
                'kategori' => $this->wasteTypeKardus->kategori,
                'harga_per_kg' => 2800,
                'satuan' => 'kg',
                'is_active' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->wasteTypeKardus->refresh();
        $this->assertEquals(2800, $this->wasteTypeKardus->harga_per_kg);
    }

    /**
     * TC-081: Koordinator Sampah mencatat setoran penimbangan sampah warga
     */
    public function test_tc_081_catat_setoran_penimbangan_sampah_warga(): void
    {
        // Hitung saldo awal dompet sampah
        $wallet = WasteWallet::firstOrCreate(['household_id' => $this->householdA->id], ['saldo' => 0]);
        $saldoAwal = (float) $wallet->saldo;

        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->set('form_household_id', $this->householdA->id)
            ->set('form_waste_type_id', $this->wasteTypeKardus->id)
            ->set('form_berat_kg', '10.5')
            ->set('form_harga_saat_setor', (string) $this->wasteTypeKardus->harga_per_kg)
            ->call('saveDeposit')
            ->assertHasNoErrors();

        $expectedNilai = round(10.5 * $this->wasteTypeKardus->harga_per_kg, 2);

        $this->assertDatabaseHas('waste_deposits', [
            'household_id' => $this->householdA->id,
            'waste_type_id' => $this->wasteTypeKardus->id,
            'berat_kg' => 10.5,
            'nilai' => $expectedNilai,
        ]);

        $wallet->refresh();
        $this->assertEquals($saldoAwal + $expectedNilai, (float) $wallet->saldo);
    }

    /**
     * TC-082: Verifikasi pencatatan mutasi kredit tabungan di waste_wallet_transactions
     */
    public function test_tc_082_verifikasi_mutasi_kredit_tabungan(): void
    {
        $wallet = WasteWallet::firstOrCreate(['household_id' => $this->householdA->id], ['saldo' => 5000]);
        $saldoSebelum = (float) $wallet->saldo;

        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->set('form_household_id', $this->householdA->id)
            ->set('form_waste_type_id', $this->wasteTypeKardus->id)
            ->set('form_berat_kg', '5.0')
            ->set('form_harga_saat_setor', '2000')
            ->call('saveDeposit');

        $wallet->refresh();

        $this->assertDatabaseHas('waste_wallet_transactions', [
            'household_id' => $this->householdA->id,
            'jenis' => 'setoran',
            'jumlah' => 10000,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSebelum + 10000,
        ]);
    }

    /**
     * TC-083: Warga memverifikasi saldo tabungan bank sampah di dashboard
     */
    public function test_tc_083_warga_memverifikasi_saldo_sampah_di_dashboard(): void
    {
        $wallet = WasteWallet::firstOrCreate(['household_id' => $this->householdA->id], ['saldo' => 0]);
        $wallet->saldo = 75000;
        $wallet->save();

        $response = $this->actingAs($this->wargaKepala)->get('/warga/dashboard');
        $response->assertOk();
        $response->assertSee('Tabungan Sampah');
        $response->assertSee('75.000');
    }

    /**
     * TC-084: Mengubah berat setoran sampah dan penyesuaian saldo dompet otomatis
     */
    public function test_tc_084_edit_setoran_sampah_dan_penyesuaian_saldo(): void
    {
        $wallet = WasteWallet::firstOrCreate(['household_id' => $this->householdA->id], ['saldo' => 0]);
        $saldoAwal = (float) $wallet->saldo;

        $deposit = WasteDeposit::create([
            'household_id' => $this->householdA->id,
            'waste_type_id' => $this->wasteTypeKardus->id,
            'berat_kg' => 5.0,
            'harga_saat_setor' => 2000,
            'nilai' => 10000,
            'dicatat_oleh' => $this->koorSampah->id,
        ]);

        // Edit berat dari 5.0 menjadi 8.0 (nilai naik dari 10.000 menjadi 16.000, selisih +6.000)
        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->call('openEdit', $deposit->id)
            ->set('form_berat_kg', '8.0')
            ->set('form_harga_saat_setor', '2000')
            ->call('saveDeposit');

        $deposit->refresh();
        $this->assertEquals(8.0, $deposit->berat_kg);
        $this->assertEquals(16000, $deposit->nilai);

        $wallet->refresh();
        $this->assertEquals($saldoAwal + 6000, (float) $wallet->saldo);

        // Verifikasi record penyesuaian
        $this->assertDatabaseHas('waste_wallet_transactions', [
            'household_id' => $this->householdA->id,
            'jumlah' => 6000,
            'keterangan' => 'Penyesuaian koreksi nilai setoran sampah #' . $deposit->id,
        ]);
    }

    /**
     * TC-085: Menghapus setoran sampah yang keliru dan sinkronisasi saldo tabungan
     */
    public function test_tc_085_hapus_setoran_sampah_dan_sinkronisasi_saldo(): void
    {
        $wallet = WasteWallet::firstOrCreate(['household_id' => $this->householdA->id], ['saldo' => 0]);
        // Pastikan saldo cukup untuk dikurangi 10.000
        $wallet->saldo = max(10000, (float) $wallet->saldo);
        $wallet->save();
        $saldoAwal = (float) $wallet->saldo;

        $deposit = WasteDeposit::create([
            'household_id' => $this->householdA->id,
            'waste_type_id' => $this->wasteTypeKardus->id,
            'berat_kg' => 5.0,
            'harga_saat_setor' => 2000,
            'nilai' => 10000,
            'dicatat_oleh' => $this->koorSampah->id,
        ]);

        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->call('deleteDeposit', $deposit->id);

        $this->assertDatabaseMissing('waste_deposits', ['id' => $deposit->id]);

        $wallet->refresh();
        $this->assertEquals($saldoAwal - 10000, (float) $wallet->saldo);

        // Verifikasi mutasi pemotongan/pembatalan
        $this->assertDatabaseHas('waste_wallet_transactions', [
            'household_id' => $this->householdA->id,
            'jenis' => 'pencairan',
            'jumlah' => 10000,
        ]);
    }

    /**
     * TC-086: Pencarian setoran sampah berdasarkan nama KK
     */
    public function test_tc_086_pencarian_setoran_sampah(): void
    {
        $deposit = WasteDeposit::create([
            'household_id' => $this->householdA->id,
            'waste_type_id' => $this->wasteTypeKardus->id,
            'berat_kg' => 4.0,
            'harga_saat_setor' => 2000,
            'nilai' => 8000,
            'catatan' => 'Pencarian unik sampah blok A',
            'dicatat_oleh' => $this->koorSampah->id,
        ]);

        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->set('search', 'blok A')
            ->assertSee('Pencarian unik sampah blok A');
    }

    /**
     * TC-087: Filter setoran berdasarkan jenis komoditas sampah
     */
    public function test_tc_087_filter_setoran_berdasarkan_jenis_sampah(): void
    {
        $typePlastik = WasteType::create([
            'nama' => 'Botol Plastik PET Bersih',
            'kategori' => 'anorganik',
            'harga_per_kg' => 3500,
            'satuan' => 'kg',
            'is_active' => true,
        ]);

        $deposit = WasteDeposit::create([
            'household_id' => $this->householdA->id,
            'waste_type_id' => $typePlastik->id,
            'berat_kg' => 3.0,
            'harga_saat_setor' => 3500,
            'nilai' => 10500,
            'catatan' => 'Uji filter plastik',
            'dicatat_oleh' => $this->koorSampah->id,
        ]);

        Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class)
            ->set('wasteTypeFilter', (string) $typePlastik->id)
            ->assertSee('Uji filter plastik');
    }

    /**
     * TC-088: Ekspor rekapitulasi setoran sampah ke CSV
     */
    public function test_tc_088_ekspor_rekapitulasi_setoran_sampah_ke_csv(): void
    {
        $component = Livewire::actingAs($this->koorSampah)
            ->test(ListWasteDeposits::class);

        $response = $component->instance()->export();
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('setoran-bank-sampah-rt15', $response->headers->get('Content-Disposition'));
    }

    /**
     * TC-089: Dashboard khusus Koordinator Bank Sampah di /admin
     */
    public function test_tc_089_dashboard_khusus_koordinator_bank_sampah(): void
    {
        $response = $this->actingAs($this->koorSampah)->get('/admin');
        $response->assertOk();
        $response->assertSee('Koordinator Bank Sampah');
        $response->assertSee('Bank Sampah');
    }

    /**
     * TC-090: Koordinator Bank Sampah dilarang mengakses modul kependudukan
     */
    public function test_tc_090_koor_sampah_dilarang_akses_kependudukan(): void
    {
        $responseResidents = $this->actingAs($this->koorSampah)->get('/admin/residents');
        $responseResidents->assertForbidden();

        $responseHouseholds = $this->actingAs($this->koorSampah)->get('/admin/households');
        $responseHouseholds->assertForbidden();
    }

    /**
     * TC-091: Koordinator Bank Sampah dilarang mengakses Buku Kas RT dan Tagihan
     */
    public function test_tc_091_koor_sampah_dilarang_akses_kas_dan_tagihan(): void
    {
        $responseCash = $this->actingAs($this->koorSampah)->get('/admin/cash-transactions');
        $responseCash->assertForbidden();

        $responseInvoices = $this->actingAs($this->koorSampah)->get('/admin/invoices');
        $responseInvoices->assertForbidden();
    }

    /**
     * TC-092: RW / Kelurahan memantau setoran sampah (View Only)
     */
    public function test_tc_092_rw_kelurahan_memantau_setoran_sampah(): void
    {
        $response = $this->actingAs($this->rwKelurahan)->get('/admin/waste-deposits');
        $response->assertOk();
        $response->assertSee('Bank Sampah Pinang Masak');
    }

    /**
     * TC-093: Bendahara RT dilarang mengakses menu Setoran Sampah
     */
    public function test_tc_093_bendahara_dilarang_akses_setoran_sampah(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/admin/waste-deposits');
        $response->assertForbidden();
    }

    /**
     * TC-094: Koordinator Keamanan dilarang mengakses menu Setoran Sampah
     */
    public function test_tc_094_koor_keamanan_dilarang_akses_setoran_sampah(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin/waste-deposits');
        $response->assertForbidden();
    }
}
