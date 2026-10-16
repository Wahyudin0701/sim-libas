<?php

namespace Tests\Feature;

use App\Filament\Widgets\StatsOverview;
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
        $ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->first();

        $response = $this->actingAs($ketuaRt)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SIM-LiBas RT 15');
    }

    /**
     * Widget KPI Dashboard memuat statistik data warga dan kas RT dengan benar.
     */
    public function test_kpi_stats_overview_widget_renders_data(): void
    {
        $ketuaRt = User::where('email', 'ketua.rt@simlibas.id')->first();

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
        $warga = User::where('email', 'warga.dedi@simlibas.id')->first();

        $response = $this->actingAs($warga)->get('/admin');

        $response->assertStatus(403);
    }
}
