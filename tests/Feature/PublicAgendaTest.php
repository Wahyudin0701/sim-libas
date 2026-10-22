<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAgendaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Card agenda di homepage memiliki link ke halaman detail agenda kegiatan.
     */
    public function test_homepage_agenda_card_links_to_detail(): void
    {
        $admin = User::first();
        $event = Event::create([
            'judul' => 'Senam Pagi Bersama Warga RT 15',
            'deskripsi' => 'Kegiatan olahraga pagi demi menjaga kebugaran seluruh keluarga.',
            'lokasi' => 'Lapangan Fasum RT 15',
            'tanggal_mulai' => now()->addDays(2)->setHour(6)->setMinute(30),
            'tanggal_selesai' => now()->addDays(2)->setHour(8)->setMinute(0),
            'kategori' => 'perayaan',
            'penyelenggara' => 'Seksi Olahraga RT 15',
            'dibuat_oleh' => $admin->id,
        ]);

        $response = $this->get(route('public.home'));
        $response->assertStatus(200);

        $detailUrl = route('public.agenda.detail', $event->id);
        $response->assertSee($detailUrl, false);
        $response->assertSee('Senam Pagi Bersama Warga RT 15');
    }

    /**
     * Halaman detail agenda dapat diakses dan menampilkan konten lengkap.
     */
    public function test_agenda_detail_page_renders_content(): void
    {
        $admin = User::first();
        $event = Event::create([
            'judul' => 'Rapat Koordinasi Evaluasi RT 15',
            'deskripsi' => 'Membahas agenda kebersihan lingkungan dan persiapan program bank sampah.',
            'lokasi' => 'Balai Warga RT 15',
            'tanggal_mulai' => now()->addDays(3)->setHour(19)->setMinute(30),
            'tanggal_selesai' => now()->addDays(3)->setHour(21)->setMinute(30),
            'kategori' => 'rapat',
            'penyelenggara' => 'Pengurus Inti RT 15',
            'dibuat_oleh' => $admin->id,
        ]);

        $response = $this->get(route('public.agenda.detail', $event->id));
        $response->assertStatus(200);
        $response->assertSee('Rapat Koordinasi Evaluasi RT 15');
        $response->assertSee('Membahas agenda kebersihan lingkungan');
        $response->assertSee('Balai Warga RT 15');
        $response->assertSee('Pengurus Inti RT 15');
        $response->assertSee('Rapat');
        $response->assertSee('Beranda');
        $response->assertSee('Agenda');
        $response->assertSee('Detail');
        $response->assertSee('Uraian Agenda Kegiatan');
        $response->assertSee('Bagikan ke Grup WhatsApp RT');
        $response->assertSee('Sekretariat RT 15');
        $response->assertDontSee('🏛️');
    }

    /**
     * Halaman detail agenda mengembalikan 404 jika ID agenda tidak ditemukan.
     */
    public function test_agenda_detail_returns_404_when_missing(): void
    {
        $response = $this->get(route('public.agenda.detail', 99999));
        $response->assertStatus(404);
    }
}
