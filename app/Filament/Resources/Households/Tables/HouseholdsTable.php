<?php

namespace App\Filament\Resources\Households\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HouseholdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kepalaKeluarga.nama')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->default('- Belum Diatur -'),

                TextColumn::make('no_kk')
                    ->label('Nomor KK')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state || strlen($state) < 8) return $state ?? '-';
                        return substr($state, 0, 4) . '********' . substr($state, -4);
                    })
                    ->badge()
                    ->color('gray'),

                TextColumn::make('alamat')
                    ->label('Alamat Rumah')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('status_hunian')
                    ->label('Status Hunian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tetap' => 'success',
                        'kontrak' => 'warning',
                        'kos' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('residents_count')
                    ->label('Anggota')
                    ->counts('residents')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('user.name')
                    ->label('Akun Portal')
                    ->icon('heroicon-o-user')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_hunian')
                    ->label('Status Hunian')
                    ->options([
                        'tetap' => 'Warga Tetap',
                        'kontrak' => 'Kontrak / Sewa',
                        'kos' => 'Kos-kosan',
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
