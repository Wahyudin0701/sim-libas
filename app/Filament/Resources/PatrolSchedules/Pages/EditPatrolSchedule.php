<?php

namespace App\Filament\Resources\PatrolSchedules\Pages;

use App\Filament\Resources\PatrolSchedules\PatrolScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrolSchedule extends EditRecord
{
    protected static string $resource = PatrolScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
