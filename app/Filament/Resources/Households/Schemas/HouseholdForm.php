<?php

namespace App\Filament\Resources\Households\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HouseholdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no_kk')
                    ->required(),
                TextInput::make('no_kk_hash')
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rt_rw')
                    ->required()
                    ->default('015/000'),
                TextInput::make('kelurahan')
                    ->required()
                    ->default('Pematang Sulur'),
                TextInput::make('kecamatan')
                    ->required()
                    ->default('Telanaipura'),
                Select::make('status_hunian')
                    ->options(['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'kos' => 'Kos'])
                    ->default('tetap')
                    ->required(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }
}
