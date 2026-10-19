<?php

namespace App\Filament\Resources\FeeTypes;

use UnitEnum;

use App\Filament\Resources\FeeTypes\Pages\CreateFeeType;
use App\Filament\Resources\FeeTypes\Pages\EditFeeType;
use App\Filament\Resources\FeeTypes\Pages\ListFeeTypes;
use App\Filament\Resources\FeeTypes\Schemas\FeeTypeForm;
use App\Filament\Resources\FeeTypes\Tables\FeeTypesTable;
use App\Models\FeeType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeeTypeResource extends Resource
{
    protected static ?string $model = FeeType::class;

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan RT';

    protected static ?string $navigationLabel = 'Master Jenis Iuran';

    protected static ?string $modelLabel = 'Jenis Iuran';

    protected static ?string $pluralModelLabel = 'Master Jenis Iuran';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'bendahara'])
            || $user->can('kelola_jenis_iuran')
        ));
    }

    public static function form(Schema $schema): Schema
    {
        return FeeTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeeTypesTable::configure($table);
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
            'index' => ListFeeTypes::route('/'),
            'create' => CreateFeeType::route('/create'),
            'edit' => EditFeeType::route('/{record}/edit'),
        ];
    }
}
