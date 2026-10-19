<?php

namespace App\Filament\Resources\LetterTypes\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LetterTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Jenis Surat')
                    ->placeholder('Contoh: Surat Pengantar SKCK')
                    ->required(),

                TextInput::make('kode')
                    ->label('Kode Singkat')
                    ->placeholder('Contoh: SP-SKCK')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi / Peruntukan')
                    ->placeholder('Keterangan singkat mengenai peruntukan surat pengantar ini...')
                    ->columnSpanFull(),

                TextInput::make('format_nomor')
                    ->label('Format Penomoran Resmi')
                    ->placeholder('{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}')
                    ->helperText('Variabel yang didukung: {NO}, {ROMAN_MONTH}, {YEAR}')
                    ->default('{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}')
                    ->required(),

                TagsInput::make('persyaratan')
                    ->label('Persyaratan Dokumen Warga')
                    ->placeholder('Ketik persyaratan lalu tekan Enter')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),

                TextInput::make('urutan')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
