<?php

namespace App\Filament\Resources\WasteDeposits;

use UnitEnum;

use App\Filament\Resources\WasteDeposits\Pages\CreateWasteDeposit;
use App\Filament\Resources\WasteDeposits\Pages\EditWasteDeposit;
use App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits;
use App\Filament\Resources\WasteDeposits\Schemas\WasteDepositForm;
use App\Filament\Resources\WasteDeposits\Tables\WasteDepositsTable;
use App\Models\WasteDeposit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WasteDepositResource extends Resource
{
    protected static ?string $model = WasteDeposit::class;

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Kampung Digital';

    protected static ?string $navigationLabel = 'Setoran Bank Sampah';

    protected static ?string $modelLabel = 'Setoran Sampah';

    protected static ?string $pluralModelLabel = 'Bank Sampah Pinang Masak';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'koordinator_bank_sampah', 'rw_kelurahan'])
            || $user->can('catat_setoran_sampah')
            || $user->can('lihat_dampak_lingkungan')
        ));
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'koordinator_bank_sampah'])
            || $user->can('catat_setoran_sampah')
        ));
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'koordinator_bank_sampah'])
            || $user->can('catat_setoran_sampah')
        ));
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'koordinator_bank_sampah'])
            || $user->can('catat_setoran_sampah')
        ));
    }

    public static function form(Schema $schema): Schema
    {
        return WasteDepositForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WasteDepositsTable::configure($table);
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
            'index' => ListWasteDeposits::route('/'),
            'create' => CreateWasteDeposit::route('/create'),
            'edit' => EditWasteDeposit::route('/{record}/edit'),
        ];
    }
}
