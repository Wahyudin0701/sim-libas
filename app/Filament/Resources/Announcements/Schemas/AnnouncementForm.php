<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                Textarea::make('isi')
                    ->required()
                    ->columnSpanFull(),
                Select::make('target')
                    ->options(['semua' => 'Semua', 'pengurus' => 'Pengurus', 'warga' => 'Warga'])
                    ->default('semua')
                    ->required(),
                TextInput::make('lampiran_path'),
                Toggle::make('is_pinned')
                    ->required(),
                Toggle::make('is_published')
                    ->required(),
                DateTimePicker::make('tanggal_tayang'),
                DateTimePicker::make('tanggal_berakhir'),
                TextInput::make('dibuat_oleh')
                    ->required()
                    ->numeric(),
            ]);
    }
}
