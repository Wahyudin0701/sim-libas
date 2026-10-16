<?php

namespace App\Filament\Resources\MsmeProfiles\Pages;

use App\Filament\Resources\MsmeProfiles\MsmeProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMsmeProfiles extends ListRecords
{
    protected static string $resource = MsmeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
