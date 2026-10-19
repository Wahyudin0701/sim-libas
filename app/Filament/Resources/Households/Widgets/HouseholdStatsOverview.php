<?php

namespace App\Filament\Resources\Households\Widgets;

use App\Models\Household;
use App\Models\Resident;
use Filament\Widgets\Widget;

class HouseholdStatsOverview extends Widget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.resources.households.widgets.household-stats-overview';

    public function getViewData(): array
    {
        $totalKk = Household::count();
        $hunianTetap = Household::where('status_hunian', 'tetap')->count();
        $kontrakKos = Household::whereIn('status_hunian', ['kontrak', 'kos'])->count();
        $totalJiwa = Resident::where('status_kependudukan', 'aktif')->count();

        return [
            'totalKk' => $totalKk,
            'hunianTetap' => $hunianTetap,
            'kontrakKos' => $kontrakKos,
            'totalJiwa' => $totalJiwa,
        ];
    }
}
