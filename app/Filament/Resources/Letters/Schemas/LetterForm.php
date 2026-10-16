<?php

namespace App\Filament\Resources\Letters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LetterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nomor_surat'),
                Select::make('letter_type_id')
                    ->relationship('letterType', 'id')
                    ->required(),
                Select::make('resident_id')
                    ->relationship('resident', 'id')
                    ->required(),
                Textarea::make('keperluan')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('data_tambahan'),
                Select::make('status')
                    ->options([
            'diajukan' => 'Diajukan',
            'diperiksa' => 'Diperiksa',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
        ])
                    ->default('diajukan')
                    ->required(),
                Textarea::make('catatan_pengurus')
                    ->columnSpanFull(),
                Textarea::make('alasan_tolak')
                    ->columnSpanFull(),
                TextInput::make('pdf_path'),
                TextInput::make('diverifikasi_oleh')
                    ->numeric(),
                TextInput::make('disetujui_oleh')
                    ->numeric(),
                DateTimePicker::make('tanggal_disetujui'),
            ]);
    }
}
