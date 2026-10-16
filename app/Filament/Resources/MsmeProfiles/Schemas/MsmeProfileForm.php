<?php

namespace App\Filament\Resources\MsmeProfiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MsmeProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                TextInput::make('nama_usaha')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Textarea::make('produk')
                    ->columnSpanFull(),
                TextInput::make('kategori'),
                TextInput::make('foto_path'),
                TextInput::make('alamat'),
                TextInput::make('telepon')
                    ->tel()
                    ->required(),
                TextInput::make('whatsapp'),
                Select::make('status_moderasi')
                    ->options(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'])
                    ->default('pending')
                    ->required(),
                Textarea::make('catatan_moderasi')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
