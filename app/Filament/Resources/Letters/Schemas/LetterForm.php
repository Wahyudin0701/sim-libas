<?php

namespace App\Filament\Resources\Letters\Schemas;

use App\Models\LetterType;
use App\Models\Resident;
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
                Select::make('resident_id')
                    ->label('Warga Pemohon')
                    ->relationship('resident', 'nama')
                    ->getOptionLabelFromRecordUsing(fn (Resident $record) => "{$record->nama} (NIK: {$record->masked_nik}) [{$record->id_warga}]")
                    ->searchable(['nama', 'id_warga'])
                    ->preload()
                    ->required(),

                Select::make('letter_type_id')
                    ->label('Jenis Surat')
                    ->relationship('letterType', 'nama')
                    ->getOptionLabelFromRecordUsing(fn (LetterType $record) => "[{$record->kode}] {$record->nama}")
                    ->searchable(['nama', 'kode'])
                    ->preload()
                    ->required(),

                Textarea::make('keperluan')
                    ->label('Keperluan')
                    ->placeholder('Jelaskan tujuan atau peruntukan surat pengantar ini...')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('nomor_surat')
                    ->label('Nomor Surat Resmi')
                    ->placeholder('Contoh: 001/SP-SKCK/RT.15/X/2026')
                    ->helperText('Diterbitkan otomatis saat disetujui, atau dapat diisi manual.'),

                Select::make('status')
                    ->label('Status Permohonan')
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
                    ->label('Catatan Pengurus RT')
                    ->placeholder('Catatan verifikasi pengurus...')
                    ->columnSpanFull(),

                Textarea::make('alasan_tolak')
                    ->label('Alasan Penolakan (Bila Ditolak)')
                    ->placeholder('Alasan mengapa permohonan ditolak...')
                    ->columnSpanFull(),

                DateTimePicker::make('tanggal_disetujui')
                    ->label('Tanggal Disetujui'),
            ]);
    }
}
