<?php

namespace Tests\Feature;

use App\Filament\Widgets\StatsOverview;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Halaman login admin Filament dapat diakses publik.
     */
    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('SIM-LiBas RT 15');
    }

    /**
     * Pengurus RT (Ketua RT) dapat mengakses panel dashboard admin.
     */
    public function test_ketua_rt_can_access_admin_dashboard(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SIM-LiBas RT 15');
    }

    /**
     * Widget KPI Dashboard memuat statistik data warga dan kas RT dengan benar.
     */
    public function test_kpi_stats_overview_widget_renders_data(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        Livewire::actingAs($ketuaRt)
            ->test(StatsOverview::class)
            ->assertSee('Total Kependudukan')
            ->assertSee('Saldo Kas RT 15')
            ->assertSee('Bank Sampah Lestari');
    }

    /**
     * Warga biasa tidak diizinkan mengakses panel admin pengurus.
     */
    public function test_regular_citizen_cannot_access_admin_dashboard(): void
    {
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $response = $this->actingAs($warga)->get('/admin');

        $response->assertStatus(403);
    }

    /**
     * Koordinator Bank Sampah hanya dapat mengakses modul Bank Sampah dan dilarang mengakses modul Kependudukan/Kas.
     */
    public function test_role_based_access_koordinator_bank_sampah(): void
    {
        $koorBankSampah = User::where('email', 'banksampah@simlibas.com')->first();

        // Diizinkan mengakses dashboard & modul Bank Sampah
        $this->actingAs($koorBankSampah)->get('/admin')->assertStatus(200)->assertSee('Koordinator Bank Sampah');
        $this->actingAs($koorBankSampah)->get('/admin/waste-deposits')->assertStatus(200);
        $this->actingAs($koorBankSampah)->get('/admin/waste-types')->assertStatus(200);

        // Dilarang mengakses modul kependudukan, surat pengantar, dan kas RT
        $this->actingAs($koorBankSampah)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($koorBankSampah)->get('/admin/households')->assertStatus(403);
        $this->actingAs($koorBankSampah)->get('/admin/letters')->assertStatus(403);
        $this->actingAs($koorBankSampah)->get('/admin/cash-transactions')->assertStatus(403);
    }

    /**
     * Bendahara hanya dapat mengakses modul Keuangan RT dan dilarang mengakses Kependudukan/Bank Sampah.
     */
    public function test_role_based_access_bendahara(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();

        // Diizinkan mengakses dashboard & modul keuangan
        $this->actingAs($bendahara)->get('/admin')->assertStatus(200)->assertSee('Bendahara RT 15');
        $this->actingAs($bendahara)->get('/admin/cash-transactions')->assertStatus(200);
        $this->actingAs($bendahara)->get('/admin/invoices')->assertStatus(200);
        $this->actingAs($bendahara)->get('/admin/payments')->assertStatus(200);

        // Dilarang mengakses modul kependudukan dan bank sampah
        $this->actingAs($bendahara)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($bendahara)->get('/admin/households')->assertStatus(403);
        $this->actingAs($bendahara)->get('/admin/waste-deposits')->assertStatus(403);
    }

    /**
     * Koordinator Keamanan hanya dapat mengakses modul Keamanan Lingkungan dan dilarang mengakses Kependudukan/Kas.
     */
    public function test_role_based_access_koordinator_keamanan(): void
    {
        $koorKeamanan = User::where('email', 'keamanan@simlibas.com')->first();

        // Diizinkan mengakses dashboard & modul keamanan
        $this->actingAs($koorKeamanan)->get('/admin')->assertStatus(200)->assertSee('Koordinator Keamanan');
        $this->actingAs($koorKeamanan)->get('/admin/patrol-schedules')->assertStatus(200);
        $this->actingAs($koorKeamanan)->get('/admin/guest-reports')->assertStatus(200);

        // Dilarang mengakses modul kependudukan dan kas RT
        $this->actingAs($koorKeamanan)->get('/admin/residents')->assertStatus(403);
        $this->actingAs($koorKeamanan)->get('/admin/cash-transactions')->assertStatus(403);
        $this->actingAs($koorKeamanan)->get('/admin/waste-deposits')->assertStatus(403);
    }

    /**
     * Ketua RT memiliki Peran Eksekutif (Dashboard, Kependudukan, Surat Pengantar, Pengaduan, Monitoring Kas)
     * dan dilarang mengakses modul teknis operasional (Master Surat, Pengumuman, Agenda, Iuran, Ronda, Bank Sampah).
     */
    public function test_role_based_access_ketua_rt_executive(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // 1. Modul Eksekutif yang Diizinkan untuk Ketua RT
        $this->actingAs($ketuaRt)->get('/admin')->assertStatus(200)->assertSee('Ketua RT 15');
        $this->actingAs($ketuaRt)->get('/admin/households')->assertStatus(200);
        $this->actingAs($ketuaRt)->get('/admin/residents')->assertStatus(200);
        $this->actingAs($ketuaRt)->get('/admin/letters')->assertStatus(200);
        $this->actingAs($ketuaRt)->get('/admin/complaints')->assertStatus(200);
        $this->actingAs($ketuaRt)->get('/admin/cash-transactions')->assertStatus(200);
        $this->actingAs($ketuaRt)->get('/admin/profile')->assertStatus(200);

        // 2. Modul Teknis yang Dilarang untuk Ketua RT (Tidak Tumpang Tindih)
        $this->actingAs($ketuaRt)->get('/admin/letter-types')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/announcements')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/events')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/msme-profiles')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/invoices')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/payments')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/fee-types')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/patrol-schedules')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/guest-reports')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/waste-deposits')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/waste-types')->assertStatus(403);
        $this->actingAs($ketuaRt)->get('/admin/rt-settings')->assertStatus(403);
    }

    /**
     * Sekretaris mengelola Administrasi Umum, Kependudukan, Surat Menyurat, Komunikasi & UMKM,
     * dan dilarang mengakses modul Keuangan, Keamanan, Bank Sampah, serta Pengaturan Sistem.
     */
    public function test_role_based_access_sekretaris(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();

        // 1. Modul yang Diizinkan untuk Sekretaris
        $this->actingAs($sekretaris)->get('/admin')->assertStatus(200)->assertSee('Sekretaris RT 15');
        $this->actingAs($sekretaris)->get('/admin/households')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/residents')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/letters')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/letter-types')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/announcements')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/events')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/complaints')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/msme-profiles')->assertStatus(200);
        $this->actingAs($sekretaris)->get('/admin/profile')->assertStatus(200);

        // 2. Modul yang Dilarang untuk Sekretaris (Tidak Tumpang Tindih)
        $this->actingAs($sekretaris)->get('/admin/cash-transactions')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/invoices')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/payments')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/fee-types')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/patrol-schedules')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/guest-reports')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/waste-deposits')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/waste-types')->assertStatus(403);
        $this->actingAs($sekretaris)->get('/admin/rt-settings')->assertStatus(403);
    }

    /**
     * Halaman Kartu Keluarga (KK) dan topbar dapat diakses pengurus RT.
     */
    public function test_household_resource_page_and_stats_widget_render_successfully(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/households');

        $response->assertStatus(200);
        $response->assertSee('Kartu Keluarga (KK)');
        $response->assertDontSee('Semua Kartu Keluarga');
        $response->assertSee('Tambah Kartu Keluarga');
        $response->assertSee('Ekspor Data');
        $response->assertSee('ID KK');
        $response->assertSee('Semua Jumlah Anggota');
        $response->assertSee('1 Jiwa (Tunggal)');
        $response->assertSee('6 Jiwa atau Lebih');
        $response->assertSee('wire:model.live="anggotaFilter"', false);
        $response->assertSee('Format PDF');
        $response->assertSee('Format Excel (.xlsx)');
    }

    /**
     * Pengurus RT dapat memfilter tabel Kartu Keluarga berdasarkan jumlah anggota dan mereset filter.
     */
    public function test_can_filter_households_by_member_count(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\ListHouseholds::class)
            ->set('anggotaFilter', '1')
            ->assertSet('anggotaFilter', '1')
            ->call('resetFilters')
            ->assertSet('anggotaFilter', '')
            ->assertSet('search', '');
    }

    /**
     * Pengurus RT dapat membuka preview PDF data Kartu Keluarga di tab baru.
     */
    public function test_can_export_households_to_pdf_preview(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $sampleHousehold = Household::first();

        $response = $this->actingAs($ketuaRt)->get(route('admin.households.export.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        if ($sampleHousehold && $sampleHousehold->no_kk) {
            $view = view('households.pdf', [
                'households' => Household::with('kepalaKeluarga')->withCount('residents')->take(1)->get(),
                'setting' => \App\Models\RtSetting::first(),
                'filterInfo' => 'Semua Data',
                'totalKk' => 1,
                'totalJiwa' => 1,
                'printedAt' => now(),
                'user' => $ketuaRt,
                'logoBase64' => 'data:image/png;base64,sample',
            ])->render();

            $this->assertStringContainsString($sampleHousehold->no_kk, $view);
            $this->assertStringNotContainsString('********', $view);
        }
    }

    /**
     * Pengurus RT dapat mengunduh file spreadsheet Excel (.xlsx) data Kartu Keluarga.
     */
    public function test_can_export_households_to_excel_download(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get(route('admin.households.export.excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition'));
    }

    /**
     * Pengurus RT dapat mengekspor Data Warga dan Agenda Kegiatan ke format PDF dan Excel.
     */
    public function test_can_export_all_resources_to_pdf_and_excel(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $resources = [
            'households',
            'residents',
            'events',
            'announcements',
            'cash-transactions',
            'complaints',
            'fee-types',
            'guest-reports',
            'invoices',
            'letter-types',
            'letters',
            'msme-profiles',
            'patrol-schedules',
            'payments',
            'waste-deposits',
            'waste-types',
        ];

        foreach ($resources as $resource) {
            // PDF
            $pdfResponse = $this->actingAs($ketuaRt)->get(route('admin.export.pdf', ['resource' => $resource]));
            $pdfResponse->assertStatus(200);
            $pdfResponse->assertHeader('content-type', 'application/pdf');

            // Excel (.xlsx)
            $excelResponse = $this->actingAs($ketuaRt)->get(route('admin.export.excel', ['resource' => $resource]));
            $excelResponse->assertStatus(200);
            $excelResponse->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->assertStringContainsString('.xlsx', $excelResponse->headers->get('content-disposition'));
        }
    }

    /**
     * Pengguna tanpa role pengurus ditolak saat mengakses ekspor data kependudukan.
     */
    public function test_regular_citizen_cannot_access_household_exports(): void
    {
        $wargaUser = User::where('email', 'budi.santoso@warga.simlibas.com')->first()
            ?? User::whereDoesntHave('roles')->first()
            ?? User::factory()->create();

        $this->actingAs($wargaUser)->get(route('admin.households.export.pdf'))->assertStatus(403);
        $this->actingAs($wargaUser)->get(route('admin.households.export.excel'))->assertStatus(403);
    }

    /**
     * Halaman Data Warga dapat diakses dan menampilkan tabel berdesain bersih dengan Deep Navy header.
     */
    public function test_resident_resource_page_renders_successfully(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/residents');

        $response->assertStatus(200);
        $response->assertSee('Data Warga');
        $response->assertSee('KEPENDUDUKAN RT 15');
        $response->assertSee('Tambah Warga');
        $response->assertSee('Ekspor Data');
        $response->assertSee('ID WARGA');
        $response->assertSee('KARTU KELUARGA');
        $response->assertSee('Detail Data Warga');
        $response->assertSee('Kartu Keluarga');
        $response->assertDontSee('Halaman KK');
        $response->assertDontSee('HUBUNGAN');
        $response->assertDontSee('fi-breadcrumbs');
    }

    /**
     * Pengurus RT dapat mengakses halaman khusus pendaftaran Kartu Keluarga (/admin/households/create).
     */
    public function test_can_access_create_household_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/households/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Kartu Keluarga Baru');
        $response->assertSee('Informasi Kartu Keluarga');
        $response->assertSee('Data Kepala Keluarga');
        $response->assertSee('Anggota Keluarga Tambahan');
        $response->assertSee('Kembali');
        $response->assertSee('Upload Foto Dokumen KK');
        $response->assertDontSee('Catatan Tambahan (Opsional)');
        $response->assertDontSee('Pendaftaran Baru');
        $response->assertSee('max-w-[1400px]');
        $response->assertDontSee('max-w-5xl');
        $response->assertDontSee('placeholder="RT 15 / RW 04"', false);
        $response->assertDontSee('fi-breadcrumbs');
        $response->assertDontSee('wire:model="kepala_jenis_kelamin"', false);
        $response->assertDontSee('wire:model="kepala_status_perkawinan"', false);
        $response->assertSee('wire:model="kepala_agama"', false);
        $response->assertSee('wire:model="kepala_pendidikan_terakhir"', false);
        $response->assertSee('Status Hunian');
        $response->assertSee('wire:model="status_hunian"', false);
        $response->assertSee('hidden sm:inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-[#0B3A85] text-white uppercase tracking-wider shrink-0', false);
        $response->assertSee('hidden sm:inline-block ml-2 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#EBF3FC]', false);
    }

    /**
     * Pengurus RT dapat menambahkan data Kartu Keluarga baru, Kepala Keluarga, dan Anggota Keluarga via halaman create.
     */
    public function test_can_create_household_via_dedicated_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\CreateHousehold::class)
            ->set('no_kk', '1571998877665544')
            ->set('alamat', 'Jl. Kenanga No. 88, RT 15')
            ->set('kepala_nama', 'Hasan Basri')
            ->set('kepala_nik', '1571011122330001')
            ->set('kepala_telepon', '0812-9988-7766')
            ->set('kepala_agama', 'Islam')
            ->set('kepala_pekerjaan', 'Wiraswasta')
            ->set('kepala_pendidikan_terakhir', 'S1')
            ->call('addAnggota')
            ->set('anggota_keluarga.0.nama', 'Siti Aminah')
            ->set('anggota_keluarga.0.nik', '1571011122330002')
            ->set('anggota_keluarga.0.hubungan_keluarga', 'istri')
            ->set('anggota_keluarga.0.tempat_lahir', 'Padang')
            ->set('anggota_keluarga.0.telepon', '0812-9988-1122')
            ->call('addAnggota')
            ->set('anggota_keluarga.1.nama', 'Fikri Basri')
            ->set('anggota_keluarga.1.nik', '1571011122330003')
            ->set('anggota_keluarga.1.hubungan_keluarga', 'anak')
            ->set('anggota_keluarga.1.jenis_kelamin', 'L')
            ->set('anggota_keluarga.1.status_perkawinan', 'Belum Kawin')
            ->call('saveHousehold')
            ->assertHasNoErrors()
            ->assertRedirect(\App\Filament\Resources\Households\HouseholdResource::getUrl('index'));

        $this->assertDatabaseHas('households', [
            'alamat' => 'Jl. Kenanga No. 88, RT 15',
            'status_hunian' => 'tetap',
        ]);

        $this->assertDatabaseHas('residents', [
            'nama' => 'Hasan Basri',
            'is_kepala_keluarga' => true,
            'jenis_kelamin' => 'L',
            'status_perkawinan' => 'Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Wiraswasta',
        ]);

        $this->assertDatabaseHas('residents', [
            'nama' => 'Siti Aminah',
            'hubungan_keluarga' => 'istri',
            'jenis_kelamin' => 'P',
            'status_perkawinan' => 'Kawin',
            'tempat_lahir' => 'Padang',
            'telepon' => '0812-9988-1122',
            'is_kepala_keluarga' => false,
        ]);

        $this->assertDatabaseHas('residents', [
            'nama' => 'Fikri Basri',
            'hubungan_keluarga' => 'anak',
            'jenis_kelamin' => 'L',
            'status_perkawinan' => 'Belum Kawin',
            'is_kepala_keluarga' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Hasan Basri',
            'email' => '1571011122330001@warga.simlibas.com',
        ]);

        $hasanUser = User::where('name', 'Hasan Basri')->first();
        $this->assertTrue($hasanUser->hasRole('warga'));

        $createdHousehold = Household::where('alamat', 'Jl. Kenanga No. 88, RT 15')->first();
        $this->assertNotNull($createdHousehold->id_kk);
        $this->assertMatchesRegularExpression('/^KK\d{3}$/', $createdHousehold->id_kk);

        $hasan = Resident::where('nama', 'Hasan Basri')->first();
        $this->assertNotNull($hasan->id_warga);
        $this->assertMatchesRegularExpression('/^W\d{3}$/', $hasan->id_warga);

        $siti = Resident::where('nama', 'Siti Aminah')->first();
        $this->assertNotNull($siti->id_warga);
        $this->assertMatchesRegularExpression('/^W\d{3}$/', $siti->id_warga);
    }

    /**
     * Pengurus RT dapat mengunggah Foto Dokumen KK saat menambahkan data Kartu Keluarga.
     */
    public function test_can_create_household_with_uploaded_foto_kk(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $file = \Illuminate\Http\UploadedFile::fake()->image('kartu_keluarga.jpg', 800, 600);

        Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\CreateHousehold::class)
            ->set('no_kk', '1571998877665599')
            ->set('alamat', 'Jl. Flamboyan No. 10, RT 15')
            ->set('status_hunian', 'kontrak')
            ->set('foto_kk', $file)
            ->set('kepala_nama', 'Bambang Sudarsono')
            ->set('kepala_nik', '1571011122339999')
            ->set('kepala_telepon', '0813-1122-3344')
            ->set('kepala_jenis_kelamin', 'L')
            ->call('saveHousehold')
            ->assertHasNoErrors()
            ->assertRedirect(\App\Filament\Resources\Households\HouseholdResource::getUrl('index'));

        $household = \App\Models\Household::where('alamat', 'Jl. Flamboyan No. 10, RT 15')->first();
        $this->assertNotNull($household);
        $this->assertEquals('kontrak', $household->status_hunian);
        $this->assertNotNull($household->foto_kk);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($household->foto_kk);
    }

    /**
     * Kolom Anggota di daftar KK mengarah ke halaman detail KK (bukan halaman edit).
     */
    public function test_anggota_column_links_to_household_detail_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::first();

        $response = $this->actingAs($ketuaRt)->get('/admin/households');

        $response->assertStatus(200);
        // Link anggota mengarah ke detail /admin/households/{id}
        $response->assertSee(url('/admin/households/' . $household->id));
        // Link aksi edit mengarah ke /admin/households/{id}/edit
        $response->assertSee(url('/admin/households/' . $household->id . '/edit'));
    }

    /**
     * Halaman detail Kartu Keluarga dapat diakses dan menampilkan anggota keluarga.
     */
    public function test_can_view_household_detail_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::with('residents', 'kepalaKeluarga')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/households/' . $household->id);

        $response->assertStatus(200);
        $response->assertSee('Data Kartu Keluarga');
        $response->assertDontSee('Detail Resmi KK');
        $response->assertDontSee('<dt class="text-slate-500 font-medium shrink-0">Catatan</dt>', false);
        $response->assertSee('Daftar Anggota Keluarga');
        $response->assertSee('ID WARGA');
        $response->assertSee('NAMA LENGKAP');
        $response->assertSee('STATUS KELUARGA');
        $response->assertDontSee('<th class="py-3.5 px-4">KARTU KELUARGA</th>', false);
        $response->assertSee('NO. TELEPON / WA');
        $response->assertSee('File Kartu Keluarga');
        $response->assertSee('Detail Data Warga');
        $response->assertSee($household->kepalaKeluarga->nama);
        $response->assertSee('Kepala Keluarga');
        foreach ($household->residents as $resident) {
            $response->assertSee($resident->nama);
            if ($resident->telepon && $resident->wa_url) {
                $response->assertSee($resident->wa_url);
            }
        }

        // Uji jika household memiliki file foto KK
        $householdWithFoto = \App\Models\Household::create([
            'no_kk' => '1571000000000099',
            'alamat' => 'Jl. Uji Foto No. 1',
            'status_hunian' => 'tetap',
            'foto_kk' => 'households/foto_kk/sample.jpg',
        ]);
        $responseWithFoto = $this->actingAs($ketuaRt)->get('/admin/households/' . $householdWithFoto->id);
        $responseWithFoto->assertStatus(200);
        $responseWithFoto->assertSee('File Kartu Keluarga');
        $responseWithFoto->assertSee('Lihat File');
    }

    /**
     * Admin dapat membuka halaman ubah data Kartu Keluarga dengan seluruh data pre-filled.
     */
    public function test_can_access_edit_household_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::with(['residents', 'kepalaKeluarga'])->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/households/' . $household->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kartu Keluarga');
        $response->assertSee('Informasi Kartu Keluarga');
        $response->assertSee('Data Kepala Keluarga');
        $response->assertSee('Anggota Keluarga Tambahan');
        if ($household->id_kk) {
            $response->assertSee($household->id_kk);
        }
        $response->assertSee($household->no_kk);
        if ($household->kepalaKeluarga) {
            $response->assertSee($household->kepalaKeluarga->nama);
        }
    }

    /**
     * Admin dapat memperbarui data Kartu Keluarga dan data kepala keluarga.
     */
    public function test_can_update_household_data(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::with(['residents', 'kepalaKeluarga'])->first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\EditHousehold::class, ['record' => $household->id])
            ->set('alamat', 'Jl. Pinang Masak No. 99 RT 15 Diperbarui')
            ->set('kepala_pekerjaan', 'Konsultan IT')
            ->call('updateHousehold')
            ->assertHasNoErrors()
            ->assertRedirect(url('/admin/households/' . $household->id));

        $this->assertEquals('Jl. Pinang Masak No. 99 RT 15 Diperbarui', $household->fresh()->alamat);
        $this->assertEquals('Konsultan IT', $household->fresh()->kepalaKeluarga->pekerjaan);
    }

    /**
     * Admin dapat memperbarui password kepala keluarga dan email serta password anggota keluarga di Ubah KK.
     */
    public function test_can_update_household_with_passwords_and_member_emails(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::with(['residents', 'kepalaKeluarga', 'user'])->first();

        $test = \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\EditHousehold::class, ['record' => $household->id])
            ->set('kepala_password', 'baru12345');

        if (!empty($test->get('anggota_keluarga'))) {
            $test->set('anggota_keluarga.0.email', 'anggota.custom@simlibas.com')
                ->set('anggota_keluarga.0.password', 'anggota123');
        }

        $test->call('updateHousehold')
            ->assertHasNoErrors()
            ->assertRedirect(url('/admin/households/' . $household->id));

        $householdUser = $household->fresh()->user;
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('baru12345', $householdUser->password));

        $updatedAnggota = $household->fresh()->residents->where('id', '!=', $household->fresh()->kepalaKeluarga->id)->first();
        if ($updatedAnggota) {
            $this->assertEquals('anggota.custom@simlibas.com', $updatedAnggota->email);
            $this->assertNotNull($updatedAnggota->user);
            $this->assertTrue(\Illuminate\Support\Facades\Hash::check('anggota123', $updatedAnggota->user->password));
        }
    }

    /**
     * Admin dapat memperbarui data KK secara parsial via modal popup di masing-masing kartu ringkasan.
     */
    public function test_can_update_household_via_modular_card_modals(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = \App\Models\Household::with(['residents', 'kepalaKeluarga', 'user'])->first();

        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('kartu_keluarga.jpg');

        // 1. Uji modal dokumen kartu keluarga
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\ViewHousehold::class, ['record' => $household->id])
            ->call('openEditDokumen')
            ->assertSet('editDokumenModalOpen', true)
            ->set('dokumen_alamat', 'Jl. Kenanga Mas No. 55 RT 15')
            ->set('dokumen_foto_kk', $file)
            ->call('saveDokumen')
            ->assertHasNoErrors()
            ->assertSet('editDokumenModalOpen', false);

        $this->assertEquals('Jl. Kenanga Mas No. 55 RT 15', $household->fresh()->alamat);
        $this->assertNotNull($household->fresh()->foto_kk);

        // 2. Uji modal kepala keluarga
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\ViewHousehold::class, ['record' => $household->id])
            ->call('openEditKepala')
            ->assertSet('editKepalaModalOpen', true)
            ->set('kepala_pekerjaan', 'Dosen Universitas Jambi')
            ->call('saveKepala')
            ->assertHasNoErrors()
            ->assertSet('editKepalaModalOpen', false);

        $this->assertEquals('Dosen Universitas Jambi', $household->fresh()->kepalaKeluarga->pekerjaan);

        // 3. Uji modal akun portal warga
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Households\Pages\ViewHousehold::class, ['record' => $household->id])
            ->call('openEditAkun')
            ->assertSet('editAkunModalOpen', true)
            ->set('akun_phone', '0812-7777-8888')
            ->call('saveAkun')
            ->assertHasNoErrors()
            ->assertSet('editAkunModalOpen', false);

        if ($household->fresh()->user) {
            $this->assertEquals('0812-7777-8888', $household->fresh()->user->phone);
        }
    }

    /**
     * Pengurus RT dapat melihat daftar permohonan surat pengantar di panel admin.
     */
    public function test_admin_can_access_letters_resource(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/letters');

        $response->assertStatus(200);
        $response->assertSee('Surat Pengantar');
    }

    /**
     * Admin pengurus RT dapat mencetak lembar surat pengantar warga yang disetujui.
     */
    public function test_admin_can_print_approved_letter(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $resident = \App\Models\Resident::first();
        $type = \App\Models\LetterType::first();

        $letter = \App\Models\Letter::create([
            'nomor_surat' => '009/RT.15/PMS/X/2026',
            'letter_type_id' => $type->id,
            'resident_id' => $resident->id,
            'keperluan' => 'Keperluan administrasi perbankan',
            'status' => 'disetujui',
            'qr_token' => 'ADMIN_PRINT_TEST_TOKEN',
            'tanggal_disetujui' => now(),
        ]);

        $response = $this->actingAs($ketuaRt)->get(route('letters.print', $letter));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        $htmlResponse = $this->actingAs($ketuaRt)->get(route('letters.print', ['letter' => $letter, 'format' => 'html']));
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('RUKUN TETANGGA 15');
        $htmlResponse->assertSee('009/RT.15/PMS/X/2026');
        $htmlResponse->assertSee(strtoupper($resident->nama));
    }

    /**
     * Pengurus RT dapat mengakses halaman create data warga dengan tampilan selaras.
     */
    public function test_can_access_create_resident_page(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin/residents/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Data Warga Baru');
        $response->assertSee('KEPENDUDUKAN RT 15');
        $response->assertSee('Kembali');
        $response->assertSee('Kategori Warga');
        $response->assertSee('Memiliki KK di RT 15');
        $response->assertSee('Tanpa KK di RT 15');
        $response->assertDontSee('fi-breadcrumbs');
    }

    /**
     * Pengurus RT dapat menambahkan data warga yang terhubung ke Kartu Keluarga terdaftar.
     */
    public function test_can_create_resident_connected_to_household(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\CreateResident::class)
            ->set('kategori_warga', 'memiliki_kk')
            ->set('household_id', (string) $household->id)
            ->set('hubungan_keluarga', 'anak')
            ->set('nama', 'Aditya Pratama')
            ->set('nik', '1571099887766551')
            ->set('tempat_lahir', 'Jambi')
            ->set('tanggal_lahir', '2005-08-17')
            ->set('jenis_kelamin', 'L')
            ->set('agama', 'Islam')
            ->set('pendidikan_terakhir', 'SMA / SMK / Sederajat')
            ->set('pekerjaan', 'Pelajar / Mahasiswa')
            ->call('saveResident')
            ->assertHasNoErrors()
            ->assertRedirect(\App\Filament\Resources\Residents\ResidentResource::getUrl('index'));

        $this->assertDatabaseHas('residents', [
            'nama' => 'Aditya Pratama',
            'household_id' => $household->id,
            'kategori_warga' => 'memiliki_kk',
            'hubungan_keluarga' => 'anak',
        ]);

        $created = Resident::where('nama', 'Aditya Pratama')->first();
        $this->assertNotNull($created->id_warga);
        $this->assertMatchesRegularExpression('/^W\d{3}$/', $created->id_warga);
        $this->assertEquals($household->id, $created->household_id);

        $this->assertDatabaseHas('users', [
            'name' => 'Aditya Pratama',
            'email' => '1571099887766551@warga.simlibas.com',
        ]);
    }

    /**
     * Pengurus RT dapat menambahkan data warga tanpa KK (kontrak / kos / pendatang).
     */
    public function test_can_create_resident_without_household(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\CreateResident::class)
            ->set('kategori_warga', 'tanpa_kk')
            ->set('status_tempat_tinggal', 'kos')
            ->set('alamat_domisili', 'Kos Dahlia No. 4 RT 15')
            ->set('nama', 'Bayu Anggoro')
            ->set('nik', '1571099887766552')
            ->set('tempat_lahir', 'Palembang')
            ->set('tanggal_lahir', '1998-05-12')
            ->set('jenis_kelamin', 'L')
            ->set('agama', 'Islam')
            ->set('pekerjaan', 'Karyawan Swasta')
            ->call('saveResident')
            ->assertHasNoErrors()
            ->assertRedirect(\App\Filament\Resources\Residents\ResidentResource::getUrl('index'));

        $this->assertDatabaseHas('residents', [
            'nama' => 'Bayu Anggoro',
            'household_id' => null,
            'kategori_warga' => 'tanpa_kk',
            'status_tempat_tinggal' => 'kos',
            'alamat_domisili' => 'Kos Dahlia No. 4 RT 15',
        ]);

        $created = Resident::where('nama', 'Bayu Anggoro')->first();
        $this->assertNotNull($created->id_warga);
        $this->assertMatchesRegularExpression('/^W\d{3}$/', $created->id_warga);
        $this->assertNull($created->household_id);
    }

    /**
     * Mencegah registrasi warga baru menggunakan NIK yang sudah ada di arsip (soft-deleted).
     */
    public function test_soft_deleted_nik_conflict_prevention(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $resident = Resident::first();
        $nikToTest = $resident->nik;

        // Soft delete warga ini
        $resident->delete();
        $this->assertSoftDeleted('residents', ['id' => $resident->id]);

        // Mencoba membuat warga baru dengan NIK yang sama harus ditolak validasi
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\CreateResident::class)
            ->set('kategori_warga', 'tanpa_kk')
            ->set('status_tempat_tinggal', 'kos')
            ->set('alamat_domisili', 'Kos Dahlia No. 4 RT 15')
            ->set('nama', 'Warga Duplikat NIK')
            ->set('nik', $nikToTest)
            ->set('tempat_lahir', 'Jambi')
            ->set('tanggal_lahir', '1995-01-01')
            ->set('jenis_kelamin', 'L')
            ->set('agama', 'Islam')
            ->call('saveResident')
            ->assertHasErrors(['nik']);
    }

    /**
     * Memastikan satu KK hanya memiliki satu Kepala Keluarga aktif (aturan single kepala keluarga).
     */
    public function test_single_kepala_keluarga_enforcement(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::with('residents')->first();

        $oldKepala = $household->kepalaKeluarga;
        $this->assertNotNull($oldKepala);
        $this->assertTrue($oldKepala->is_kepala_keluarga);

        // Tambah anggota baru sebagai Kepala Keluarga
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\CreateResident::class)
            ->set('kategori_warga', 'memiliki_kk')
            ->set('household_id', $household->id)
            ->set('nama', 'Kepala Keluarga Pengganti')
            ->set('nik', '1571099887766599')
            ->set('tempat_lahir', 'Jambi')
            ->set('tanggal_lahir', '1980-01-01')
            ->set('jenis_kelamin', 'L')
            ->set('agama', 'Islam')
            ->set('hubungan_keluarga', 'kepala_keluarga')
            ->call('saveResident')
            ->assertHasNoErrors();

        // Kepala keluarga lama harus dinonaktifkan status kepalanya
        $oldKepala->refresh();
        $this->assertFalse((bool) $oldKepala->is_kepala_keluarga);
        $this->assertEquals('lainnya', $oldKepala->hubungan_keluarga);

        // Kepala keluarga baru harus aktif
        $newKepala = Resident::where('nik_hash', hash('sha256', '1571099887766599'))->first();
        $this->assertTrue((bool) $newKepala->is_kepala_keluarga);
        $this->assertEquals('kepala_keluarga', $newKepala->hubungan_keluarga);
    }

    /**
     * Memastikan kepala keluarga tidak dapat dihapus jika masih ada anggota keluarga lain di KK.
     */
    public function test_delete_resident_guard_for_kepala_keluarga(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::has('residents', '>=', 2)->with('kepalaKeluarga')->first();
        $kepala = $household->kepalaKeluarga;

        $this->assertNotNull($kepala);

        // Coba hapus kepala keluarga lewat ListResidents
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\ListResidents::class)
            ->call('deleteResident', $kepala->id);

        // Record kepala keluarga harus tetap ada di database (tidak terhapus)
        $this->assertNotSoftDeleted('residents', ['id' => $kepala->id]);

        // Temukan anggota biasa yang bukan kepala
        $ordinaryMember = $household->residents()->where('is_kepala_keluarga', false)->first();
        $this->assertNotNull($ordinaryMember);

        // Hapus anggota biasa harus berhasil
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\ListResidents::class)
            ->call('deleteResident', $ordinaryMember->id);

        $this->assertSoftDeleted('residents', ['id' => $ordinaryMember->id]);
    }

    /**
     * Pengujian fitur reactive search, filter status, filter gender, dan modal detail pada ListResidents.
     */
    public function test_resident_list_search_and_modals(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $resident = Resident::first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Residents\Pages\ListResidents::class)
            ->set('search', $resident->nama)
            ->assertSee($resident->nama)
            ->set('statusFilter', 'aktif')
            ->assertSet('statusFilter', 'aktif')
            ->set('genderFilter', 'L')
            ->assertSet('genderFilter', 'L')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('statusFilter', '')
            ->assertSet('genderFilter', '')
            ->call('openDetailResident', $resident->id)
            ->assertSet('detailResidentModalOpen', true)
            ->assertDispatched('open-detail-resident-modal')
            ->call('closeDetailResident')
            ->assertSet('detailResidentModalOpen', false)
            ->assertDispatched('close-detail-resident-modal');
    }

    /**
     * Pengujian fitur tampilan baru Layanan Administrasi: Surat Pengantar (search, filter, approval, reject, export).
     */
    public function test_letter_list_search_filter_approval_and_modals(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $resident = Resident::first();
        $type = \App\Models\LetterType::first();

        $letter = \App\Models\Letter::create([
            'resident_id' => $resident->id,
            'letter_type_id' => $type->id,
            'keperluan' => 'Permohonan pengujian fitur persetujuan admin RT 15',
            'status' => 'diajukan',
            'qr_token' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        // 1. Uji akses halaman HTTP GET (Validasi Aturan Standar Search Bar & Reset Filter)
        $response = $this->actingAs($ketuaRt)->get('/admin/letters');
        $response->assertStatus(200);
        $response->assertSee('LAYANAN ADMINISTRASI RT 15');
        $response->assertSee('Surat Pengantar');
        $response->assertSee('Buat Surat Pengantar');
        $response->assertSee('Ekspor Data');
        $response->assertSee('name="search"', false);
        // Standar: Tombol "Reset Filter" tidak boleh dirender saat tidak ada parameter filter aktif
        $response->assertDontSee('Reset Filter');

        // Standar: Saat ada parameter query aktif di URL (SSR), "Reset Filter" harus muncul
        $filteredResponse = $this->actingAs($ketuaRt)->get('/admin/letters?search=permohonan');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="permohonan"', false);

        // 2. Uji komponen Livewire ListLetters
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Letters\Pages\ListLetters::class)
            ->assertSee('LAYANAN ADMINISTRASI RT 15')
            ->assertSee($letter->keperluan)
            // Uji modal detail
            ->call('openDetailLetter', $letter->id)
            ->assertSet('detailLetterModalOpen', true)
            ->call('closeDetailLetter')
            ->assertSet('detailLetterModalOpen', false)
            // Uji modal persetujuan surat
            ->call('openApproveLetter', $letter->id)
            ->assertSet('approveLetterModalOpen', true)
            ->call('confirmApproveLetter')
            ->assertSet('approveLetterModalOpen', false);

        $this->assertEquals('disetujui', $letter->fresh()->status);
        $this->assertNotNull($letter->fresh()->nomor_surat);
        $this->assertNotNull($letter->fresh()->tanggal_disetujui);

        // 3. Uji penolakan surat
        $letterToReject = \App\Models\Letter::create([
            'resident_id' => $resident->id,
            'letter_type_id' => $type->id,
            'keperluan' => 'Pengujian penolakan surat dokumen tidak lengkap',
            'status' => 'diajukan',
            'qr_token' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Letters\Pages\ListLetters::class)
            ->call('openRejectLetter', $letterToReject->id)
            ->assertSet('rejectLetterModalOpen', true)
            ->set('reject_alasan', 'Lampiran foto KK buram dan tidak terbaca jelas.')
            ->call('confirmRejectLetter')
            ->assertSet('rejectLetterModalOpen', false);

        $this->assertEquals('ditolak', $letterToReject->fresh()->status);
        $this->assertEquals('Lampiran foto KK buram dan tidak terbaca jelas.', $letterToReject->fresh()->alasan_tolak);

        // 4. Uji ekspor CSV
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Letters\Pages\ListLetters::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian fitur tampilan baru Layanan Administrasi: Master Jenis Surat.
     */
    public function test_letter_types_list_and_management(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT tidak mengelola master teknis jenis surat
        $this->actingAs($ketuaRt)->get('/admin/letter-types')->assertStatus(403);

        // 1. Uji akses halaman HTTP GET oleh Sekretaris
        $response = $this->actingAs($sekretaris)->get('/admin/letter-types');
        $response->assertStatus(200);
        $response->assertSee('LAYANAN ADMINISTRASI RT 15');
        $response->assertSee('Master Jenis Surat');
        $response->assertSee('Tambah Jenis Surat');
        $response->assertSee('Ekspor Data');
        $response->assertSee('name="search"', false);
        // Standar: Tombol "Reset Filter" tidak boleh dirender di awal
        $response->assertDontSee('Reset Filter');

        // Standar: Saat ada parameter query aktif di URL (SSR), "Reset Filter" harus muncul
        $filteredResponse = $this->actingAs($sekretaris)->get('/admin/letter-types?search=skck');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="skck"', false);

        // 2. Uji komponen Livewire ListLetterTypes
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\LetterTypes\Pages\ListLetterTypes::class)
            ->call('openCreate')
            ->assertSet('letterTypeModalOpen', true)
            ->set('nama', 'Surat Izin Keramaian Warga')
            ->set('kode', 'SIK-WARGA')
            ->set('deskripsi', 'Izin mengadakan hajatan atau keramaian di lingkungan RT 15')
            ->set('format_nomor', '{NO}/SIK/RT.15/{ROMAN_MONTH}/{YEAR}')
            ->set('persyaratan_text', "Surat Pengantar RT\nFotokopi KTP\nJadwal Acara")
            ->set('is_active', true)
            ->set('urutan', 10)
            ->call('save')
            ->assertSet('letterTypeModalOpen', false);

        $this->assertDatabaseHas('letter_types', [
            'kode' => 'SIK-WARGA',
            'nama' => 'Surat Izin Keramaian Warga',
            'urutan' => 10,
        ]);

        $createdType = \App\Models\LetterType::where('kode', 'SIK-WARGA')->first();

        // Uji toggle status aktif
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\LetterTypes\Pages\ListLetterTypes::class)
            ->call('toggleActive', $createdType->id);

        $this->assertFalse((bool) $createdType->fresh()->is_active);

        // Uji ekspor CSV
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\LetterTypes\Pages\ListLetterTypes::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian fitur Buku Kas RT: Header Deep Navy, KPI Cards, search bar SSR & reset filter, modal CRUD, dan CSV export.
     */
    public function test_cash_transactions_list_stats_search_and_management(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // 1. Uji HTTP GET Halaman Buku Kas RT (Standar Search Bar & Reset Filter)
        $response = $this->actingAs($ketuaRt)->get('/admin/cash-transactions');
        $response->assertStatus(200);
        $response->assertSee('KEUANGAN RT 15');
        $response->assertSee('Buku Kas RT');
        $response->assertSee('Catat Transaksi Kas');
        $response->assertSee('Ekspor Data');
        $response->assertSee('TOTAL PEMASUKAN');
        $response->assertSee('TOTAL PENGELUARAN');
        $response->assertSee('SALDO KAS SAAT INI');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif harus memicu tombol Reset Filter
        $filteredResponse = $this->actingAs($ketuaRt)->get('/admin/cash-transactions?search=fogging');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="fogging"', false);

        // 2. Uji Komponen Livewire ListCashTransactions oleh Bendahara
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $tx = \App\Models\CashTransaction::first();

        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\CashTransactions\Pages\ListCashTransactions::class)
            ->assertSee('KEUANGAN RT 15')
            ->assertSee('Buku Kas RT')
            // Buka Modal Detail
            ->call('openDetail', $tx->id)
            ->assertSet('detailModalOpen', true)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false)
            // Buka Modal Tambah Transaksi
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_jenis', 'masuk')
            ->set('form_kategori', 'Donasi Warga')
            ->set('form_jumlah', '750000')
            ->set('form_tanggal', '2026-10-17')
            ->set('form_deskripsi', 'Donasi perlengkapan sound system rapat RT 15')
            ->call('save')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('cash_transactions', [
            'kategori' => 'Donasi Warga',
            'jumlah' => 750000.00,
        ]);

        $createdTx = \App\Models\CashTransaction::where('kategori', 'Donasi Warga')->first();

        // Uji Ubah Transaksi
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\CashTransactions\Pages\ListCashTransactions::class)
            ->call('openEdit', $createdTx->id)
            ->assertSet('formModalOpen', true)
            ->set('form_jumlah', '800000')
            ->call('save')
            ->assertSet('formModalOpen', false);

        $this->assertEquals(800000.00, (float) $createdTx->fresh()->jumlah);

        // Uji Hapus Transaksi
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\CashTransactions\Pages\ListCashTransactions::class)
            ->call('deleteTransaction', $createdTx->id);

        $this->assertSoftDeleted('cash_transactions', ['id' => $createdTx->id]);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\CashTransactions\Pages\ListCashTransactions::class)
            ->call('export')
            ->assertFileDownloaded();

        // 3. Uji Filter Periode Waktu & Rentang Tanggal Kas RT
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\CashTransactions\Pages\ListCashTransactions::class)
            ->assertSet('periodeFilter', 'semua')
            ->set('periodeFilter', 'bulan_ini')
            ->assertSet('periodeFilter', 'bulan_ini')
            ->assertSee('Kumulatif:')
            ->set('startDate', '2026-10-01')
            ->set('endDate', '2026-10-31')
            ->assertSet('periodeFilter', 'kustom')
            ->assertSee('Filter Tanggal Kas:')
            ->call('resetFilters')
            ->assertSet('periodeFilter', 'semua')
            ->assertSet('startDate', '')
            ->assertSet('endDate', '');

        // Uji Ekspor PDF & Excel dengan Filter Periode & Tanggal
        $this->actingAs($ketuaRt)->get('/admin/cash-transactions?periode=bulan_ini')->assertStatus(200)->assertSee('Reset Filter');
        
        $pdfAllTime = $this->actingAs($ketuaRt)->get('/admin/export/cash-transactions/pdf');
        $pdfAllTime->assertStatus(200);

        $pdfFiltered = $this->actingAs($ketuaRt)->get('/admin/export/cash-transactions/pdf?periode=bulan_ini');
        $pdfFiltered->assertStatus(200);

        $excelFiltered = $this->actingAs($ketuaRt)->get('/admin/export/cash-transactions/excel?start_date=2026-10-01&end_date=2026-10-31');
        $excelFiltered->assertStatus(200);
    }

    /**
     * Pengujian fitur Tagihan Iuran: Header Deep Navy, search bar SSR & reset filter, modal bayar cepat, terbit massal, dan CSV export.
     */
    public function test_invoices_list_stats_search_pay_and_create(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT tidak mengelola tagihan iuran teknis
        $this->actingAs($ketuaRt)->get('/admin/invoices')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Tagihan Iuran oleh Bendahara
        $response = $this->actingAs($bendahara)->get('/admin/invoices');
        $response->assertStatus(200);
        $response->assertSee('KEUANGAN RT 15');
        $response->assertSee('Tagihan Iuran Warga');
        $response->assertSee('Terbitkan Tagihan');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($bendahara)->get('/admin/invoices?search=kebersihan');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="kebersihan"', false);

        // 2. Uji Komponen Livewire ListInvoices oleh Bendahara
        $invoice = \App\Models\Invoice::where('status', 'belum_bayar')->first();

        if ($invoice) {
            \Livewire\Livewire::actingAs($bendahara)
                ->test(\App\Filament\Resources\Invoices\Pages\ListInvoices::class)
                ->assertSee('KEUANGAN RT 15')
                // Buka Modal Detail
                ->call('openDetail', $invoice->id)
                ->assertSet('detailModalOpen', true)
                ->call('closeDetail')
                ->assertSet('detailModalOpen', false)
                // Buka Modal Bayar Langsung / Tunai
                ->call('openPay', $invoice->id)
                ->assertSet('payModalOpen', true)
                ->set('pay_metode', 'tunai')
                ->set('pay_catatan', 'Pembayaran tunai lunas diterima pengurus')
                ->call('confirmPayment')
                ->assertSet('payModalOpen', false);

            $this->assertEquals('lunas', $invoice->fresh()->status);
            $this->assertDatabaseHas('payments', [
                'invoice_id' => $invoice->id,
                'metode' => 'tunai',
                'status' => 'valid',
            ]);
        }

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\Invoices\Pages\ListInvoices::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian fitur Validasi Pembayaran: Header Deep Navy, search bar SSR & reset filter, konfirmasi approval / reject, dan CSV export.
     */
    public function test_payments_list_stats_search_and_validation(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT tidak mengelola validasi pembayaran teknis
        $this->actingAs($ketuaRt)->get('/admin/payments')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Validasi Pembayaran oleh Bendahara
        $response = $this->actingAs($bendahara)->get('/admin/payments');
        $response->assertStatus(200);
        $response->assertSee('KEUANGAN RT 15');
        $response->assertSee('Validasi Pembayaran');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($bendahara)->get('/admin/payments?status=pending');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');

        // 2. Uji Komponen Livewire ListPayments oleh Bendahara
        $pendingPayment = \App\Models\Payment::where('status', 'pending')->first();

        if ($pendingPayment) {
            \Livewire\Livewire::actingAs($bendahara)
                ->test(\App\Filament\Resources\Payments\Pages\ListPayments::class)
                ->assertSee('KEUANGAN RT 15')
                // Buka modal approve
                ->call('openApprove', $pendingPayment->id)
                ->assertSet('validateModalOpen', true)
                ->assertSet('actionType', 'valid')
                ->call('processValidation')
                ->assertSet('validateModalOpen', false);

            $this->assertEquals('valid', $pendingPayment->fresh()->status);
            $this->assertEquals('lunas', $pendingPayment->fresh()->invoice->status);
        }

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\Payments\Pages\ListPayments::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian fitur Master Jenis Iuran: Header Deep Navy, search bar SSR & reset filter, toggle active, modal CRUD, dan CSV export.
     */
    public function test_fee_types_list_search_and_management(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT tidak mengelola master teknis jenis iuran
        $this->actingAs($ketuaRt)->get('/admin/fee-types')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Master Jenis Iuran oleh Bendahara
        $response = $this->actingAs($bendahara)->get('/admin/fee-types');
        $response->assertStatus(200);
        $response->assertSee('KEUANGAN RT 15');
        $response->assertSee('Master Jenis Iuran');
        $response->assertSee('Tambah Jenis Iuran');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($bendahara)->get('/admin/fee-types?search=kebersihan');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="kebersihan"', false);

        // 2. Uji Komponen Livewire ListFeeTypes oleh Bendahara
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\FeeTypes\Pages\ListFeeTypes::class)
            ->call('openCreate')
            ->assertSet('feeTypeModalOpen', true)
            ->set('nama', 'Iuran Swadaya Internet Lingkungan')
            ->set('deskripsi', 'Pemeliharaan wifi & CCTV lingkungan RT 15')
            ->set('nominal', '35000')
            ->set('periode', 'bulanan')
            ->set('is_active', true)
            ->call('save')
            ->assertSet('feeTypeModalOpen', false);

        $this->assertDatabaseHas('fee_types', [
            'nama' => 'Iuran Swadaya Internet Lingkungan',
            'nominal' => 35000.00,
        ]);

        $createdFt = \App\Models\FeeType::where('nama', 'Iuran Swadaya Internet Lingkungan')->first();

        // Uji toggle status aktif
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\FeeTypes\Pages\ListFeeTypes::class)
            ->call('toggleActive', $createdFt->id);

        $this->assertFalse((bool) $createdFt->fresh()->is_active);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($bendahara)
            ->test(\App\Filament\Resources\FeeTypes\Pages\ListFeeTypes::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Pengumuman Warga: Header Deep Navy, search bar SSR & dynamic reset, pin toggle, CRUD modal, dan CSV export.
     */
    public function test_announcements_list_stats_search_and_management(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan pengumuman ke Sekretaris
        $this->actingAs($ketuaRt)->get('/admin/announcements')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Pengumuman oleh Sekretaris
        $response = $this->actingAs($sekretaris)->get('/admin/announcements');
        $response->assertStatus(200);
        $response->assertSee('KOMUNIKASI WARGA RT 15');
        $response->assertSee('Pengumuman Warga');
        $response->assertSee('Buat Pengumuman');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($sekretaris)->get('/admin/announcements?search=bakti');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="bakti"', false);

        // 2. Uji Komponen Livewire ListAnnouncements oleh Sekretaris
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Announcements\Pages\ListAnnouncements::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_judul', 'Pemberitahuan Fogging Nyamuk DBD')
            ->set('form_isi', 'Akan dilaksanakan fogging serentak di seluruh gang RT 15 pada hari Minggu pagi.')
            ->set('form_target', 'semua')
            ->set('form_is_pinned', true)
            ->set('form_is_published', true)
            ->call('saveAnnouncement')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('announcements', [
            'judul' => 'Pemberitahuan Fogging Nyamuk DBD',
            'is_pinned' => true,
        ]);

        $created = \App\Models\Announcement::where('judul', 'Pemberitahuan Fogging Nyamuk DBD')->first();

        // Uji Toggle Pin
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Announcements\Pages\ListAnnouncements::class)
            ->call('togglePin', $created->id);

        $this->assertFalse((bool) $created->fresh()->is_pinned);

        // Uji Detail Modal
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Announcements\Pages\ListAnnouncements::class)
            ->call('openDetail', $created->id)
            ->assertSet('detailModalOpen', true)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Announcements\Pages\ListAnnouncements::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Siklus Hidup Status Dinamis Pengumuman (Real-Time Status Lifecycle):
     * Status otomatis berubah menjadi Berakhir / Kedaluwarsa ketika melewati tanggal berakhir,
     * dan pengumuman kedaluwarsa tidak muncul di dashboard portal warga.
     */
    public function test_announcement_dynamic_real_time_status_lifecycle(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();

        // 1. Buat Pengumuman yang sudah lewat periode tayang (Expired)
        $expiredAnnouncement = \App\Models\Announcement::create([
            'judul' => 'Pengumuman Kedaluwarsa Uji Coba',
            'isi' => 'Konten pengumuman yang sudah lewat masa aktifnya.',
            'target' => 'semua',
            'is_pinned' => false,
            'is_published' => true,
            'tanggal_tayang' => now()->subDays(2),
            'tanggal_berakhir' => now()->subHour(),
            'dibuat_oleh' => $sekretaris->id,
        ]);

        // 2. Buat Pengumuman yang masih aktif tayang (Active)
        $activeAnnouncement = \App\Models\Announcement::create([
            'judul' => 'Pengumuman Masih Aktif Tayang',
            'isi' => 'Konten pengumuman yang masih dalam periode tayang.',
            'target' => 'semua',
            'is_pinned' => false,
            'is_published' => true,
            'tanggal_tayang' => now()->subHour(),
            'tanggal_berakhir' => now()->addDays(3),
            'dibuat_oleh' => $sekretaris->id,
        ]);

        // 3. Verifikasi accessor status dinamis pada model
        $this->assertEquals('berakhir', $expiredAnnouncement->status);
        $this->assertEquals('Sudah Berakhir', $expiredAnnouncement->status_label);
        $this->assertEquals('tayang', $activeAnnouncement->status);
        $this->assertEquals('Sedang Tayang', $activeAnnouncement->status_label);

        // 4. Verifikasi filter status di komponen admin Livewire ListAnnouncements
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Announcements\Pages\ListAnnouncements::class)
            ->set('statusFilter', 'berakhir')
            ->assertSee('Pengumuman Kedaluwarsa Uji Coba')
            ->assertDontSee('Pengumuman Masih Aktif Tayang')
            ->call('openDetail', $expiredAnnouncement->id)
            ->assertSee('SUDAH BERAKHIR');

        // 5. Verifikasi di Portal Warga: pengumuman kedaluwarsa tidak ditampilkan ke warga
        $wargaUser = User::where('email', 'budi.santoso@warga.simlibas.com')->first()
            ?? User::whereDoesntHave('roles')->first();

        if ($wargaUser) {
            $response = $this->actingAs($wargaUser)->get(route('warga.dashboard'));
            $response->assertStatus(200);
            $response->assertSee('Pengumuman Masih Aktif Tayang');
            $response->assertDontSee('Pengumuman Kedaluwarsa Uji Coba');
        }
    }

    /**
     * Pengujian Pengaduan Warga: Header Deep Navy, search bar SSR & dynamic reset, tindak lanjut penanganan, dan CSV export.
     */
    public function test_complaints_list_stats_search_and_handling(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // 1. Uji HTTP GET Halaman Pengaduan
        $response = $this->actingAs($ketuaRt)->get('/admin/complaints');
        $response->assertStatus(200);
        $response->assertSee('KOMUNIKASI WARGA RT 15');
        $response->assertSee('Pengaduan & Aspirasi Warga');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($ketuaRt)->get('/admin/complaints?search=lampu');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="lampu"', false);

        // 2. Uji Komponen Livewire ListComplaints
        $complaint = \App\Models\Complaint::first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Complaints\Pages\ListComplaints::class)
            ->call('openDetail', $complaint->id)
            ->assertSet('detailModalOpen', true)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false)
            ->call('openHandling', $complaint->id)
            ->assertSet('handlingModalOpen', true)
            ->set('form_status', 'diproses')
            ->set('form_catatan_penanganan', 'Petugas keamanan RT sudah mengecek langsung ke lokasi.')
            ->call('saveHandling')
            ->assertSet('handlingModalOpen', false);

        $this->assertEquals('diproses', $complaint->fresh()->status);
        $this->assertEquals('Petugas keamanan RT sudah mengecek langsung ke lokasi.', $complaint->fresh()->catatan_penanganan);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Resources\Complaints\Pages\ListComplaints::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Agenda Kegiatan: Header Deep Navy, search bar SSR & dynamic reset, tambah agenda, dan CSV export.
     */
    public function test_events_list_stats_search_and_management(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan agenda ke Sekretaris
        $this->actingAs($ketuaRt)->get('/admin/events')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Agenda Kegiatan oleh Sekretaris
        $response = $this->actingAs($sekretaris)->get('/admin/events');
        $response->assertStatus(200);
        $response->assertSee('KOMUNIKASI WARGA RT 15');
        $response->assertSee('Agenda Kegiatan Warga');
        $response->assertSee('Tambah Agenda');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($sekretaris)->get('/admin/events?search=bakti');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="bakti"', false);

        // 2. Uji Komponen Livewire ListEvents oleh Sekretaris
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Events\Pages\ListEvents::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_judul', 'Senam Sehat Minggu Pagi Lansia & Warga')
            ->set('form_kategori', 'lainnya')
            ->set('form_penyelenggara', 'Kader Posyandu & Lansia')
            ->set('form_lokasi', 'Lapangan Olahraga RT 15')
            ->set('form_tanggal_mulai', now()->addDays(2)->format('Y-m-d\TH:i'))
            ->set('form_deskripsi', 'Kegiatan senam bersama instruktur profesional dilanjutkan sarapan sehat.')
            ->call('saveEvent')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('events', [
            'judul' => 'Senam Sehat Minggu Pagi Lansia & Warga',
            'penyelenggara' => 'Kader Posyandu & Lansia',
            'lokasi' => 'Lapangan Olahraga RT 15',
        ]);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\Events\Pages\ListEvents::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Lapor Tamu 1x24 Jam: Header Deep Navy, search bar SSR & dynamic reset, catat pulang, dan CSV export.
     */
    public function test_guest_reports_list_stats_search_and_management(): void
    {
        $koorKeamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan keamanan ke Koordinator Keamanan
        $this->actingAs($ketuaRt)->get('/admin/guest-reports')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Lapor Tamu oleh Koordinator Keamanan
        $response = $this->actingAs($koorKeamanan)->get('/admin/guest-reports');
        $response->assertStatus(200);
        $response->assertSee('KEAMANAN LINGKUNGAN RT 15');
        $response->assertSee('Lapor Tamu 1x24 Jam');
        $response->assertSee('Lapor Tamu Baru');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($koorKeamanan)->get('/admin/guest-reports?search=bandung');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="bandung"', false);

        // 2. Uji Komponen Livewire ListGuestReports oleh Koordinator Keamanan
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\GuestReports\Pages\ListGuestReports::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_household_id', $household->id)
            ->set('form_nama_tamu', 'Bambang Sudarmono')
            ->set('form_asal', 'Solo, Jawa Tengah')
            ->set('form_tanggal_datang', now()->format('Y-m-d'))
            ->set('form_tujuan', 'Silaturahmi keluarga mertua')
            ->call('saveGuestReport')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('guest_reports', [
            'nama_tamu' => 'Bambang Sudarmono',
            'asal' => 'Solo, Jawa Tengah',
        ]);

        $created = \App\Models\GuestReport::where('nama_tamu', 'Bambang Sudarmono')->first();

        // Uji Catat Pulang
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\GuestReports\Pages\ListGuestReports::class)
            ->call('catatPulang', $created->id);

        $this->assertNotNull($created->fresh()->tanggal_pulang);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\GuestReports\Pages\ListGuestReports::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Jadwal Ronda: Header Deep Navy, search bar SSR & dynamic reset, penugasan personil, dan CSV export.
     */
    public function test_patrol_schedules_list_stats_search_and_management(): void
    {
        $koorKeamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $resident = Resident::first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan jadwal ronda ke Koordinator Keamanan
        $this->actingAs($ketuaRt)->get('/admin/patrol-schedules')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Jadwal Ronda oleh Koordinator Keamanan
        $response = $this->actingAs($koorKeamanan)->get('/admin/patrol-schedules');
        $response->assertStatus(200);
        $response->assertSee('KEAMANAN LINGKUNGAN RT 15');
        $response->assertSee('Jadwal Ronda Malam');
        $response->assertSee('Tambah Jadwal Ronda');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($koorKeamanan)->get('/admin/patrol-schedules?search=garuda');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="garuda"', false);

        // 2. Uji Komponen Livewire ListPatrolSchedules oleh Koordinator Keamanan
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\PatrolSchedules\Pages\ListPatrolSchedules::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_tanggal', now()->addDays(1)->format('Y-m-d'))
            ->set('form_regu', 'Regu 4 (Rajawali)')
            ->set('form_waktu_mulai', '23:00')
            ->set('form_waktu_selesai', '04:00')
            ->set('form_catatan', 'Piket pos ronda timur dan patroli portal gang.')
            ->set('form_resident_ids', [(string) $resident->id])
            ->call('saveSchedule')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('patrol_schedules', [
            'regu' => 'Regu 4 (Rajawali)',
        ]);

        $schedule = \App\Models\PatrolSchedule::where('regu', 'Regu 4 (Rajawali)')->first();
        $this->assertDatabaseHas('patrol_members', [
            'patrol_schedule_id' => $schedule->id,
            'resident_id' => $resident->id,
        ]);

        // Uji Detail Modal
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\PatrolSchedules\Pages\ListPatrolSchedules::class)
            ->call('openDetail', $schedule->id)
            ->assertSet('detailModalOpen', true)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($koorKeamanan)
            ->test(\App\Filament\Resources\PatrolSchedules\Pages\ListPatrolSchedules::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Katalog UMKM Warga: Header Deep Navy, search bar SSR & dynamic reset, registrasi, moderasi status, dan CSV export.
     */
    public function test_msme_profiles_list_stats_search_and_moderation(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan kurasi UMKM ke Sekretaris
        $this->actingAs($ketuaRt)->get('/admin/msme-profiles')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Katalog UMKM oleh Sekretaris
        $response = $this->actingAs($sekretaris)->get('/admin/msme-profiles');
        $response->assertStatus(200);
        $response->assertSee('KAMPUNG DIGITAL RT 15');
        $response->assertSee('Katalog UMKM Warga');
        $response->assertSee('Daftarkan UMKM');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($sekretaris)->get('/admin/msme-profiles?search=warung');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="warung"', false);

        // 2. Uji Komponen Livewire ListMsmeProfiles oleh Sekretaris
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_household_id', $household->id)
            ->set('form_nama_usaha', 'Warung Sambal Bu Dedi')
            ->set('form_kategori', 'kuliner')
            ->set('form_telepon', '081298765432')
            ->set('form_produk', 'Sambal Bawang & Tempe Mendoan')
            ->set('form_deskripsi', 'Menyediakan aneka lauk matang higienis.')
            ->call('saveMsme')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('msme_profiles', [
            'nama_usaha' => 'Warung Sambal Bu Dedi',
            'kategori' => 'kuliner',
            'status_moderasi' => 'disetujui',
        ]);

        $msme = \App\Models\MsmeProfile::where('nama_usaha', 'Warung Sambal Bu Dedi')->first();

        // Uji Moderasi
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openModeration', $msme->id)
            ->assertSet('moderationModalOpen', true)
            ->set('form_status_moderasi', 'ditolak')
            ->set('form_catatan_moderasi', 'Foto profil usaha belum jelas')
            ->call('saveModeration')
            ->assertSet('moderationModalOpen', false);

        $this->assertEquals('ditolak', $msme->fresh()->status_moderasi);

        // Uji Toggle Aktif
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('toggleActive', $msme->id);

        $this->assertFalse($msme->fresh()->is_active);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Alur Review UMKM & Pemisahan Aksi Berdasarkan Status (Menunggu Review, Disetujui, Ditolak)
     */
    public function test_msme_review_workflow_and_action_separation(): void
    {
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first();
        $household = Household::first();

        // 1. Buat UMKM dengan status pending (Menunggu Review)
        $pendingMsme = \App\Models\MsmeProfile::create([
            'household_id' => $household->id,
            'nama_usaha' => 'Kedai Kopi Kerinci',
            'kategori' => 'kuliner',
            'telepon' => '081211112222',
            'whatsapp' => '081211112222',
            'produk' => 'Kopi Arabika & Robusta',
            'deskripsi' => 'Kopi asli seduh manual khas Jambi.',
            'status_moderasi' => 'pending',
            'is_active' => false,
        ]);

        // Uji tampilan tabel untuk UMKM pending: tombol Review
        $resPending = $this->actingAs($sekretaris)->get('/admin/msme-profiles?search=Kedai+Kopi+Kerinci');
        $resPending->assertStatus(200);
        $resPending->assertSee('Review Pengajuan UMKM Warga');
        $resPending->assertSee('Menunggu Review');

        // 2. Uji Popup Review UMKM & Tindakan Setujui
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openModeration', $pendingMsme->id)
            ->assertSet('moderationModalOpen', true)
            ->assertSet('moderatingId', $pendingMsme->id)
            ->call('approveMsme')
            ->assertSet('moderationModalOpen', false);

        $pendingMsme->refresh();
        $this->assertEquals('disetujui', $pendingMsme->status_moderasi);
        $this->assertTrue($pendingMsme->is_active);

        // Uji baris yang disetujui: hanya 3 tombol (Lihat Detail -> Edit -> Hapus), tanpa tombol globe/perisai
        $resApproved = $this->actingAs($sekretaris)->get('/admin/msme-profiles?search=Kedai+Kopi+Kerinci');
        $resApproved->assertStatus(200);
        $resApproved->assertSee('Lihat Detail Profil UMKM');
        $resApproved->assertSee('Ubah Data Usaha');
        $resApproved->assertSee('Hapus UMKM');
        $resApproved->assertDontSee('Ubah Status Moderasi UMKM');
        $resApproved->assertDontSee('Tayang di Katalog Online (Klik untuk Sembunyikan)');

        // 3. Buat pengajuan baru untuk menguji penolakan dengan catatan wajib
        $rejectedMsme = \App\Models\MsmeProfile::create([
            'household_id' => $household->id,
            'nama_usaha' => 'Toko Gadget Abal',
            'kategori' => 'jasa',
            'telepon' => '081299998888',
            'status_moderasi' => 'pending',
            'is_active' => false,
        ]);

        // Coba Tolak tanpa mengisi alasan penolakan -> harus gagal validasi
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openModeration', $rejectedMsme->id)
            ->set('form_catatan_moderasi', '')
            ->call('rejectMsme')
            ->assertHasErrors(['form_catatan_moderasi']);

        // Tolak dengan alasan penolakan yang valid
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openModeration', $rejectedMsme->id)
            ->set('form_catatan_moderasi', 'Usaha tidak berlokasi di wilayah RT 15 Pematang Sulur')
            ->call('rejectMsme')
            ->assertSet('moderationModalOpen', false);

        $rejectedMsme->refresh();
        $this->assertEquals('ditolak', $rejectedMsme->status_moderasi);
        $this->assertFalse($rejectedMsme->is_active);
        $this->assertEquals('Usaha tidak berlokasi di wilayah RT 15 Pematang Sulur', $rejectedMsme->catatan_moderasi);

        // Uji baris yang ditolak: hanya Lihat Detail & Hapus (tanpa Edit)
        $resRejected = $this->actingAs($sekretaris)->get('/admin/msme-profiles?search=Toko+Gadget+Abal');
        $resRejected->assertStatus(200);
        $resRejected->assertSee('Lihat Alasan Penolakan dan Detail Pengajuan');
        $resRejected->assertSee('Hapus Arsip Pengajuan Ditolak');

        // Uji Detail Modal menampilkan catatan penolakan
        \Livewire\Livewire::actingAs($sekretaris)
            ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
            ->call('openDetail', $rejectedMsme->id)
            ->assertSet('detailModalOpen', true)
            ->assertSee('Alasan Penolakan Pengajuan:')
            ->assertSee('Usaha tidak berlokasi di wilayah RT 15 Pematang Sulur');

        // 4. Uji Otorisasi: Pengguna non-pengurus dilarang mengeksekusi approveMsme
        $wargaUser = User::where('role', 'warga')->first();
        if ($wargaUser) {
            try {
                \Livewire\Livewire::actingAs($wargaUser)
                    ->test(\App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles::class)
                    ->set('moderatingId', $rejectedMsme->id)
                    ->call('approveMsme');
                $this->fail('Pengguna warga tidak boleh memiliki otorisasi moderasi UMKM.');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertEquals(403, $e->getStatusCode());
            }
        }
    }

    /**
     * Pengujian Setoran Bank Sampah: Header Deep Navy, search bar SSR & dynamic reset, penimbangan, integrasi tabungan keluarga, dan CSV export.
     */
    public function test_waste_deposits_list_stats_search_and_management(): void
    {
        $koorBankSampah = User::where('email', 'banksampah@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $household = Household::first();
        $wasteType = \App\Models\WasteType::first();

        // Validasi pembatasan peran eksekutif: Ketua RT mendelegasikan bank sampah ke Koordinator Bank Sampah
        $this->actingAs($ketuaRt)->get('/admin/waste-deposits')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Setoran Bank Sampah oleh Koordinator Bank Sampah
        $response = $this->actingAs($koorBankSampah)->get('/admin/waste-deposits');
        $response->assertStatus(200);
        $response->assertSee('KAMPUNG DIGITAL RT 15');
        $response->assertSee('Setoran Bank Sampah Lestari');
        $response->assertSee('Catat Penimbangan');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($koorBankSampah)->get('/admin/waste-deposits?search=botol');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="botol"', false);

        // 2. Uji Komponen Livewire ListWasteDeposits oleh Koordinator Bank Sampah
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_household_id', $household->id)
            ->set('form_waste_type_id', $wasteType->id)
            ->set('form_berat_kg', '5.00')
            ->set('form_harga_saat_setor', '3000')
            ->set('form_catatan', 'Setoran kardus bersih')
            ->call('saveDeposit')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('waste_deposits', [
            'household_id' => $household->id,
            'waste_type_id' => $wasteType->id,
            'berat_kg' => 5.00,
            'nilai' => 15000.00,
        ]);

        $deposit = \App\Models\WasteDeposit::where('household_id', $household->id)->latest()->first();

        // Uji Tabungan Keluarga Bertambah
        $this->assertDatabaseHas('waste_wallets', [
            'household_id' => $household->id,
        ]);
        $this->assertDatabaseHas('waste_wallet_transactions', [
            'household_id' => $household->id,
            'jenis' => 'setoran',
            'jumlah' => 15000.00,
        ]);

        // Uji Detail Modal
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits::class)
            ->call('openDetail', $deposit->id)
            ->assertSet('detailModalOpen', true)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Pengujian Master Harga Sampah: Header Deep Navy, search bar SSR & dynamic reset, CRUD komoditas, toggle aktif, dan CSV export.
     */
    public function test_waste_types_list_stats_search_and_management(): void
    {
        $koorBankSampah = User::where('email', 'banksampah@simlibas.com')->first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        // Validasi pembatasan peran eksekutif: Ketua RT tidak mengelola master teknis harga sampah
        $this->actingAs($ketuaRt)->get('/admin/waste-types')->assertStatus(403);

        // 1. Uji HTTP GET Halaman Master Harga Sampah oleh Koordinator Bank Sampah
        $response = $this->actingAs($koorBankSampah)->get('/admin/waste-types');
        $response->assertStatus(200);
        $response->assertSee('KAMPUNG DIGITAL RT 15');
        $response->assertSee('Master Harga Sampah');
        $response->assertSee('Tambah Komoditas');
        $response->assertSee('name="search"', false);
        $response->assertDontSee('Reset Filter');

        // Parameter query URL aktif
        $filteredResponse = $this->actingAs($koorBankSampah)->get('/admin/waste-types?search=plastik');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Reset Filter');
        $filteredResponse->assertSee('value="plastik"', false);

        // 2. Uji Komponen Livewire ListWasteTypes oleh Koordinator Bank Sampah
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteTypes\Pages\ListWasteTypes::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_nama', 'Kaleng Alumunium Minuman')
            ->set('form_kategori', 'anorganik')
            ->set('form_harga_per_kg', '12000')
            ->set('form_satuan', 'kg')
            ->call('saveWasteType')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('waste_types', [
            'nama' => 'Kaleng Alumunium Minuman',
            'harga_per_kg' => 12000.00,
            'kategori' => 'anorganik',
        ]);

        $wasteType = \App\Models\WasteType::where('nama', 'Kaleng Alumunium Minuman')->first();

        // Uji Toggle Aktif
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteTypes\Pages\ListWasteTypes::class)
            ->call('toggleActive', $wasteType->id);

        $this->assertFalse($wasteType->fresh()->is_active);

        // Uji Ekspor CSV
        \Livewire\Livewire::actingAs($koorBankSampah)
            ->test(\App\Filament\Resources\WasteTypes\Pages\ListWasteTypes::class)
            ->call('export')
            ->assertFileDownloaded();
    }

    /**
     * Menu Pengaturan Sistem hanya dapat diakses oleh Super Admin.
     */
    public function test_super_admin_can_access_setting_resource(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();

        $response = $this->actingAs($superAdmin)->get('/admin/rt-settings');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem');
        $response->assertSee('Rukun Tetangga 15');
    }

    /**
     * Role pengurus lain (Ketua RT, Bendahara, Keamanan, Bank Sampah) dilarang mengakses Pengaturan Sistem.
     */
    public function test_non_super_admin_cannot_access_setting_resource(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $keamanan = User::where('email', 'keamanan@simlibas.com')->first();
        $bankSampah = User::where('email', 'banksampah@simlibas.com')->first();

        $this->actingAs($ketuaRt)->get('/admin/rt-settings')->assertStatus(403);
        $this->actingAs($bendahara)->get('/admin/rt-settings')->assertStatus(403);
        $this->actingAs($keamanan)->get('/admin/rt-settings')->assertStatus(403);
        $this->actingAs($bankSampah)->get('/admin/rt-settings')->assertStatus(403);
    }

    /**
     * Super admin dapat memperbarui data pengaturan sistem via custom Livewire modal.
     */
    public function test_super_admin_can_update_rt_setting_via_custom_page(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $setting = \App\Models\RtSetting::instance();

        Livewire::actingAs($superAdmin)
            ->test(\App\Filament\Resources\RtSettings\Pages\ListRtSettings::class)
            ->assertSee('Pengaturan Sistem')
            ->assertSee('Wilayah Administrasi')
            ->assertSee('Pimpinan RT 15')
            ->call('openEdit', $setting->id)
            ->assertSet('formModalOpen', true)
            ->set('telepon', '0812-9999-8888')
            ->call('saveSetting')
            ->assertHasNoErrors()
            ->assertSet('formModalOpen', false);

        $this->assertEquals('0812-9999-8888', $setting->fresh()->telepon);
    }

    /**
     * Seluruh akun pengurus RT dapat mengakses halaman Profil Pengurus.
     */
    public function test_all_pengurus_roles_can_access_profile_page(): void
    {
        $rolesToTest = [
            'admin@simlibas.com',
            'rt@simlibas.com',
            'sekretaris@simlibas.com',
            'bendahara@simlibas.com',
            'keamanan@simlibas.com',
            'banksampah@simlibas.com',
        ];

        foreach ($rolesToTest as $email) {
            $user = User::where('email', $email)->first();
            $response = $this->actingAs($user)->get('/admin/profile');

            $response->assertStatus(200);
            $response->assertSee('Profil Pengurus');
            $response->assertSee('Informasi Akun & Pengurus');
            $response->assertSee('Keamanan & Kata Sandi');
        }
    }

    /**
     * Pengurus dapat memperbarui data profil akunnya (nama dan nomor telepon).
     */
    public function test_pengurus_can_update_profile_data(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first();

        \Livewire\Livewire::actingAs($ketuaRt)
            ->test(\App\Filament\Pages\Auth\EditProfile::class)
            ->set('data.name', 'Bpk. H. Ahmad Sudirman, S.E., M.M.')
            ->set('data.phone', '0812-7400-9999')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $ketuaRt->id,
            'name' => 'Bpk. H. Ahmad Sudirman, S.E., M.M.',
            'phone' => '0812-7400-9999',
        ]);
    }
}

