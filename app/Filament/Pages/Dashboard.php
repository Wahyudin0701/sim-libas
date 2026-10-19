<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    /**
     * Sembunyikan heading pada body Dashboard karena judul "Dashboard" sudah berada di Topbar.
     */
    public function getHeading(): string
    {
        return '';
    }

    /**
     * Sembunyikan breadcrumbs pada Dashboard agar tidak terjadi duplikasi teks "Dashboard".
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
