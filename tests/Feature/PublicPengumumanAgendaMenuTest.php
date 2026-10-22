<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPengumumanAgendaMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Halaman menu direktori pengumuman dapat diakses dengan pencarian dan paginasi.
     */
    public function test_pengumuman_index_renders_properly(): void
    {
        $admin = User::first();
        Announcement::create([
            'judul' => 'Pengumuman Kerja Bakti Akbar RT 15',
            'isi' => 'Seluruh warga diharapkan hadir membawa peralatan kebersihan.',
            'target' => 'semua',
            'is_published' => true,
            'is_pinned' => true,
            'dibuat_oleh' => $admin->id,
        ]);

        $response = $this->get(route('public.pengumuman.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengumuman RT 15 Pematang Sulur');
        $response->assertSee('Pengumuman Kerja Bakti Akbar RT 15');
        $response->assertSee('📌 Penting');
        $response->assertSee('Baca Selengkapnya');
        $response->assertSee('grid-cols-2', false);

        // Test pencarian
        $searchResponse = $this->get(route('public.pengumuman.index', ['q' => 'Kerja Bakti']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Pengumuman Kerja Bakti Akbar RT 15');

        $searchEmpty = $this->get(route('public.pengumuman.index', ['q' => 'KataKunciYangTidakAda123']));
        $searchEmpty->assertStatus(200);
        $searchEmpty->assertSee('Pengumuman Tidak Ditemukan');
    }

    /**
     * Halaman menu direktori agenda kegiatan dapat diakses dengan filter kategori dan pencarian.
     */
    public function test_agenda_index_renders_properly(): void
    {
        $admin = User::first();
        $event = Event::create([
            'judul' => 'Senam Pagi dan Cek Kesehatan RT 15',
            'deskripsi' => 'Kegiatan rutin bulanan warga RT 15.',
            'lokasi' => 'Balai Pertemuan RT 15',
            'tanggal_mulai' => now()->addDays(5)->setHour(7)->setMinute(0),
            'kategori' => 'perayaan',
            'penyelenggara' => 'Seksi Kesehatan PKK RT 15',
            'dibuat_oleh' => $admin->id,
        ]);

        $response = $this->get(route('public.agenda.index'));
        $response->assertStatus(200);
        $response->assertSee('Agenda Kegiatan Warga RT 15');
        $response->assertSee('Senam Pagi dan Cek Kesehatan RT 15');
        $response->assertSee('Perayaan');
        $response->assertSee('Lihat Detail Agenda');
        $response->assertSee('grid-cols-2', false);

        // Test filter kategori
        $filterResponse = $this->get(route('public.agenda.index', ['kategori' => 'perayaan']));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Senam Pagi dan Cek Kesehatan RT 15');

        $filterEmpty = $this->get(route('public.agenda.index', ['kategori' => 'arisan']));
        $filterEmpty->assertStatus(200);
        $filterEmpty->assertSee('Agenda Tidak Ditemukan');
    }

    /**
     * Homepage beranda memiliki tombol "Lihat Semua" ke menu pengumuman dan menu agenda.
     */
    public function test_homepage_links_to_pengumuman_and_agenda_menus(): void
    {
        $response = $this->get(route('public.home'));
        $response->assertStatus(200);

        $response->assertSee(route('public.pengumuman.index'), false);
        $response->assertSee(route('public.agenda.index'), false);
        $response->assertSee('Lihat Semua');
    }

    /**
     * Navigasi navbar di layout publik memuat tautan menu Pengumuman dan Agenda.
     */
    public function test_navbar_contains_pengumuman_and_agenda_links(): void
    {
        $response = $this->get(route('public.home'));
        $response->assertStatus(200);

        // Desktop & mobile menu links
        $response->assertSee(route('public.pengumuman.index'), false);
        $response->assertSee(route('public.agenda.index'), false);
        $response->assertSee('mobile-menu-btn', false);
        $response->assertSee('id="mobile-menu"', false);
        $response->assertSee('lg:hidden', false);
    }

    /**
     * Searchbar dan filter terbungkus dalam card dan banner promosi UMKM dihapus.
     */
    public function test_search_and_filter_wrapped_in_card_and_umkm_promo_banner_removed(): void
    {
        // Pengumuman index
        $resPengumuman = $this->get(route('public.pengumuman.index'));
        $resPengumuman->assertStatus(200);
        $resPengumuman->assertSee('bg-white border border-border-soft rounded-2xl shadow-sm p-4 sm:p-5 mb-6', false);

        // Agenda index
        $resAgenda = $this->get(route('public.agenda.index'));
        $resAgenda->assertStatus(200);
        $resAgenda->assertSee('bg-white border border-border-soft rounded-2xl shadow-sm p-4 sm:p-5 mb-6', false);

        // UMKM index
        $resUmkm = $this->get(route('public.umkm'));
        $resUmkm->assertStatus(200);
        $resUmkm->assertSee('bg-white border border-border-soft rounded-2xl shadow-sm p-4 sm:p-5 mb-6', false);
        $resUmkm->assertDontSee('Punya Usaha Rumahan atau Jasa?');
        $resUmkm->assertDontSee('Daftarkan Usaha Anda');

        // Reset Filter button tersembunyi ketika tidak ada filter yang digunakan
        $resPengumuman->assertSee('id="search-reset-btn"', false);
        $resPengumuman->assertSee('display: none;', false);

        $resAgenda->assertSee('id="search-reset-btn"', false);
        $resAgenda->assertSee('display: none;', false);

        $resUmkm->assertSee('id="search-reset-btn"', false);
        $resUmkm->assertSee('display: none;', false);
    }
}
