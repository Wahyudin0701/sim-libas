<?php

namespace App\Filament\Resources\WasteDeposits\Pages;

use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWasteDeposit extends EditRecord
{
    protected static string $resource = WasteDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
