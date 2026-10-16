<?php

namespace App\Filament\Resources\GuestReports\Pages;

use App\Filament\Resources\GuestReports\GuestReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGuestReport extends EditRecord
{
    protected static string $resource = GuestReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
