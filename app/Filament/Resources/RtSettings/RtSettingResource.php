<?php

namespace App\Filament\Resources\RtSettings;

use UnitEnum;

use App\Filament\Resources\RtSettings\Pages\CreateRtSetting;
use App\Filament\Resources\RtSettings\Pages\EditRtSetting;
use App\Filament\Resources\RtSettings\Pages\ListRtSettings;
use App\Filament\Resources\RtSettings\Schemas\RtSettingForm;
use App\Filament\Resources\RtSettings\Tables\RtSettingsTable;
use App\Models\RtSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RtSettingResource extends Resource
{
    protected static ?string $model = RtSetting::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Profil & Format RT';

    protected static ?string $modelLabel = 'Pengaturan RT';

    protected static ?string $pluralModelLabel = 'Profil & Pengaturan RT';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Schema $schema): Schema
    {
        return RtSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RtSettingsTable::configure($table);
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
            'index' => ListRtSettings::route('/'),
            'create' => CreateRtSetting::route('/create'),
            'edit' => EditRtSetting::route('/{record}/edit'),
        ];
    }
}
