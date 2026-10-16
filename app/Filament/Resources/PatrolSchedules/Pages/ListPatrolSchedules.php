<?php

namespace App\Filament\Resources\PatrolSchedules\Pages;

use App\Filament\Resources\PatrolSchedules\PatrolScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrolSchedules extends ListRecords
{
    protected static string $resource = PatrolScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
