<?php

namespace App\Filament\Resources\LetterTypes;

use UnitEnum;

use App\Filament\Resources\LetterTypes\Pages\CreateLetterType;
use App\Filament\Resources\LetterTypes\Pages\EditLetterType;
use App\Filament\Resources\LetterTypes\Pages\ListLetterTypes;
use App\Filament\Resources\LetterTypes\Schemas\LetterTypeForm;
use App\Filament\Resources\LetterTypes\Tables\LetterTypesTable;
use App\Models\LetterType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LetterTypeResource extends Resource
{
    protected static ?string $model = LetterType::class;

    protected static string|UnitEnum|null $navigationGroup = 'Layanan Administrasi';

    protected static ?string $navigationLabel = 'Master Jenis Surat';

    protected static ?string $modelLabel = 'Jenis Surat';

    protected static ?string $pluralModelLabel = 'Master Jenis Surat';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Schema $schema): Schema
    {
        return LetterTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterTypesTable::configure($table);
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
            'index' => ListLetterTypes::route('/'),
            'create' => CreateLetterType::route('/create'),
            'edit' => EditLetterType::route('/{record}/edit'),
        ];
    }
}
