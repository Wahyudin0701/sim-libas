<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WasteDepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                Select::make('waste_type_id')
                    ->relationship('wasteType', 'id')
                    ->required(),
                TextInput::make('berat_kg')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_saat_setor')
                    ->required()
                    ->numeric(),
                TextInput::make('nilai')
                    ->required()
                    ->numeric(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                TextInput::make('dicatat_oleh')
                    ->required()
                    ->numeric(),
            ]);
    }
}
