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
     * Halaman Publik Detail UMKM Warga RT 15
     */
    public function umkmDetail($id): View
    {
        $setting = RtSetting::first();

        $umkm = MsmeProfile::with('household.headOfHousehold')
            ->where('status_moderasi', 'disetujui')
            ->where('is_active', true)
            ->findOrFail($id);

        $relatedUmkm = MsmeProfile::where('status_moderasi', 'disetujui')
            ->where('is_active', true)
            ->where('id', '!=', $umkm->id)
            ->when($umkm->kategori, function ($q) use ($umkm) {
                return $q->orderByRaw("kategori = ? DESC", [$umkm->kategori]);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('public.umkm-detail', compact('setting', 'umkm', 'relatedUmkm'));
    }

    /**
     * Halaman Publik Direktori Seluruh Pengumuman Resmi RT 15
     */
    public function pengumumanIndex(Request $request): View
    {
        $setting = RtSetting::first();
        $search = $request->query('search', $request->query('q'));

        $announcements = Announcement::where('is_published', true)
            ->whereIn('target', ['semua', 'warga', 'tetap', 'kontrak'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_pinned')
            ->latest('created_at')
            ->paginate(6)
            ->withQueryString();

        return view('public.pengumuman.index', compact('setting', 'announcements', 'search'));
    }

    /**
     * Halaman Publik Detail Pengumuman Resmi RT 15
     */
    public function pengumumanDetail($id): View
    {
        $setting = RtSetting::first();

        $announcement = Announcement::where('is_published', true)
            ->whereIn('target', ['semua', 'warga', 'tetap', 'kontrak'])
            ->findOrFail($id);

        $otherAnnouncements = Announcement::active()
            ->whereIn('target', ['semua', 'warga', 'tetap', 'kontrak'])
            ->where('id', '!=', $announcement->id)
            ->latest('created_at')
            ->take(4)
            ->get();

        return view('public.pengumuman-detail', compact('setting', 'announcement', 'otherAnnouncements'));
    }

    /**
     * Halaman Publik Direktori Seluruh Agenda / Kegiatan Warga RT 15
     */
    public function agendaIndex(Request $request): View
    {
        $setting = RtSetting::first();
        $kategori = $request->query('kategori');
        $search = $request->query('search', $request->query('q'));

        $events = Event::query()
            ->when($kategori && $kategori !== 'semua', function ($query) use ($kategori) {
                return $query->where('kategori', $kategori);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal_mulai')
            ->paginate(6)
            ->withQueryString();

        $kategoriList = [
            'semua' => 'Semua Agenda',
            'kerja_bakti' => 'Gotong Royong',
            'rapat' => 'Rapat',
            'pengajian' => 'Keagamaan',
            'perayaan' => 'Perayaan',
            'arisan' => 'Arisan',
            'lainnya' => 'Lainnya',
        ];

        return view('public.agenda.index', compact('setting', 'events', 'kategori', 'search', 'kategoriList'));
    }

    /**
     * Halaman Publik Detail Agenda / Kegiatan Resmi RT 15
     */
    public function agendaDetail($id): View
    {
        $setting = RtSetting::first();

        $event = Event::findOrFail($id);

        $otherEvents = Event::where('id', '!=', $event->id)
            ->where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(4)
            ->get();

        if ($otherEvents->count() < 2) {
            $otherEvents = Event::where('id', '!=', $event->id)
                ->orderByDesc('tanggal_mulai')
                ->take(4)
                ->get();
        }

        return view('public.agenda-detail', compact('setting', 'event', 'otherEvents'));
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

    /**
     * Halaman Publik Struktur Organisasi & Pelayan Masyarakat RT 15
     */
    public function strukturPengurus(): View
    {
        $setting = RtSetting::first();

        $struktur = [
            'pimpinan' => [
                'ketua' => [
                    'id' => 'ketua-rt',
                    'nama' => 'Dr. Sigit Indrawijaya, SE, MSi',
                    'jabatan' => 'Ketua RT 15 Pematang Sulur',
                    'tier' => 'Pimpinan Utama',
                    'inisial' => 'SI',
                    'badge' => 'Ketua Rukun Tetangga',
                    'badge_color' => 'bg-primary-900 text-white',
                    'ring_color' => 'ring-primary-700',
                    'tugas' => [
                        'Memimpin tata kelola lingkungan, pelayanan warga, dan pelaksanaan rukun tetangga.',
                        'Penandatanganan dan legalisasi resmi berkas surat pengantar warga pada sistem SIM-LiBas.',
                        'Mengordinasikan kerja sama strategis dengan Kelurahan Pematang Sulur, Babinsa, Bhabinkamtibmas, dan instansi terkait.',
                        'Membina ketenteraman, ketertiban, serta kerukunan hidup bermasyarakat antarwarga RT 15.',
                    ],
                ],
                'sekretaris' => [
                    'id' => 'sekretaris-rt',
                    'nama' => 'Alexander Charles',
                    'jabatan' => 'Sekretaris RT 15 Pematang Sulur',
                    'tier' => 'Pimpinan Administrasi',
                    'inisial' => 'AC',
                    'badge' => 'Sekretaris RT',
                    'badge_color' => 'bg-blue-700 text-white',
                    'ring_color' => 'ring-blue-600',
                    'tugas' => [
                        'Pengelolaan administrasi kependudukan dan database kependudukan terpadu RT 15.',
                        'Verifikasi dan pengarsipan permohonan surat pengantar warga secara digital.',
                        'Penyusunan agenda musyawarah warga dan notulensi rapat berkala pengurus RT.',
                        'Mendukung tata kelola administrasi surat-menyurat resmi dengan instansi pemerintah.',
                    ],
                ],
                'bendahara' => [
                    'id' => 'bendahara-rt',
                    'nama' => 'Rully Sondri Mudahar',
                    'jabatan' => 'Bendahara RT 15 Pematang Sulur',
                    'tier' => 'Pimpinan Keuangan',
                    'inisial' => 'RS',
                    'badge' => 'Bendahara RT',
                    'badge_color' => 'bg-amber-600 text-white',
                    'ring_color' => 'ring-amber-500',
                    'tugas' => [
                        'Pencatatan pembukuan kas lingkungan secara tertib, terbuka, dan akuntabel.',
                        'Pengelolaan iuran bulanan warga dan penerimaan donasi kegiatan sosial rukun tetangga.',
                        'Penyusunan laporan pertanggungjawaban keuangan berkala untuk transparansi warga.',
                        'Pencairan dan pengawasan anggaran operasional kegiatan lingkungan RT 15.',
                    ],
                ],
            ],
            'lembaga_mitra' => [
                [
                    'id' => 'mitra-pkk',
                    'nama' => 'Pengurus Pemberdayaan Kesejahteraan Keluarga (PKK)',
                    'singkatan' => 'TP-PKK RT 15',
                    'kategori' => 'Pemberdayaan Wanita & Keluarga',
                    'koordinasi' => 'Tim Penggerak PKK RT 15',
                    'badge_color' => 'bg-pink-50 text-pink-700 border-pink-200',
                    'icon_color' => 'bg-pink-50 text-pink-600',
                    'fokus' => [
                        'Mendorong pemberdayaan perempuan dan ketahanan keluarga di lingkungan RT 15.',
                        'Pengelolaan program Posyandu balita dan lansia serta kesehatan keluarga.',
                        'Penguatan program Dasawisma dan penghijauan pekarangan rumah tangga.',
                    ],
                ],
                [
                    'id' => 'mitra-mnf',
                    'nama' => 'Pengurus Masjid Nurul Fuqon (MNF)',
                    'singkatan' => 'DKM Nurul Fuqon',
                    'kategori' => 'Pembinaan Keagamaan & Spiritual',
                    'koordinasi' => 'Dewan Kemakmuran Masjid (DKM)',
                    'badge_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'icon_color' => 'bg-emerald-50 text-emerald-600',
                    'fokus' => [
                        'Memakmurkan masjid dan mengoordinasikan kegiatan peribadatan jamaah warga.',
                        'Penyelenggaraan Peringatan Hari Besar Islam (PHBI) dan pengajian rutin lingkungan.',
                        'Pengelolaan zakat, infak, sedekah, dan kegiatan sosial keagamaan warga.',
                    ],
                ],
                [
                    'id' => 'mitra-lam',
                    'nama' => 'Pengurus Lembaga Adat Masyarakat (LAM)',
                    'singkatan' => 'LAM RT 15',
                    'kategori' => 'Adat Melayu & Kerukunan',
                    'koordinasi' => 'Pemangku Adat Lingkungan',
                    'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'icon_color' => 'bg-amber-50 text-amber-700',
                    'fokus' => [
                        'Pelestarian norma adat istiadat dan kearifan lokal Melayu Jambi di lingkungan warga.',
                        'Mediasi musyawarah mufakat dalam memelihara kerukunan antartetangga.',
                        'Pendampingan tata cara adat pada prosesi sosial kemasyarakatan warga RT 15.',
                    ],
                ],
            ],
            'bidang_operasional' => [
                [
                    'id' => 'bidang-medsos',
                    'nama' => 'Yudhistira Adiwijaya',
                    'jabatan' => 'Bidang Media Sosial dan Dokumentasi',
                    'inisial' => 'YA',
                    'badge' => 'Medsos & Dokumentasi',
                    'badge_color' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'accent_color' => 'indigo',
                    'ring_color' => 'ring-indigo-500',
                    'tugas' => [
                        'Membuat dokumentasi dalam bentuk foto dan video tentang aktivitas warga serta kegiatan sosial maupun keagamaan.',
                        'Memposting pengumuman, foto, dan video kegiatan ke media sosial resmi RT 15.',
                    ],
                ],
                [
                    'id' => 'bidang-pemberdayaan',
                    'nama' => 'Dwi Kuswanto',
                    'jabatan' => 'Bidang Pemberdayaan Masyarakat',
                    'inisial' => 'DK',
                    'badge' => 'Pemberdayaan Masyarakat',
                    'badge_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'accent_color' => 'emerald',
                    'ring_color' => 'ring-emerald-500',
                    'tugas' => [
                        'Menggerakkan warga untuk berpartisipasi pada setiap kegiatan sosial, lingkungan, maupun keagamaan, seperti arisan dan gotong royong.',
                        'Memperbarui secara berkala data warga yang membutuhkan bantuan agar penyaluran tepat sasaran.',
                    ],
                ],
                [
                    'id' => 'bidang-keamanan',
                    'nama' => 'Tony Mulyady',
                    'jabatan' => 'Bidang Keamanan dan Ketertiban',
                    'inisial' => 'TM',
                    'badge' => 'Keamanan & Ketertiban',
                    'badge_color' => 'bg-red-50 text-red-700 border-red-200',
                    'accent_color' => 'red',
                    'ring_color' => 'ring-red-500',
                    'tugas' => [
                        'Melakukan antisipasi dan mitigasi risiko keamanan serta ketertiban lingkungan.',
                        'Berkoordinasi dengan pihak kelurahan, Babinsa, dan kepolisian terhadap potensi serta gangguan keamanan dan ketertiban.',
                    ],
                ],
                [
                    'id' => 'bidang-perlengkapan',
                    'nama' => 'M. Imam Agus Sutopo',
                    'jabatan' => 'Bidang Perlengkapan',
                    'inisial' => 'IS',
                    'badge' => 'Perlengkapan & Aset',
                    'badge_color' => 'bg-sky-50 text-sky-700 border-sky-200',
                    'accent_color' => 'sky',
                    'ring_color' => 'ring-sky-500',
                    'tugas' => [
                        'Menginventarisir, menjaga, dan memastikan aset serta inventaris RT 15 tetap aman, terawat, dan dapat digunakan untuk kepentingan bersama.',
                        'Mengupayakan pengadaan perlengkapan dan aset yang dibutuhkan lingkungan RT 15.',
                    ],
                ],
                [
                    'id' => 'bidang-umkm',
                    'nama' => 'Kresna',
                    'jabatan' => 'Bidang UMKM',
                    'inisial' => 'KR',
                    'badge' => 'Bidang UMKM',
                    'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'accent_color' => 'amber',
                    'ring_color' => 'ring-amber-500',
                    'tugas' => [
                        'Melakukan pendataan UMKM warga RT 15.',
                        'Mengupayakan pembinaan dan pendampingan UMKM warga.',
                        'Menciptakan peluang lahirnya UMKM baru dengan sektor beragam.',
                    ],
                ],
            ],
        ];

        return view('public.struktur-pengurus', compact('setting', 'struktur'));
    }
}
