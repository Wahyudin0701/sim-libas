<?php

namespace App\Filament\Resources\Residents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_warga')
                    ->label('ID Warga')
                    ->helperText('Dihasilkan otomatis oleh sistem (format W***)')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn (?\App\Models\Resident $record) => $record !== null && !empty($record->id_warga)),

                Select::make('kategori_warga')
                    ->label('Kategori Warga')
                    ->options([
                        'memiliki_kk' => 'Memiliki KK di RT 15',
                        'tanpa_kk' => 'Tanpa KK di RT 15 (Kontrak / Kos / Pendatang)',
                    ])
                    ->default('memiliki_kk')
                    ->required(),

                Select::make('household_id')
                    ->label('Kartu Keluarga (KK)')
                    ->relationship('household', 'no_kk')
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\Household $record) => ($record->id_kk ? "[{$record->id_kk}] " : '') . "{$record->no_kk} (" . ($record->kepalaKeluarga?->nama ?? $record->alamat) . ')')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('status_tempat_tinggal')
                    ->label('Status Tempat Tinggal')
                    ->options([
                        'kontrak' => 'Mengontrak Rumah',
                        'kos' => 'Ngekos / Sewa Kamar',
                        'pendatang' => 'Pendatang / Tinggal Sementara',
                        'lainnya' => 'Lainnya',
                    ])
                    ->nullable(),

                TextInput::make('alamat_domisili')
                    ->label('Alamat Domisili di RT 15')
                    ->nullable(),

                TextInput::make('nik')
                    ->label('NIK (16 Digit)')
                    ->nullable()
                    ->length(16)
                    ->numeric()
                    ->helperText('Kosongkan jika NIK fisik belum terverifikasi dari KK asli'),

                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(100),

                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->default('Jambi'),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('agama')
                    ->label('Agama')
                    ->default('Islam'),

                TextInput::make('pendidikan_terakhir')
                    ->label('Pendidikan Terakhir'),

                TextInput::make('pekerjaan')
                    ->label('Pekerjaan'),

                Select::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->options([
                        'Belum Kawin' => 'Belum Kawin',
                        'Kawin' => 'Kawin',
                        'Cerai Hidup' => 'Cerai Hidup',
                        'Cerai Mati' => 'Cerai Mati',
                    ]),

                Select::make('hubungan_keluarga')
                    ->label('Hubungan Keluarga')
                    ->options([
                        'kepala_keluarga' => 'Kepala Keluarga',
                        'istri' => 'Istri',
                        'anak' => 'Anak',
                        'menantu' => 'Menantu',
                        'cucu' => 'Cucu',
                        'orang_tua' => 'Orang Tua',
                        'mertua' => 'Mertua',
                        'famili_lain' => 'Famili Lain',
                    ])
                    ->required(),

                Select::make('status_kependudukan')
                    ->label('Status Kependudukan')
                    ->options([
                        'aktif' => 'Aktif',
                        'pindah' => 'Pindah',
                        'meninggal' => 'Meninggal',
                        'lahir_baru' => 'Lahir Baru',
                    ])
                    ->default('aktif')
                    ->required(),

                TextInput::make('telepon')
                    ->label('Nomor Telepon / WhatsApp')
                    ->tel(),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),

                Toggle::make('is_kepala_keluarga')
                    ->label('Kepala Keluarga'),
            ]);
    }
}
