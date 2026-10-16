<?php

namespace App\Filament\Resources\RtSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RtSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_rt')
                    ->required()
                    ->default('RT 15'),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('kelurahan')
                    ->required()
                    ->default('Pematang Sulur'),
                TextInput::make('kecamatan')
                    ->required()
                    ->default('Telanaipura'),
                TextInput::make('kota')
                    ->required()
                    ->default('Kota Jambi'),
                TextInput::make('provinsi')
                    ->required()
                    ->default('Jambi'),
                TextInput::make('kode_pos')
                    ->default('36129'),
                TextInput::make('telepon')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('nama_ketua_rt')
                    ->required(),
                TextInput::make('tanda_tangan_path'),
                TextInput::make('stempel_path'),
                TextInput::make('format_nomor_surat'),
            ]);
    }
}
