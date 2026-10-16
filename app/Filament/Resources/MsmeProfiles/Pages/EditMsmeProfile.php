<?php

namespace App\Filament\Resources\MsmeProfiles\Pages;

use App\Filament\Resources\MsmeProfiles\MsmeProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMsmeProfile extends EditRecord
{
    protected static string $resource = MsmeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
