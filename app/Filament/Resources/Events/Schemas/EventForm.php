<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('lokasi'),
                DateTimePicker::make('tanggal_mulai')
                    ->required(),
                DateTimePicker::make('tanggal_selesai'),
                Select::make('kategori')
                    ->options([
            'kerja_bakti' => 'Kerja bakti',
            'arisan' => 'Arisan',
            'rapat' => 'Rapat',
            'pengajian' => 'Pengajian',
            'perayaan' => 'Perayaan',
            'lainnya' => 'Lainnya',
        ])
                    ->default('lainnya')
                    ->required(),
                Toggle::make('is_recurring')
                    ->required(),
                TextInput::make('dibuat_oleh')
                    ->required()
                    ->numeric(),
            ]);
    }
}
