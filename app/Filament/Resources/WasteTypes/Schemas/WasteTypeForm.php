<?php

namespace App\Filament\Resources\WasteTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WasteTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Select::make('kategori')
                    ->options(['organik' => 'Organik', 'anorganik' => 'Anorganik', 'b3' => 'B3', 'lainnya' => 'Lainnya'])
                    ->default('anorganik')
                    ->required(),
                TextInput::make('harga_per_kg')
                    ->required()
                    ->numeric(),
                TextInput::make('satuan')
                    ->required()
                    ->default('kg'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
