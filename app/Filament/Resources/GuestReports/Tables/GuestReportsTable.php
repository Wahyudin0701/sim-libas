<?php

namespace App\Filament\Resources\GuestReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuestReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('household.id')
                    ->searchable(),
                TextColumn::make('nama_tamu')
                    ->searchable(),
                TextColumn::make('nik_tamu')
                    ->searchable(),
                TextColumn::make('asal')
                    ->searchable(),
                TextColumn::make('tanggal_datang')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_pulang')
                    ->date()
                    ->sortable(),
                TextColumn::make('foto_path')
                    ->searchable(),
                TextColumn::make('dilaporkan_oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
