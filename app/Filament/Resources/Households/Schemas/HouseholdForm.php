<?php

namespace App\Filament\Resources\Households\Schemas;

use App\Models\Household;
use Filament\Forms\Components\FileUpload;
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
                TextInput::make('id_kk')
                    ->label('ID Kartu Keluarga')
                    ->helperText('Dihasilkan otomatis oleh sistem (format KK***)')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn (?Household $record) => $record !== null && !empty($record->id_kk))
                    ->columnSpanFull(),

                TextInput::make('no_kk')
                    ->label('Nomor Kartu Keluarga (KK)')
                    ->helperText('16 digit angka resmi dari dokumen Kartu Keluarga')
                    ->required()
                    ->maxLength(16)
                    ->columnSpanFull(),
                Textarea::make('alamat')
                    ->label('Alamat Rumah')
                    ->placeholder('Contoh: Jl. Pinang Masak No. 12 Blok A')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status_hunian')
                    ->label('Status Hunian')
                    ->options([
                        'tetap' => 'Tetap (Milik Sendiri)',
                        'kontrak' => 'Kontrak / Sewa',
                        'kos' => 'Kos',
                    ])
                    ->default('tetap')
                    ->required(),
                FileUpload::make('foto_kk')
                    ->label('Foto Dokumen KK')
                    ->image()
                    ->directory('households/foto_kk')
                    ->disk('public')
                    ->maxSize(5120)
                    ->columnSpanFull(),
                Select::make('user_id')
                    ->label('Akun Login Warga (Opsional)')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
