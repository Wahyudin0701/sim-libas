<?php

namespace App\Filament\Resources\Complaints\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('kategori')
                    ->options([
            'infrastruktur' => 'Infrastruktur',
            'kebersihan' => 'Kebersihan',
            'keamanan' => 'Keamanan',
            'kebisingan' => 'Kebisingan',
            'lainnya' => 'Lainnya',
        ])
                    ->default('lainnya')
                    ->required(),
                TextInput::make('judul')
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('foto_path'),
                TextInput::make('lokasi'),
                Toggle::make('is_anonim')
                    ->required(),
                Select::make('status')
                    ->options([
            'diterima' => 'Diterima',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ])
                    ->default('diterima')
                    ->required(),
                Textarea::make('catatan_penanganan')
                    ->columnSpanFull(),
                Select::make('penanggung_jawab_id')
                    ->relationship('penanggungJawab', 'name'),
                DateTimePicker::make('tanggal_selesai'),
            ]);
    }
}
