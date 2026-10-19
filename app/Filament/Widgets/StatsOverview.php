<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\CashTransaction;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\PatrolSchedule;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
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

        // Label Peran Utama Pengurus
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

        // Identifikasi Tampilan Khusus Tiap Role
        $isSuperAdmin = (bool) ($user && $user->hasAnyRole(['super_admin', 'admin']));
        $isKetuaRt = (bool) ($user && $user->hasRole('ketua_rt') && ! $isSuperAdmin);
        $isSekretaris = (bool) ($user && $user->hasRole('sekretaris') && ! $isSuperAdmin && ! $isKetuaRt);
        $isBendahara = (bool) ($user && $user->hasRole('bendahara') && ! $isSuperAdmin && ! $isKetuaRt);
        $isKoordinatorBankSampah = (bool) ($user && $user->hasRole('koordinator_bank_sampah') && ! $isSuperAdmin && ! $isKetuaRt);
        $isKoordinatorKeamanan = (bool) ($user && $user->hasRole('koordinator_keamanan') && ! $isSuperAdmin && ! $isKetuaRt);
        $isRwKelurahan = (bool) ($user && $user->hasRole('rw_kelurahan') && ! $isSuperAdmin && ! $isKetuaRt);

        // Hak Akses Modul
        $canKependudukan = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('lihat_data_warga')));
        $canLetters = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('periksa_surat') || $user->can('setujui_surat')));
        $canCash = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'bendahara', 'rw_kelurahan']) || $user->can('lihat_laporan_keuangan') || $user->can('kelola_buku_kas')));
        $canInvoices = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'bendahara']) || $user->can('terbitkan_tagihan') || $user->can('validasi_pembayaran')));
        $canComplaints = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'ketua_rt', 'sekretaris']) || $user->can('lihat_semua_pengaduan')));
        $canBankSampah = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'koordinator_bank_sampah', 'rw_kelurahan']) || $user->can('catat_setoran_sampah') || $user->can('lihat_dampak_lingkungan')));
        $canKeamanan = (bool) ($user && ($user->hasAnyRole(['super_admin', 'admin', 'koordinator_keamanan']) || $user->can('kelola_jadwal_ronda') || $user->can('lihat_laporan_tamu')));

        // 1. Data Kependudukan (Admin, Ketua RT, Sekretaris, dan agregat makro RW)
        $totalWarga = ($canKependudukan || $isRwKelurahan) ? Resident::where('status_kependudukan', 'aktif')->count() : 0;
        $totalKk = ($canKependudukan || $isRwKelurahan) ? Household::count() : 0;

        // 2. Data Surat Pengantar
        $suratBerjalan = $canLetters ? Letter::whereIn('status', ['diajukan', 'diperiksa'])->count() : 0;
        $suratDiajukan = $canLetters ? Letter::where('status', 'diajukan')->count() : 0;
        $suratSelesai = $canLetters ? Letter::whereIn('status', ['disetujui', 'selesai'])->count() : 0;
        $recentLetters = $canLetters ? Letter::with(['resident', 'letterType'])->latest('created_at')->take(4)->get() : collect();
        $lettersNeedingApproval = $canLetters ? Letter::whereIn('status', ['diajukan', 'diperiksa'])->with(['resident', 'letterType'])->latest('created_at')->take(4)->get() : collect();

        // 3. Data Kas & Keuangan RT
        $pemasukan = $canCash ? CashTransaction::where('jenis', 'masuk')->sum('jumlah') : 0;
        $pengeluaran = $canCash ? CashTransaction::where('jenis', 'keluar')->sum('jumlah') : 0;
        $saldoKas = $pemasukan - $pengeluaran;
        $recentCashTransactions = $canCash ? CashTransaction::latest('tanggal')->take(5)->get() : collect();

        // 4. Data Tagihan & Pembayaran Iuran (Khusus Bendahara & Super Admin)
        $tagihanTertunda = $canInvoices ? Invoice::whereIn('status', ['belum_bayar', 'belum_lunas', 'menunggu_verifikasi'])->sum('jumlah') : 0;
        $pembayaranPendingCount = $canInvoices ? Payment::where('status', 'pending')->count() : 0;
        $recentPayments = $canInvoices 
            ? Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->latest('created_at')->take(4)->get() 
            : collect();
        $pendingPayments = $canInvoices 
            ? Payment::where('status', 'pending')->with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->latest('created_at')->take(4)->get() 
            : collect();

        // 5. Data Pengaduan Warga
        $pengaduanTerbuka = $canComplaints ? Complaint::whereIn('status', ['diterima', 'diproses'])->count() : 0;
        $recentComplaints = $canComplaints ? Complaint::with(['user'])->latest('created_at')->take(4)->get() : collect();
        $complaintsNeedingAction = $canComplaints ? Complaint::whereIn('status', ['diterima', 'diproses'])->with(['user'])->latest('created_at')->take(4)->get() : collect();

        // 6. Data Bank Sampah Pinang Masak Lestari (Bank Sampah, RW, & Ringkasan Lingkungan)
        $totalSampahKg = ($canBankSampah || $isSuperAdmin || $isKetuaRt) ? (float) WasteDeposit::sum('berat_kg') : 0;
        $totalTransaksiSampah = $canBankSampah ? WasteDeposit::count() : 0;
        $totalNilaiSampahRp = ($canBankSampah || $isSuperAdmin || $isKetuaRt) ? WasteDeposit::sum('nilai') : 0;
        $nasabahAktifCount = $canBankSampah ? WasteDeposit::distinct('household_id')->count('household_id') : 0;
        $jenisSampahCount = $canBankSampah ? WasteType::where('is_active', true)->count() : 0;
        $recentWasteDeposits = ($canBankSampah || $isSuperAdmin) ? WasteDeposit::with(['household.kepalaKeluarga', 'wasteType'])->latest('created_at')->take(4)->get() : collect();
        $recentWasteTypes = $canBankSampah ? WasteType::active()->latest('created_at')->take(4)->get() : collect();

        // 7. Data Keamanan Lingkungan (Koordinator Keamanan & Super Admin)
        $totalJadwalRonda = $canKeamanan ? PatrolSchedule::count() : 0;
        $totalLaporTamu = $canKeamanan ? GuestReport::count() : 0;
        $tamuMenginapAktifCount = $canKeamanan 
            ? GuestReport::where('status', 'menginap')
                ->orWhere(function ($q) {
                    $q->whereNull('status')
                      ->where(function ($sub) {
                          $sub->whereNull('tanggal_pulang')->orWhere('tanggal_pulang', '>=', today());
                      });
                })->count() 
            : 0;
        $todayPatrolSchedule = $canKeamanan 
            ? PatrolSchedule::whereDate('tanggal', today())->with(['members.resident'])->first() 
            : null;
        $recentGuestReports = $canKeamanan ? GuestReport::latest('created_at')->take(4)->get() : collect();
        $recentPatrolSchedules = $canKeamanan ? PatrolSchedule::with(['members.resident'])->latest('tanggal')->take(4)->get() : collect();

        // 8. Data Khusus Admin Sistem
        $totalUsers = $isSuperAdmin ? User::count() : 0;
        $activeUsers = $isSuperAdmin ? User::where('status', 'aktif')->count() : 0;
        $totalAuditLogs = $isSuperAdmin ? AuditLog::count() : 0;
        $recentAuditLogs = $isSuperAdmin ? AuditLog::with(['causer'])->latest('created_at')->take(5)->get() : collect();

        // 9. Data Khusus Komunikasi & Agenda Sekretaris
        $recentAnnouncements = $isSekretaris ? Announcement::latest('created_at')->take(3)->get() : collect();
        $upcomingEvents = $isSekretaris ? Event::whereDate('tanggal_mulai', '>=', today())->orderBy('tanggal_mulai')->take(3)->get() : collect();

        return [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'greeting' => $greeting,

            // Flags Role
            'isSuperAdmin' => $isSuperAdmin,
            'isKetuaRt' => $isKetuaRt,
            'isSekretaris' => $isSekretaris,
            'isBendahara' => $isBendahara,
            'isKoordinatorBankSampah' => $isKoordinatorBankSampah,
            'isKoordinatorKeamanan' => $isKoordinatorKeamanan,
            'isRwKelurahan' => $isRwKelurahan,

            // Hak Akses
            'canKependudukan' => $canKependudukan,
            'canLetters' => $canLetters,
            'canCash' => $canCash,
            'canInvoices' => $canInvoices,
            'canComplaints' => $canComplaints,
            'canBankSampah' => $canBankSampah,
            'canKeamanan' => $canKeamanan,

            // Metrik Kependudukan
            'totalWarga' => $totalWarga,
            'totalKk' => $totalKk,

            // Metrik Surat Pengantar
            'suratBerjalan' => $suratBerjalan,
            'suratDiajukan' => $suratDiajukan,
            'suratSelesai' => $suratSelesai,
            'recentLetters' => $recentLetters,
            'lettersNeedingApproval' => $lettersNeedingApproval,

            // Metrik Keuangan Kas
            'saldoKas' => $saldoKas,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'recentCashTransactions' => $recentCashTransactions,

            // Metrik Tagihan & Pembayaran
            'tagihanTertunda' => $tagihanTertunda,
            'pembayaranPendingCount' => $pembayaranPendingCount,
            'recentPayments' => $recentPayments,
            'pendingPayments' => $pendingPayments,

            // Metrik Pengaduan Warga
            'pengaduanTerbuka' => $pengaduanTerbuka,
            'recentComplaints' => $recentComplaints,
            'complaintsNeedingAction' => $complaintsNeedingAction,

            // Metrik Bank Sampah
            'totalSampahKg' => $totalSampahKg,
            'totalTransaksiSampah' => $totalTransaksiSampah,
            'totalNilaiSampahRp' => $totalNilaiSampahRp,
            'nasabahAktifCount' => $nasabahAktifCount,
            'jenisSampahCount' => $jenisSampahCount,
            'recentWasteDeposits' => $recentWasteDeposits,
            'recentWasteTypes' => $recentWasteTypes,

            // Metrik Keamanan
            'totalJadwalRonda' => $totalJadwalRonda,
            'totalLaporTamu' => $totalLaporTamu,
            'tamuMenginapAktifCount' => $tamuMenginapAktifCount,
            'todayPatrolSchedule' => $todayPatrolSchedule,
            'recentGuestReports' => $recentGuestReports,
            'recentPatrolSchedules' => $recentPatrolSchedules,

            // Metrik Khusus Admin
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalAuditLogs' => $totalAuditLogs,
            'recentAuditLogs' => $recentAuditLogs,

            // Metrik Khusus Sekretaris
            'recentAnnouncements' => $recentAnnouncements,
            'upcomingEvents' => $upcomingEvents,
        ];
    }
}
