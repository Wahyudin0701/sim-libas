<?php

namespace Tests\Feature;

use App\Filament\Resources\CashTransactions\CashTransactionResource;
use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use App\Models\CashTransaction;
use App\Models\User;
use App\Models\WasteDeposit;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RwKelurahanAccessTest extends TestCase
{
    public function test_rw_account_exists_and_can_authenticate(): void
    {
        $rwUser = User::where('email', 'rw@simlibas.com')->first();

        $this->assertNotNull($rwUser, 'Akun rw@simlibas.com harus terdaftar di database.');
        $this->assertTrue(Hash::check('password', $rwUser->password), 'Password rw@simlibas.com harus "password".');
        $this->assertTrue($rwUser->hasRole('rw_kelurahan'), 'User harus memiliki role rw_kelurahan.');
        $this->assertEquals('aktif', $rwUser->status);
    }

    public function test_rw_role_has_only_read_only_permissions(): void
    {
        $rwUser = User::where('email', 'rw@simlibas.com')->first();
        $permissions = $rwUser->getAllPermissions()->pluck('name')->toArray();

        $this->assertContains('lihat_laporan_keuangan', $permissions);
        $this->assertContains('lihat_dampak_lingkungan', $permissions);

        // Pastikan TIDAK memiliki hak akses tulis atau kelola data privat warga
        $this->assertNotContains('lihat_data_warga', $permissions);
        $this->assertNotContains('kelola_buku_kas', $permissions);
        $this->assertNotContains('catat_setoran_sampah', $permissions);
        $this->assertNotContains('kelola_pengguna', $permissions);
    }

    public function test_rw_kelurahan_resource_level_write_methods_return_false(): void
    {
        $rwUser = User::where('email', 'rw@simlibas.com')->first();
        $this->actingAs($rwUser);

        // BUKU KAS
        $this->assertTrue(CashTransactionResource::canViewAny(), 'RW harus bisa melihat Buku Kas');
        $this->assertFalse(CashTransactionResource::canCreate(), 'RW TIDAK boleh bisa mencatat kas baru');
        
        $cashRecord = CashTransaction::first() ?? new CashTransaction();
        $this->assertFalse(CashTransactionResource::canEdit($cashRecord), 'RW TIDAK boleh bisa mengubah transaksi kas');
        $this->assertFalse(CashTransactionResource::canDelete($cashRecord), 'RW TIDAK boleh bisa menghapus transaksi kas');

        // BANK SAMPAH
        $this->assertTrue(WasteDepositResource::canViewAny(), 'RW harus bisa melihat Setoran Bank Sampah');
        $this->assertFalse(WasteDepositResource::canCreate(), 'RW TIDAK boleh bisa mencatat setoran sampah');

        $wasteRecord = WasteDeposit::first() ?? new WasteDeposit();
        $this->assertFalse(WasteDepositResource::canEdit($wasteRecord), 'RW TIDAK boleh bisa mengubah setoran sampah');
        $this->assertFalse(WasteDepositResource::canDelete($wasteRecord), 'RW TIDAK boleh bisa menghapus setoran sampah');
    }

    public function test_rw_kelurahan_forbidden_from_private_citizen_modules(): void
    {
        $rwUser = User::where('email', 'rw@simlibas.com')->first();

        // Modul Privat Kependudukan & Administrasi RT wajib 403 Forbidden
        $this->actingAs($rwUser)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($rwUser)->get('/admin/households')->assertStatus(403);
        $this->actingAs($rwUser)->get('/admin/letters')->assertStatus(403);
        $this->actingAs($rwUser)->get('/admin/complaints')->assertStatus(403);
        $this->actingAs($rwUser)->get('/admin/fee-types')->assertStatus(403);
        $this->actingAs($rwUser)->get('/admin/invoices')->assertStatus(403);

        // Modul Monitoring Makro harus dapat diakses (200 OK)
        $this->actingAs($rwUser)->get('/admin/cash-transactions')->assertStatus(200);
        $this->actingAs($rwUser)->get('/admin/waste-deposits')->assertStatus(200);
    }
}
