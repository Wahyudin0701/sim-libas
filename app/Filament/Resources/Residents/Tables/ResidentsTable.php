<?php

namespace App\Filament\Resources\Residents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ResidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('masked_nik')
                    ->label('NIK')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('household.alamat')
                    ->label('Alamat KK')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('hubungan_keluarga')
                    ->label('Hubungan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                IconColumn::make('is_kepala_keluarga')
                    ->label('Kepala KK')
                    ->boolean(),

                TextColumn::make('status_kependudukan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pindah' => 'warning',
                        'meninggal' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('telepon')
                    ->label('No. Telepon / WA')
                    ->searchable(),

                TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_kependudukan')
                    ->label('Status Kependudukan')
                    ->options([
                        'aktif' => 'Aktif',
                        'pindah' => 'Pindah',
                        'meninggal' => 'Meninggal',
                        'lahir_baru' => 'Lahir Baru',
                    ]),
                SelectFilter::make('hubungan_keluarga')
                    ->label('Hubungan Keluarga')
                    ->options([
                        'kepala_keluarga' => 'Kepala Keluarga',
                        'istri' => 'Istri',
                        'anak' => 'Anak',
                        'orang_tua' => 'Orang Tua',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
