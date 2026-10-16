<?php

namespace App\Filament\Resources\Residents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                Textarea::make('nik_encrypted')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('nik_hash')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->required(),
                DatePicker::make('tanggal_lahir')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->options(['L' => 'L', 'P' => 'P'])
                    ->required(),
                TextInput::make('agama')
                    ->required(),
                TextInput::make('pendidikan_terakhir'),
                TextInput::make('pekerjaan'),
                TextInput::make('status_perkawinan'),
                Select::make('hubungan_keluarga')
                    ->options([
            'kepala_keluarga' => 'Kepala keluarga',
            'istri' => 'Istri',
            'anak' => 'Anak',
            'menantu' => 'Menantu',
            'cucu' => 'Cucu',
            'orang_tua' => 'Orang tua',
            'mertua' => 'Mertua',
            'famili_lain' => 'Famili lain',
            'lainnya' => 'Lainnya',
        ])
                    ->required(),
                Select::make('status_kependudukan')
                    ->options([
            'aktif' => 'Aktif',
            'pindah' => 'Pindah',
            'meninggal' => 'Meninggal',
            'lahir_baru' => 'Lahir baru',
        ])
                    ->default('aktif')
                    ->required(),
                TextInput::make('telepon')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('foto_path'),
                Toggle::make('is_kepala_keluarga')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }
}
