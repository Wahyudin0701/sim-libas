<?php

namespace App\Filament\Resources\WasteDeposits\Tables;

use App\Models\WasteDeposit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WasteDepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Setor')
                    ->date('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('household.kepalaKeluarga.nama')
                    ->label('Warga Penyetor')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (WasteDeposit $record): string => $record->household?->alamat ?? '-'),

                TextColumn::make('wasteType.nama')
                    ->label('Jenis Sampah')
                    ->badge()
                    ->color('success'),

                TextColumn::make('berat_kg')
                    ->label('Berat')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.') . ' kg')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('harga_saat_setor')
                    ->label('Harga / Kg')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('nilai')
                    ->label('Total Nilai')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('pencatat.name')
                    ->label('Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('waste_type_id')
                    ->label('Jenis Sampah')
                    ->relationship('wasteType', 'nama'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
