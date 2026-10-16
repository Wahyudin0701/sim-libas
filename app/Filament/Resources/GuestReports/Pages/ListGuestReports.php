<?php

namespace App\Filament\Resources\GuestReports\Pages;

use App\Filament\Resources\GuestReports\GuestReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuestReports extends ListRecords
{
    protected static string $resource = GuestReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
