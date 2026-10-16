<?php

namespace App\Filament\Resources\RtSettings\Pages;

use App\Filament\Resources\RtSettings\RtSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRtSetting extends EditRecord
{
    protected static string $resource = RtSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
