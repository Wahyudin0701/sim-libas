<?php

namespace App\Filament\Resources\PatrolSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PatrolScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hari_ke')
                    ->label('Hari Ronda')
                    ->options([
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu',
                    ])
                    ->required(),
                TextInput::make('regu')
                    ->label('Nama Regu Ronda')
                    ->required(),
                TimePicker::make('waktu_mulai')
                    ->required(),
                TimePicker::make('waktu_selesai')
                    ->required(),
                TextInput::make('pos_lokasi')
                    ->label('Pos Lokasi')
                    ->default('Pos Utama RT 15'),
                Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }
}
