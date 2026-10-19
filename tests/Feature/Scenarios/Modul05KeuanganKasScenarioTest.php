<?php

namespace Tests\Feature\Scenarios;

use App\Filament\Resources\CashTransactions\Pages\CreateCashTransaction;
use App\Filament\Resources\FeeTypes\Pages\CreateFeeType;
use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Models\CashTransaction;
use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Terotomatisasi Skenario Modul 5: Keuangan, Tagihan Iuran & Kas RT (End-to-End)
 * Skenario: TC-061 s/d TC-078
 */
class Modul05KeuanganKasScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $bendahara;
    protected User $ketuaRt;
    protected User $sekretaris;
    protected User $koorKeamanan;
    protected User $rwKelurahan;
    protected User $wargaKepala;
    protected Household $householdA;
    protected Household $householdB;
    protected User $wargaLain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->bendahara = User::where('email', 'bendahara@simlibas.id')->first();
        $this->ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->first();
        $this->sekretaris = User::where('email', 'sekretaris@simlibas.id')->first();
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

        // Warga KK B (Keluarga Lain)
        $this->householdB = Household::where('id', '!=', $this->householdA->id)->first();
        $residentLain = $this->householdB->residents()->first();
        if (!$residentLain->user_id) {
            $userLain = User::create([
                'name' => $residentLain->nama,
                'email' => $residentLain->nik . '@warga.simlibas.id',
                'password' => bcrypt('password'),
                'status' => 'aktif',
            ]);
            $userLain->assignRole('warga');
            $residentLain->update(['user_id' => $userLain->id]);
            $this->wargaLain = $userLain;
        } else {
            $this->wargaLain = $residentLain->user;
        }
    }

    /**
     * TC-061: Bendahara menambah jenis iuran baru (FeeType)
     */
    public function test_tc_061_bendahara_menambah_jenis_iuran_baru(): void
    {
        Livewire::actingAs($this->bendahara)
            ->test(CreateFeeType::class)
            ->fillForm([
                'nama' => 'Iuran Pembangunan Swadaya',
                'deskripsi' => 'Iuran swadaya perbaikan jalan RT 15',
                'nominal' => 50000,
                'periode' => 'bulanan',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('fee_types', [
            'nama' => 'Iuran Pembangunan Swadaya',
            'nominal' => 50000,
            'periode' => 'bulanan',
            'is_active' => true,
        ]);
    }

    /**
     * TC-062: Bendahara menerbitkan tagihan iuran (Invoice) untuk KK
     */
    public function test_tc_062_bendahara_menerbitkan_tagihan_iuran(): void
    {
        $feeType = FeeType::first();

        Livewire::actingAs($this->bendahara)
            ->test(CreateInvoice::class)
            ->fillForm([
                'household_id' => $this->householdA->id,
                'fee_type_id' => $feeType->id,
                'periode_bulan' => 9,
                'periode_tahun' => 2028,
                'jumlah' => $feeType->nominal ?? 25000,
                'status' => 'belum_bayar',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('invoices', [
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 9,
            'periode_tahun' => 2028,
            'status' => 'belum_bayar',
        ]);
    }

    /**
     * TC-063: Warga melihat daftar tagihan tertunda di dashboard dan portal iuran
     */
    public function test_tc_063_warga_melihat_daftar_tagihan_tertunda(): void
    {
        $feeType = FeeType::first();
        Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 11,
            'periode_tahun' => 2026,
            'jumlah' => 35000,
            'status' => 'belum_bayar',
        ]);

        $response = $this->actingAs($this->wargaKepala)->get('/warga/iuran');
        $response->assertOk();
        $response->assertSee('Iuran Bulanan');
        $response->assertSee('Rp 35.000');
        $response->assertSee('Belum Lunas');
    }

    /**
     * TC-064: Warga mengunggah konfirmasi bukti bayar iuran transfer
     */
    public function test_tc_064_warga_mengunggah_konfirmasi_bukti_bayar_transfer(): void
    {
        Storage::fake('public');
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 12,
            'periode_tahun' => 2026,
            'jumlah' => 30000,
            'status' => 'belum_bayar',
        ]);

        $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 800);

        $response = $this->actingAs($this->wargaKepala)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => 30000,
            'metode' => 'transfer',
            'bukti' => $file,
            'catatan' => 'Transfer via BCA',
        ]);

        $response->assertRedirect(route('warga.iuran'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'jumlah' => 30000,
            'metode' => 'transfer',
            'status' => 'pending',
        ]);
    }

    /**
     * TC-065: Validasi cegah pembayaran ganda saat status pembayaran masih pending
     */
    public function test_tc_065_validasi_cegah_pembayaran_ganda_status_pending(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 1,
            'periode_tahun' => 2027,
            'jumlah' => 25000,
            'status' => 'belum_bayar',
        ]);

        // Buat pembayaran pertama yang masih pending
        Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 25000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        // Coba bayar lagi
        $response = $this->actingAs($this->wargaKepala)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => 25000,
            'metode' => 'transfer',
        ]);

        $response->assertSessionHasErrors(['pembayaran']);
    }

    /**
     * TC-066: Validasi cegah pembayaran pada tagihan yang sudah lunas
     */
    public function test_tc_066_validasi_cegah_pembayaran_pada_tagihan_lunas(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 2,
            'periode_tahun' => 2027,
            'jumlah' => 25000,
            'status' => 'lunas',
        ]);

        $response = $this->actingAs($this->wargaKepala)->post(route('warga.iuran.pay', $invoice), [
            'jumlah' => 25000,
            'metode' => 'transfer',
        ]);

        $response->assertSessionHasErrors(['pembayaran']);
    }

    /**
     * TC-067: Keamanan tagihan: Warga dilarang membayar tagihan milik KK lain
     */
    public function test_tc_067_warga_dilarang_membayar_tagihan_kk_lain(): void
    {
        $feeType = FeeType::first();
        $invoiceKkLain = Invoice::create([
            'household_id' => $this->householdB->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 3,
            'periode_tahun' => 2027,
            'jumlah' => 25000,
            'status' => 'belum_bayar',
        ]);

        $response = $this->actingAs($this->wargaKepala)->post(route('warga.iuran.pay', $invoiceKkLain), [
            'jumlah' => 25000,
            'metode' => 'transfer',
        ]);

        $response->assertForbidden();
    }

    /**
     * TC-068: Bendahara menerima notifikasi konfirmasi pembayaran masuk
     */
    public function test_tc_068_bendahara_menerima_notifikasi_konfirmasi_pembayaran(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 4,
            'periode_tahun' => 2027,
            'jumlah' => 40000,
            'status' => 'belum_bayar',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 40000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        \App\Services\NotificationService::notifyNewPayment($payment);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->bendahara->id,
        ]);
        $this->assertTrue(Notification::where('user_id', $this->bendahara->id)->where('judul', 'like', '%Pembayaran%')->exists());
    }

    /**
     * TC-069: Bendahara memvalidasi lunas pembayaran warga
     */
    public function test_tc_069_bendahara_memvalidasi_lunas_pembayaran_warga(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 5,
            'periode_tahun' => 2027,
            'jumlah' => 50000,
            'status' => 'belum_bayar',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 50000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        Livewire::actingAs($this->bendahara)
            ->test(ListPayments::class)
            ->callTableAction('validasi', $payment);

        $payment->refresh();
        $invoice->refresh();

        $this->assertEquals('valid', $payment->status);
        $this->assertEquals($this->bendahara->id, $payment->divalidasi_oleh);
        $this->assertEquals('lunas', $invoice->status);

        // Otomatis tercatat di Buku Kas RT
        $this->assertDatabaseHas('cash_transactions', [
            'jenis' => 'masuk',
            'kategori' => 'Iuran Warga',
            'jumlah' => 50000,
        ]);

        // Notifikasi terkirim ke warga
        $this->assertTrue(Notification::where('user_id', $this->wargaKepala->id)->where('judul', 'like', '%Validasi%')->exists());
    }

    /**
     * TC-070: Bendahara menolak bukti pembayaran tidak valid
     */
    public function test_tc_070_bendahara_menolak_bukti_pembayaran(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 6,
            'periode_tahun' => 2027,
            'jumlah' => 35000,
            'status' => 'belum_bayar',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 35000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        Livewire::actingAs($this->bendahara)
            ->test(ListPayments::class)
            ->callTableAction('tolak', $payment, [
                'catatan' => 'Bukti transfer buram dan nominal tidak terbaca.',
            ]);

        $payment->refresh();
        $invoice->refresh();

        $this->assertEquals('ditolak', $payment->status);
        $this->assertEquals('Bukti transfer buram dan nominal tidak terbaca.', $payment->catatan);
        $this->assertEquals('belum_bayar', $invoice->status);

        // Notifikasi penolakan ke warga
        $this->assertTrue(Notification::where('user_id', $this->wargaKepala->id)->where('judul', 'like', '%Ditolak%')->exists());
    }

    /**
     * TC-071: Warga memverifikasi status tagihan setelah divalidasi lunas
     */
    public function test_tc_071_warga_memverifikasi_status_tagihan_lunas(): void
    {
        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 7,
            'periode_tahun' => 2027,
            'jumlah' => 25000,
            'status' => 'lunas',
        ]);

        $response = $this->actingAs($this->wargaKepala)->get('/warga/iuran');
        $response->assertOk();
        $response->assertSee('Lunas');
    }

    /**
     * TC-072: Verifikasi otomatisasi pencatatan kas masuk dari iuran
     */
    public function test_tc_072_verifikasi_otomatisasi_kas_masuk_dari_iuran(): void
    {
        $cashCountBefore = CashTransaction::where('jenis', 'masuk')->count();

        $feeType = FeeType::first();
        $invoice = Invoice::create([
            'household_id' => $this->householdA->id,
            'fee_type_id' => $feeType->id,
            'periode_bulan' => 8,
            'periode_tahun' => 2027,
            'jumlah' => 45000,
            'status' => 'belum_bayar',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => 45000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        Livewire::actingAs($this->bendahara)
            ->test(ListPayments::class)
            ->callTableAction('validasi', $payment);

        $cashCountAfter = CashTransaction::where('jenis', 'masuk')->count();
        $this->assertEquals($cashCountBefore + 1, $cashCountAfter);

        $this->assertDatabaseHas('cash_transactions', [
            'jenis' => 'masuk',
            'kategori' => 'Iuran Warga',
            'jumlah' => 45000,
        ]);
    }

    /**
     * TC-073: Pencatatan pengeluaran kas RT manual
     */
    public function test_tc_073_pencatatan_pengeluaran_kas_rt_manual(): void
    {
        Livewire::actingAs($this->bendahara)
            ->test(CreateCashTransaction::class)
            ->fillForm([
                'jenis' => 'keluar',
                'kategori' => 'Perbaikan Lampu Jalan',
                'deskripsi' => 'Penggantian 2 bohlam LED jalan blok Kenanga',
                'jumlah' => 120000,
                'tanggal' => now()->format('Y-m-d'),
                'dicatat_oleh' => $this->bendahara->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('cash_transactions', [
            'jenis' => 'keluar',
            'kategori' => 'Perbaikan Lampu Jalan',
            'jumlah' => 120000,
        ]);
    }

    /**
     * TC-074: Verifikasi keakuratan saldo kas RT di widget KPI
     */
    public function test_tc_074_verifikasi_keakuratan_saldo_kas_rt(): void
    {
        CashTransaction::create([
            'jenis' => 'masuk',
            'kategori' => 'Donasi Warga',
            'deskripsi' => 'Uji KPI kas masuk',
            'jumlah' => 200000,
            'tanggal' => now(),
            'dicatat_oleh' => $this->bendahara->id,
        ]);

        CashTransaction::create([
            'jenis' => 'keluar',
            'kategori' => 'Konsumsi Rapat',
            'deskripsi' => 'Uji KPI kas keluar',
            'jumlah' => 50000,
            'tanggal' => now(),
            'dicatat_oleh' => $this->bendahara->id,
        ]);

        $masuk = CashTransaction::where('jenis', 'masuk')->sum('jumlah');
        $keluar = CashTransaction::where('jenis', 'keluar')->sum('jumlah');
        $saldo = $masuk - $keluar;

        $response = $this->actingAs($this->bendahara)->get('/admin');
        $response->assertOk();
        $response->assertSee(number_format($saldo, 0, ',', '.'));
    }

    /**
     * TC-075: Role RW / Kelurahan memantau transparansi kas RT (View Only)
     */
    public function test_tc_075_rw_kelurahan_memantau_transparansi_kas_rt(): void
    {
        $response = $this->actingAs($this->rwKelurahan)->get('/admin/cash-transactions');
        $response->assertOk();
        $response->assertSee('Buku Kas RT');

        // Memastikan RW tidak memiliki akses membuat transaksi baru
        $responseCreate = $this->actingAs($this->rwKelurahan)->get('/admin/cash-transactions/create');
        $responseCreate->assertForbidden();
    }

    /**
     * TC-076: RW / Kelurahan dilarang mengakses modul kependudukan dan surat
     */
    public function test_tc_076_rw_kelurahan_dilarang_akses_kependudukan_dan_surat(): void
    {
        $responseResidents = $this->actingAs($this->rwKelurahan)->get('/admin/residents');
        $responseResidents->assertForbidden();

        $responseLetters = $this->actingAs($this->rwKelurahan)->get('/admin/letters');
        $responseLetters->assertForbidden();
    }

    /**
     * TC-077: Sekretaris RT dilarang mengakses menu Buku Kas RT
     */
    public function test_tc_077_sekretaris_dilarang_akses_buku_kas(): void
    {
        $response = $this->actingAs($this->sekretaris)->get('/admin/cash-transactions');
        $response->assertForbidden();
    }

    /**
     * TC-078: Koordinator Keamanan dilarang mengakses Buku Kas RT
     */
    public function test_tc_078_koor_keamanan_dilarang_akses_buku_kas(): void
    {
        $response = $this->actingAs($this->koorKeamanan)->get('/admin/cash-transactions');
        $response->assertForbidden();
    }
}
