<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Household;
use App\Models\Letter;
use App\Models\MsmeProfile;
use App\Models\Resident;
use App\Models\RtSetting;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPortalController extends Controller
{
    /**
     * Beranda Publik SIM-LiBas: Profil Kampung Digital Pinang Masak Lestari
     */
    public function index(): View
    {
        $setting = RtSetting::first();
        
        $stats = [
            'total_kk' => Household::count(),
            'total_warga' => Resident::where('status_kependudukan', 'aktif')->count(),
            'total_sampah_kg' => (float) WasteDeposit::sum('berat_kg'),
            'total_umkm' => MsmeProfile::where('status_moderasi', 'disetujui')->where('is_active', true)->count(),
        ];

        $pengumuman = Announcement::active()
            ->whereIn('target', ['semua', 'warga'])
            ->orderByDesc('is_pinned')
            ->latest('created_at')
            ->take(3)
            ->get();

        $agenda = Event::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        $umkm = MsmeProfile::where('status_moderasi', 'disetujui')
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('public.index', compact('setting', 'stats', 'pengumuman', 'agenda', 'umkm'));
    }

    /**
     * Halaman Publik Verifikasi Keaslian Surat Pengantar via QR Code (FR-PUB-03)
     * Hanya menampilkan data minimal (nomor, jenis, tanggal, status sah), TANPA NIK sesuai UU PDP.
     */
    public function verifyLetter(string $token): View
    {
        $letter = Letter::where('qr_token', $token)
            ->orWhere('id', $token)
            ->orWhere('nomor_surat', $token)
            ->with(['letterType', 'resident'])
            ->first();

        $setting = RtSetting::first();

        return view('public.verify-letter', compact('letter', 'setting', 'token'));
    }

    /**
     * Halaman Publik Katalog UMKM Warga RT 15 (FR-PUB-04 / FR-UMK-02)
     */
    public function umkm(Request $request): View
    {
        $setting = RtSetting::first();

        $query = MsmeProfile::where('status_moderasi', 'disetujui')->where('is_active', true);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $search = $request->input('search', $request->input('q'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', '%' . $search . '%')
                  ->orWhere('produk', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $umkms = $query->latest()->paginate(12)->withQueryString();

        return view('public.umkm', compact('setting', 'umkms'));
    }

    /**
     * Halaman Publik Bank Sampah Pinang Masak Lestari
     */
    public function bankSampah(): View
    {
        $setting = RtSetting::first();
        $wasteTypes = WasteType::where('is_active', true)->orderBy('nama')->get();
        $totalKg = (float) WasteDeposit::sum('berat_kg');
        $totalPenyetor = WasteDeposit::distinct('household_id')->count('household_id');
        return view('public.bank-sampah', compact('setting', 'wasteTypes', 'totalKg', 'totalPenyetor'));
    }

    /**
     * Halaman Direktori Semua Layanan Warga RT 15
     */
    public function layananIndex(): View
    {
        $setting = RtSetting::first();
        $services = \App\Services\CitizenServiceCatalog::all();
        return view('public.layanan.index', compact('setting', 'services'));
    }

    /**
     * Halaman Detail Layanan Warga Spesifik
     */
    public function layananDetail(string $slug): View
    {
        $service = \App\Services\CitizenServiceCatalog::find($slug);
        if (!$service) {
            abort(404, 'Layanan warga tidak ditemukan.');
        }

        $setting = RtSetting::first();
        $otherServices = \App\Services\CitizenServiceCatalog::others($slug, 3);

        return view('public.layanan.detail', compact('setting', 'service', 'otherServices'));
    }

    /**
     * Halaman Publik Kontak & Sekretariat RT 15 Pematang Sulur
     */
    public function contact(): View
    {
        $setting = RtSetting::first();
        return view('public.contact', compact('setting'));
    }
}
