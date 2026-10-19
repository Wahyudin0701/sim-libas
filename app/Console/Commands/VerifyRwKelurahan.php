<?php

namespace App\Console\Commands;

use App\Filament\Resources\CashTransactions\CashTransactionResource;
use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use App\Models\CashTransaction;
use App\Models\User;
use App\Models\WasteDeposit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerifyRwKelurahan extends Command
{
    protected $signature = 'simlibas:verify-rw';
    protected $description = 'Verifikasi akun RW dan validasi hak akses read-only';

    public function handle(): int
    {
        $this->info('=== VERIFIKASI SEMUA AKUN PENGURUS & AUTENTIKASI ===');

        $accounts = [
            'admin@simlibas.com' => 'super_admin',
            'rt@simlibas.com' => 'ketua_rt',
            'sekretaris@simlibas.com' => 'sekretaris',
            'bendahara@simlibas.com' => 'bendahara',
            'banksampah@simlibas.com' => 'koordinator_bank_sampah',
            'keamanan@simlibas.com' => 'koordinator_keamanan',
            'rw@simlibas.com' => 'rw_kelurahan',
        ];

        foreach ($accounts as $email => $expectedRole) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("FAIL: Akun {$email} TIDAK DITEMUKAN!");
                return 1;
            }
            $passOk = Hash::check('password', $user->password);
            $hasRole = $user->hasRole($expectedRole);
            $canAccess = $user->canAccessPanel(filament()->getPanel('admin'));

            $this->line(sprintf(
                '[%s] %s | Role: %s | Status: %s | Pass: %s | PanelAccess: %s',
                ($passOk && $hasRole && $canAccess && $user->status === 'aktif') ? 'OK' : 'FAIL',
                str_pad($email, 25),
                $expectedRole,
                $user->status,
                $passOk ? 'VALID' : 'INVALID',
                $canAccess ? 'ALLOWED' : 'DENIED'
            ));
        }

        $this->info('=== REKAP DATA SEEDER AWAL (MINIMAL 5 DATA PER TABEL) ===');
        $models = [
            'Users (Akun Pengguna)' => \App\Models\User::count(),
            'Households (Kartu Keluarga)' => \App\Models\Household::count(),
            'Residents (Data Warga)' => \App\Models\Resident::count(),
            'LetterTypes (Jenis Surat)' => \App\Models\LetterType::count(),
            'Letters (Surat Pengantar)' => \App\Models\Letter::count(),
            'FeeTypes (Jenis Iuran)' => \App\Models\FeeType::count(),
            'Invoices (Tagihan Iuran)' => \App\Models\Invoice::count(),
            'Payments (Pembayaran Iuran)' => \App\Models\Payment::count(),
            'CashTransactions (Buku Kas RT)' => \App\Models\CashTransaction::count(),
            'Announcements (Pengumuman)' => \App\Models\Announcement::count(),
            'Events (Agenda & Kegiatan)' => \App\Models\Event::count(),
            'Complaints (Pengaduan Warga)' => \App\Models\Complaint::count(),
            'PatrolSchedules (Jadwal Ronda)' => \App\Models\PatrolSchedule::count(),
            'GuestReports (Buku Tamu 1x24 Jam)' => \App\Models\GuestReport::count(),
            'WasteTypes (Jenis Sampah)' => \App\Models\WasteType::count(),
            'WasteDeposits (Setoran Bank Sampah)' => \App\Models\WasteDeposit::count(),
            'WasteWallets (Buku Tabungan Sampah)' => \App\Models\WasteWallet::count(),
            'WasteWalletTransactions (Mutasi Tabungan)' => \App\Models\WasteWalletTransaction::count(),
            'MsmeProfiles (Katalog UMKM Warga)' => \App\Models\MsmeProfile::count(),
        ];

        foreach ($models as $name => $count) {
            $status = $count >= 5 ? 'LENGKAP' : ($count > 0 ? 'TERSEDIA' : 'KOSONG');
            $this->line(sprintf('  [%s] %s: %d record', $status, str_pad($name, 42), $count));
        }

        $this->info('=== VERIFIKASI AKUN RW & HAK AKSES SISTEM ===');

        // 1. Akun RW
        $rw = User::where('email', 'rw@simlibas.com')->first();
        if (!$rw) {
            $this->error('FAIL: Akun rw@simlibas.com tidak ditemukan!');
            return 1;
        }

        $this->line("1. Akun Ditemukan: {$rw->name} ({$rw->email})");
        $this->line("   - Status: {$rw->status}");
        $this->line("   - Phone: {$rw->phone}");

        // 2. Password Check
        if (Hash::check('password', $rw->password)) {
            $this->info('2. Password Matching: "password" [PASS]');
        } else {
            $this->error('2. Password Matching: GAGAL [FAIL]');
            return 1;
        }

        // 3. Roles & Permissions
        $roles = $rw->getRoleNames()->toArray();
        $this->line('3. Roles: ' . implode(', ', $roles));
        if (!in_array('rw_kelurahan', $roles)) {
            $this->error('FAIL: Akun tidak memiliki role rw_kelurahan!');
            return 1;
        }

        $permissions = $rw->getAllPermissions()->pluck('name')->toArray();
        $this->line('   Permissions Terdaftar: ' . implode(', ', $permissions));

        // 4. Resource Gates
        Auth::login($rw);

        $canViewCash = CashTransactionResource::canViewAny();
        $canCreateCash = CashTransactionResource::canCreate();
        $cashRecord = CashTransaction::first() ?? new CashTransaction();
        $canEditCash = CashTransactionResource::canEdit($cashRecord);
        $canDeleteCash = CashTransactionResource::canDelete($cashRecord);

        $this->line('4. Buku Kas RT:');
        $this->line('   - canViewAny: ' . ($canViewCash ? 'TRUE [PASS]' : 'FALSE [FAIL]'));
        $this->line('   - canCreate: ' . (!$canCreateCash ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));
        $this->line('   - canEdit: ' . (!$canEditCash ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));
        $this->line('   - canDelete: ' . (!$canDeleteCash ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));

        $canViewWaste = WasteDepositResource::canViewAny();
        $canCreateWaste = WasteDepositResource::canCreate();
        $wasteRecord = WasteDeposit::first() ?? new WasteDeposit();
        $canEditWaste = WasteDepositResource::canEdit($wasteRecord);
        $canDeleteWaste = WasteDepositResource::canDelete($wasteRecord);

        $this->line('5. Bank Sampah:');
        $this->line('   - canViewAny: ' . ($canViewWaste ? 'TRUE [PASS]' : 'FALSE [FAIL]'));
        $this->line('   - canCreate: ' . (!$canCreateWaste ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));
        $this->line('   - canEdit: ' . (!$canEditWaste ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));
        $this->line('   - canDelete: ' . (!$canDeleteWaste ? 'FALSE (Read-Only) [PASS]' : 'TRUE [FAIL - SEHARUSNYA FALSE]'));

        // 6. Check canAccessPanel
        $canAccessPanel = $rw->canAccessPanel(filament()->getCurrentPanel() ?? filament()->getPanel('admin'));
        $this->line('6. Akses Panel Admin: ' . ($canAccessPanel ? 'TRUE [PASS]' : 'FALSE [FAIL]'));

        $allPass = $canViewCash && !$canCreateCash && !$canEditCash && !$canDeleteCash
            && $canViewWaste && !$canCreateWaste && !$canEditWaste && !$canDeleteWaste
            && $canAccessPanel;

        if ($allPass) {
            $this->info('=== SEMUA PENGECEKAN HAK AKSES READ-ONLY RW: BERHASIL LULUS 100% ===');
            return 0;
        }

        $this->error('=== ADA PENGECEKAN YANG GAGAL ===');
        return 1;
    }
}
