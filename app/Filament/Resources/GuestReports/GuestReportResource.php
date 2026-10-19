<?php

namespace App\Filament\Resources\GuestReports;

use UnitEnum;

use App\Filament\Resources\GuestReports\Pages\CreateGuestReport;
use App\Filament\Resources\GuestReports\Pages\EditGuestReport;
use App\Filament\Resources\GuestReports\Pages\ListGuestReports;
use App\Filament\Resources\GuestReports\Schemas\GuestReportForm;
use App\Filament\Resources\GuestReports\Tables\GuestReportsTable;
use App\Models\GuestReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuestReportResource extends Resource
{
    protected static ?string $model = GuestReport::class;

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Keamanan Lingkungan';

    protected static ?string $navigationLabel = 'Lapor Tamu';

    protected static ?string $modelLabel = 'Laporan Tamu';

    protected static ?string $pluralModelLabel = 'Lapor Tamu 1x24 Jam';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && (
            $user->hasAnyRole(['super_admin', 'admin', 'koordinator_keamanan'])
            || $user->can('lihat_laporan_tamu')
        ));
    }

    public static function form(Schema $schema): Schema
    {
        return GuestReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuestReportsTable::configure($table);
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
            'index' => ListGuestReports::route('/'),
            'create' => CreateGuestReport::route('/create'),
            'edit' => EditGuestReport::route('/{record}/edit'),
        ];
    }
}
