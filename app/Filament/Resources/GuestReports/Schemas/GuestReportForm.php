<?php

namespace App\Filament\Resources\GuestReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GuestReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                TextInput::make('nama_tamu')
                    ->required(),
                TextInput::make('nik_tamu'),
                TextInput::make('asal')
                    ->required(),
                Textarea::make('tujuan')
                    ->columnSpanFull(),
                DatePicker::make('tanggal_datang')
                    ->required(),
                DatePicker::make('tanggal_pulang'),
                TextInput::make('foto_path'),
                TextInput::make('dilaporkan_oleh')
                    ->required()
                    ->numeric(),
            ]);
    }
}
