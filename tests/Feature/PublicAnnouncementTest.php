<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Card pengumuman di homepage memiliki link ke halaman detail pengumuman.
     */
    public function test_homepage_announcement_card_links_to_detail(): void
    {
        $announcement = Announcement::first();
        $this->assertNotNull($announcement);

        $response = $this->get(route('public.home'));
        $response->assertStatus(200);

        $detailUrl = route('public.pengumuman.detail', $announcement->id);
        $response->assertSee($detailUrl, false);
        $response->assertSee('Baca Selengkapnya');
    }

    /**
     * Halaman detail pengumuman dapat diakses dan menampilkan konten lengkap.
     */
    public function test_announcement_detail_page_renders_content(): void
    {
        $admin = User::first();
        $announcement = Announcement::create([
            'judul' => 'Uji Coba Pengumuman Detail RT 15',
            'isi' => 'Ini adalah isi pengumuman lengkap yang memuat informasi detail seputar kegiatan warga.',
            'target' => 'semua',
            'is_published' => true,
            'is_pinned' => true,
            'dibuat_oleh' => $admin->id,
        ]);

        $response = $this->get(route('public.pengumuman.detail', $announcement->id));
        $response->assertStatus(200);
        $response->assertSee('Uji Coba Pengumuman Detail RT 15');
        $response->assertSee('Ini adalah isi pengumuman lengkap');
        $response->assertSee('Sasaran: SEMUA');
        $response->assertSee('📌 Penting');
        $response->assertSee('Uraian Pengumuman Lengkap');
        $response->assertSee('Beranda');
        $response->assertSee('Pengumuman');
        $response->assertSee('Detail');
        $response->assertSee('Bagikan ke Grup WhatsApp RT');
        $response->assertDontSee('Kembali ke Beranda');
        $response->assertDontSee('Status Pengumuman');
        $response->assertDontSee('Diterbitkan resmi oleh Pengurus RT 15 Pematang Sulur');
    }

    /**
     * Halaman detail pengumuman mengembalikan 404 jika belum dipublikasikan atau ID tidak ditemukan.
     */
    public function test_announcement_detail_returns_404_when_unpublished_or_missing(): void
    {
        $admin = User::first();
        $draft = Announcement::create([
            'judul' => 'Pengumuman Masih Draft',
            'isi' => 'Belum boleh dilihat warga.',
            'target' => 'semua',
            'is_published' => false,
            'dibuat_oleh' => $admin->id,
        ]);

        $responseDraft = $this->get(route('public.pengumuman.detail', $draft->id));
        $responseDraft->assertStatus(404);

        $responseMissing = $this->get(route('public.pengumuman.detail', 99999));
        $responseMissing->assertStatus(404);
    }
}
