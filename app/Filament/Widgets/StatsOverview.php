<?php

namespace App\Filament\Widgets;

use App\Models\CashTransaction;
use App\Models\Complaint;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\Resident;
use App\Models\WasteDeposit;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends Widget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-overview';

    public function getViewData(): array
    {
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

        $totalWarga = Resident::where('status_kependudukan', 'aktif')->count();
        $totalKk = Household::count();
        $suratBerjalan = Letter::whereIn('status', ['diajukan', 'diperiksa'])->count();
        $tagihanTertunda = Invoice::where('status', 'belum_bayar')->sum('jumlah');
        $pemasukan = CashTransaction::where('jenis', 'masuk')->sum('jumlah');
        $pengeluaran = CashTransaction::where('jenis', 'keluar')->sum('jumlah');
        $saldoKas = $pemasukan - $pengeluaran;
        $pengaduanTerbuka = Complaint::whereIn('status', ['diterima', 'diproses'])->count();
        $totalSampahKg = WasteDeposit::sum('berat_kg');

        $recentLetters = Letter::with(['resident', 'letterType'])
            ->latest('created_at')
            ->take(4)
            ->get();

        $recentComplaints = Complaint::with(['user'])
            ->latest('created_at')
            ->take(4)
            ->get();

        return [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'greeting' => $greeting,
            'totalWarga' => $totalWarga,
            'totalKk' => $totalKk,
            'suratBerjalan' => $suratBerjalan,
            'saldoKas' => $saldoKas,
            'tagihanTertunda' => $tagihanTertunda,
            'pengaduanTerbuka' => $pengaduanTerbuka,
            'totalSampahKg' => $totalSampahKg,
            'recentLetters' => $recentLetters,
            'recentComplaints' => $recentComplaints,
        ];
    }
}
