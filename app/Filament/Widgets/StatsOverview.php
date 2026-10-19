<?php

namespace App\Filament\Widgets;

use App\Models\CashTransaction;
use App\Models\Complaint;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\PatrolSchedule;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-overview';

    public function getViewData(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $roles = $user ? $user->getRoleNames() : collect();

        $roleLabel = match (true) {
            $roles->contains('super_admin') => 'Super Administrator',
            $roles->contains('ketua_rt') => 'Ketua RT 15',
            $roles->contains('sekretaris') => 'Sekretaris RT 15',
            $roles->contains('bendahara') => 'Bendahara RT 15',
            $roles->contains('koordinator_bank_sampah') => 'Koordinator Bank Sampah',
            $roles->contains('koordinator_keamanan') => 'Koordinator Keamanan',
            $roles->contains('rw_kelurahan') => 'RW / Kelurahan',
            default => 'Pengurus RT 15',
        };

        $hour = (int) now()->format('H');
        $greeting = match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        // Hak akses modul
        $canKependudukan = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('lihat_data_warga')));
        $canLetters = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('periksa_surat')));
        $canCash = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'bendahara', 'rw_kelurahan']) || $user->can('lihat_laporan_keuangan')));
        $canInvoices = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'bendahara']) || $user->can('terbitkan_tagihan')));
        $canComplaints = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('lihat_semua_pengaduan')));
        $canBankSampah = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'koordinator_bank_sampah', 'rw_kelurahan']) || $user->can('catat_setoran_sampah') || $user->can('lihat_dampak_lingkungan')));
        $canKeamanan = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'koordinator_keamanan']) || $user->can('kelola_jadwal_ronda')));

        // Mode Tampilan Peran Khusus
        $isKoordinatorBankSampah = (bool) ($user && $user->hasRole('koordinator_bank_sampah') && ! $user->hasAnyRole(['super_admin', 'admin', 'ketua_rt']));
        $isKoordinatorKeamanan = (bool) ($user && $user->hasRole('koordinator_keamanan') && ! $user->hasAnyRole(['super_admin', 'admin', 'ketua_rt']));
        $isBendahara = (bool) ($user && $user->hasRole('bendahara') && ! $user->hasAnyRole(['super_admin', 'admin', 'ketua_rt']));
        $isSekretaris = (bool) ($user && $user->hasRole('sekretaris') && ! $user->hasAnyRole(['super_admin', 'admin', 'ketua_rt']));

        // Data Umum
        $totalWarga = $canKependudukan ? Resident::where('status_kependudukan', 'aktif')->count() : 0;
        $totalKk = $canKependudukan ? Household::count() : 0;
        $suratBerjalan = $canLetters ? Letter::whereIn('status', ['diajukan', 'diperiksa'])->count() : 0;
        $tagihanTertunda = $canInvoices ? Invoice::where('status', 'belum_bayar')->sum('jumlah') : 0;
        $pemasukan = $canCash ? CashTransaction::where('jenis', 'masuk')->sum('jumlah') : 0;
        $pengeluaran = $canCash ? CashTransaction::where('jenis', 'keluar')->sum('jumlah') : 0;
        $saldoKas = $pemasukan - $pengeluaran;
        $pengaduanTerbuka = $canComplaints ? Complaint::whereIn('status', ['diterima', 'diproses'])->count() : 0;
        $totalSampahKg = $canBankSampah ? (float) WasteDeposit::sum('berat_kg') : 0;

        // Data Khusus Bank Sampah
        $totalTransaksiSampah = $canBankSampah ? WasteDeposit::count() : 0;
        $jenisSampahCount = $canBankSampah ? WasteType::where('is_active', true)->count() : 0;
        $totalNilaiSampahRp = $canBankSampah ? WasteDeposit::sum('nilai') : 0;
        $recentWasteDeposits = $canBankSampah ? WasteDeposit::with(['household.kepalaKeluarga', 'wasteType'])->latest('created_at')->take(4)->get() : collect();
        $recentWasteTypes = $canBankSampah ? WasteType::active()->latest('created_at')->take(4)->get() : collect();

        // Data Khusus Keamanan Lingkungan
        $totalJadwalRonda = $canKeamanan ? PatrolSchedule::count() : 0;
        $totalLaporTamu = $canKeamanan ? GuestReport::count() : 0;
        $recentGuestReports = $canKeamanan ? GuestReport::latest('created_at')->take(4)->get() : collect();
        $recentPatrolSchedules = $canKeamanan ? PatrolSchedule::latest('tanggal')->take(4)->get() : collect();

        // Data Khusus Keuangan / Bendahara
        $recentCashTransactions = $canCash ? CashTransaction::latest('tanggal')->take(4)->get() : collect();
        $recentPayments = $canInvoices ? Payment::with(['invoice.household.kepalaKeluarga'])->latest('created_at')->take(4)->get() : collect();

        // Data Umum Surat & Pengaduan
        $recentLetters = $canLetters ? Letter::with(['resident', 'letterType'])->latest('created_at')->take(4)->get() : collect();
        $recentComplaints = $canComplaints ? Complaint::with(['user'])->latest('created_at')->take(4)->get() : collect();

        return [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'greeting' => $greeting,
            'canKependudukan' => $canKependudukan,
            'canLetters' => $canLetters,
            'canCash' => $canCash,
            'canInvoices' => $canInvoices,
            'canComplaints' => $canComplaints,
            'canBankSampah' => $canBankSampah,
            'canKeamanan' => $canKeamanan,
            'isKoordinatorBankSampah' => $isKoordinatorBankSampah,
            'isKoordinatorKeamanan' => $isKoordinatorKeamanan,
            'isBendahara' => $isBendahara,
            'isSekretaris' => $isSekretaris,
            // Metrik Umum
            'totalWarga' => $totalWarga,
            'totalKk' => $totalKk,
            'suratBerjalan' => $suratBerjalan,
            'saldoKas' => $saldoKas,
            'tagihanTertunda' => $tagihanTertunda,
            'pengaduanTerbuka' => $pengaduanTerbuka,
            'totalSampahKg' => $totalSampahKg,
            'recentLetters' => $recentLetters,
            'recentComplaints' => $recentComplaints,
            // Metrik Bank Sampah
            'totalTransaksiSampah' => $totalTransaksiSampah,
            'jenisSampahCount' => $jenisSampahCount,
            'totalNilaiSampahRp' => $totalNilaiSampahRp,
            'recentWasteDeposits' => $recentWasteDeposits,
            'recentWasteTypes' => $recentWasteTypes,
            // Metrik Keamanan
            'totalJadwalRonda' => $totalJadwalRonda,
            'totalLaporTamu' => $totalLaporTamu,
            'recentGuestReports' => $recentGuestReports,
            'recentPatrolSchedules' => $recentPatrolSchedules,
            // Metrik Keuangan
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'recentCashTransactions' => $recentCashTransactions,
            'recentPayments' => $recentPayments,
        ];
    }
}
