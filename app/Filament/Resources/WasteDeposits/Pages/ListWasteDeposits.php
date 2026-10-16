<?php

namespace App\Filament\Resources\WasteDeposits\Pages;

use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWasteDeposits extends ListRecords
{
    protected static string $resource = WasteDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
