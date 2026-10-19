<?php

namespace App\Filament\Resources\MsmeProfiles;

use UnitEnum;

use App\Filament\Resources\MsmeProfiles\Pages\CreateMsmeProfile;
use App\Filament\Resources\MsmeProfiles\Pages\EditMsmeProfile;
use App\Filament\Resources\MsmeProfiles\Pages\ListMsmeProfiles;
use App\Filament\Resources\MsmeProfiles\Schemas\MsmeProfileForm;
use App\Filament\Resources\MsmeProfiles\Tables\MsmeProfilesTable;
use App\Models\MsmeProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MsmeProfileResource extends Resource
{
    protected static ?string $model = MsmeProfile::class;

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Kampung Digital';

    protected static ?string $navigationLabel = 'Katalog UMKM Warga';

    protected static ?string $modelLabel = 'UMKM Warga';

    protected static ?string $pluralModelLabel = 'Katalog UMKM Warga';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'sekretaris'])
            || $user->can('moderasi_umkm')
        ));
    }

    public static function form(Schema $schema): Schema
    {
        return MsmeProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MsmeProfilesTable::configure($table);
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
            'index' => ListMsmeProfiles::route('/'),
            'create' => CreateMsmeProfile::route('/create'),
            'edit' => EditMsmeProfile::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
