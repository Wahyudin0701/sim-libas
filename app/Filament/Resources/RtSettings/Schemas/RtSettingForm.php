<?php

namespace App\Filament\Resources\RtSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RtSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Wilayah RT')
                    ->description('Konfigurasi nama RT, pimpinan, dan domisili wilayah.')
                    ->icon('heroicon-o-home')
                    ->columns(2)
                    ->components([
                        TextInput::make('nama_rt')
                            ->label('Nama RT')
                            ->required()
                            ->default('RT 15'),
                        TextInput::make('nama_ketua_rt')
                            ->label('Nama Ketua RT')
                            ->required()
                            ->default('Bpk. H. Ahmad Sudirman, S.E.'),
                        Textarea::make('alamat')
                            ->label('Alamat Sekretariat / Domisili')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('kelurahan')
                            ->label('Kelurahan')
                            ->required()
                            ->default('Pematang Sulur'),
                        TextInput::make('kecamatan')
                            ->label('Kecamatan')
                            ->required()
                            ->default('Telanaipura'),
                        TextInput::make('kota')
                            ->label('Kota / Kabupaten')
                            ->required()
                            ->default('Kota Jambi'),
                        TextInput::make('provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->default('Jambi'),
                        TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->default('36129'),
                    ]),

                Section::make('Kontak Resmi RT')
                    ->description('Saluran komunikasi resmi pengurus RT 15.')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->components([
                        TextInput::make('telepon')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel()
                            ->placeholder('0812-xxxx-xxxx'),
                        TextInput::make('email')
                            ->label('Alamat Email Resmi')
                            ->email()
                            ->placeholder('rt15pematangsulur@gmail.com'),
                    ]),

                Section::make('Format Surat & Administrasi Digital')
                    ->description('Format penomoran surat pengantar dan aset tanda tangan / stempel digital.')
                    ->icon('heroicon-o-document-duplicate')
                    ->columns(2)
                    ->components([
                        TextInput::make('format_nomor_surat')
                            ->label('Format Nomor Surat')
                            ->default('{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}')
                            ->helperText('Variabel yang didukung: {NO}, {BULAN_ROMAWI}, {TAHUN}')
                            ->columnSpanFull(),
                        FileUpload::make('tanda_tangan_path')
                            ->label('Tanda Tangan Digital Ketua RT')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->helperText('Format PNG transparan dianjurkan. Maks 2MB.'),
                        FileUpload::make('stempel_path')
                            ->label('Stempel Resmi RT 15')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->helperText('Format PNG transparan dianjurkan. Maks 2MB.'),
                    ]),
            ]);
    }
}
