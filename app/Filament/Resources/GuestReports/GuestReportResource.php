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

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi & Keamanan';

    protected static ?string $navigationLabel = 'Lapor Tamu';

    protected static ?string $modelLabel = 'Laporan Tamu';

    protected static ?string $pluralModelLabel = 'Lapor Tamu 1x24 Jam';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

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
