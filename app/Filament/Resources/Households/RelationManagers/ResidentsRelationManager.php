<?php

namespace App\Filament\Resources\Households\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResidentsRelationManager extends RelationManager
{
    protected static string $relationship = 'residents';

    protected static ?string $title = 'Daftar Anggota Keluarga';

    protected static ?string $modelLabel = 'Anggota Keluarga';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(100),

                TextInput::make('nik')
                    ->label('NIK (16 Digit)')
                    ->required()
                    ->maxLength(16),

                Select::make('hubungan_keluarga')
                    ->label('Hubungan Keluarga')
                    ->options([
                        'kepala_keluarga' => 'Kepala Keluarga',
                        'istri' => 'Istri',
                        'anak' => 'Anak',
                        'orang_tua' => 'Orang Tua',
                        'mertua' => 'Mertua',
                        'menantu' => 'Menantu',
                        'cucu' => 'Cucu',
                        'famili_lain' => 'Famili Lain',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->default('Jambi')
                    ->maxLength(50),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir'),

                TextInput::make('telepon')
                    ->label('No. Telepon / WhatsApp')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->maxLength(100),

                Toggle::make('is_kepala_keluarga')
                    ->label('Tandai sebagai Kepala Keluarga')
                    ->default(false),

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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('masked_nik')
                    ->label('NIK')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('hubungan_keluarga')
                    ->label('Hubungan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'warning'),

                IconColumn::make('is_kepala_keluarga')
                    ->label('Kepala KK')
                    ->boolean(),

                TextColumn::make('telepon')
                    ->label('Telepon / WA')
                    ->default('-'),

                TextColumn::make('status_kependudukan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pindah' => 'warning',
                        'meninggal' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Anggota Keluarga'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
