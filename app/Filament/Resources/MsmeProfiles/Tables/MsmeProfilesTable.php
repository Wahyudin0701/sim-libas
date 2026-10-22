<?php

namespace App\Filament\Resources\MsmeProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MsmeProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_path')
                    ->label('Foto')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('favicon.png')),
                TextColumn::make('nama_usaha')
                    ->label('Nama Usaha')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),
                TextColumn::make('household.headOfHousehold.nama')
                    ->label('Pemilik (KK)')
                    ->searchable(),
                TextColumn::make('telepon')
                    ->label('Telepon / HP')
                    ->searchable(),
                TextColumn::make('status_moderasi')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'disetujui',
                        'warning' => 'pending',
                        'danger' => 'ditolak',
                    ]),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
