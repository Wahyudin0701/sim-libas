<?php

namespace App\Filament\Resources\FeeTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeeTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                Select::make('periode')
                    ->options(['bulanan' => 'Bulanan', 'tahunan' => 'Tahunan', 'insidental' => 'Insidental'])
                    ->default('bulanan')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
