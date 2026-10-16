<?php

namespace App\Filament\Resources\PatrolSchedules;

use UnitEnum;

use App\Filament\Resources\PatrolSchedules\Pages\CreatePatrolSchedule;
use App\Filament\Resources\PatrolSchedules\Pages\EditPatrolSchedule;
use App\Filament\Resources\PatrolSchedules\Pages\ListPatrolSchedules;
use App\Filament\Resources\PatrolSchedules\Schemas\PatrolScheduleForm;
use App\Filament\Resources\PatrolSchedules\Tables\PatrolSchedulesTable;
use App\Models\PatrolSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatrolScheduleResource extends Resource
{
    protected static ?string $model = PatrolSchedule::class;

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi & Keamanan';

    protected static ?string $navigationLabel = 'Jadwal Ronda';

    protected static ?string $modelLabel = 'Jadwal Ronda';

    protected static ?string $pluralModelLabel = 'Jadwal Ronda';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Schema $schema): Schema
    {
        return PatrolScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatrolSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrolSchedules::route('/'),
            'create' => CreatePatrolSchedule::route('/create'),
            'edit' => EditPatrolSchedule::route('/{record}/edit'),
        ];
    }
}
