<?php

namespace App\Filament\Resources\RtSettings;

use App\Filament\Resources\RtSettings\Pages\CreateRtSetting;
use App\Filament\Resources\RtSettings\Pages\EditRtSetting;
use App\Filament\Resources\RtSettings\Pages\ListRtSettings;
use App\Filament\Resources\RtSettings\Schemas\RtSettingForm;
use App\Filament\Resources\RtSettings\Tables\RtSettingsTable;
use App\Models\RtSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RtSettingResource extends Resource
{
    protected static ?string $model = RtSetting::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Pengaturan Sistem';

    protected static ?string $modelLabel = 'Pengaturan Sistem';

    protected static ?string $pluralModelLabel = 'Pengaturan Sistem';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 1;

    /**
     * Menu Pengaturan Sistem hanya boleh diakses dan dilihat oleh Super Admin.
     */
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && $user->hasRole('super_admin'));
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && $user->hasRole('super_admin'));
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && $user->hasRole('super_admin'));
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && $user->hasRole('super_admin'));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

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
