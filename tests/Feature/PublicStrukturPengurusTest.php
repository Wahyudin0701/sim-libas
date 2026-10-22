<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicStrukturPengurusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_struktur_pengurus_page_is_accessible(): void
    {
        $response = $this->get('/struktur-pengurus');
        $response->assertStatus(200);
        $response->assertSee('Dr. Sigit Indrawijaya, SE, MSi');
        $response->assertSee('Alexander Charles');
        $response->assertSee('Rully Sondri Mudahar');
        $response->assertSee('Yudhistira Adiwijaya');
        $response->assertSee('Dwi Kuswanto');
        $response->assertSee('Tony Mulyady');
        $response->assertSee('M. Imam Agus Sutopo');
        $response->assertSee('Kresna');
        $response->assertSee('Pemberdayaan Kesejahteraan Keluarga');
        $response->assertSee('Masjid Nurul Fuqon');
        $response->assertSee('Lembaga Adat Masyarakat');
    }

    public function test_struktur_alias_redirects_to_struktur_pengurus(): void
    {
        $response = $this->get('/struktur');
        $response->assertRedirect('/struktur-pengurus');

        $response2 = $this->get('/struktur-organisasi');
        $response2->assertRedirect('/struktur-pengurus');
    }
}
