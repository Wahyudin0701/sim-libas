<?php

namespace App\Filament\Resources\RtSettings\Pages;

use App\Filament\Resources\RtSettings\RtSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRtSettings extends ListRecords
{
    protected static string $resource = RtSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
