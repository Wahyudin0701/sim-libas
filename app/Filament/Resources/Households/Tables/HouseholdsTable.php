<?php

namespace App\Filament\Resources\Households\Tables;

use App\Models\Household;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HouseholdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kk')
                    ->label('ID KK')
                    ->badge()
                    ->color('info')
                    ->weight('bold')
                    ->fontFamily(FontFamily::Mono)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kepalaKeluarga.nama')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-s-user-circle')
                    ->iconColor('primary')
                    ->description(fn (Household $record): ?string => $record->kepalaKeluarga?->telepon ? 'Telp: ' . $record->kepalaKeluarga->telepon : ($record->rt_rw ? 'RT/RW ' . $record->rt_rw : 'RT 15'))
                    ->default('- Belum Ditentukan -'),

                TextColumn::make('no_kk')
                    ->label('Nomor KK')
                    ->fontFamily(FontFamily::Mono)
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state || strlen($state) < 8) return $state ?? '-';
                        return substr($state, 0, 4) . ' •••• •••• ' . substr($state, -4);
                    })
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Nomor KK disalin ke clipboard'),

                TextColumn::make('alamat')
                    ->label('Alamat Rumah')
                    ->searchable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('gray')
                    ->limit(40),

                TextColumn::make('residents_count')
                    ->label('Anggota')
                    ->counts('residents')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-users')
                    ->formatStateUsing(fn ($state): string => "{$state} Jiwa")
                    ->url(fn (Household $record): string => \App\Filament\Resources\Households\HouseholdResource::getUrl('view', ['record' => $record])),

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
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Data Kartu Keluarga')
            ->emptyStateDescription('Mulai tambahkan data KK baru untuk mencatat kepala keluarga dan anggotanya di RT 15.')
            ->emptyStateIcon('heroicon-o-home')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-m-pencil-square'),
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
