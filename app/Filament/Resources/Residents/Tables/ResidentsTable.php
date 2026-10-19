<?php

namespace App\Filament\Resources\Residents\Tables;

use App\Models\Resident;
use Filament\Actions\Action;
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
                TextColumn::make('id_warga')
                    ->label('ID Warga')
                    ->badge()
                    ->color('info')
                    ->weight('bold')
                    ->fontFamily(\Filament\Support\Enums\FontFamily::Mono)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('household.id_kk')
                    ->label('ID KK')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-chevron-right')
                    ->iconPosition(\Filament\Support\Enums\IconPosition::After)
                    ->url(fn (Resident $record): ?string => $record->household_id ? url('/admin/households/' . $record->household_id) : null)
                    ->fontFamily(\Filament\Support\Enums\FontFamily::Mono)
                    ->sortable()
                    ->searchable(),

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
                    ->searchable()
                    ->url(fn (Resident $record): ?string => $record->wa_url)
                    ->openUrlInNewTab()
                    ->color(fn (?string $state): ?string => $state ? 'success' : null)
                    ->icon(fn (?string $state): ?string => $state ? 'heroicon-m-chat-bubble-left-ellipsis' : null)
                    ->tooltip(fn (Resident $record): ?string => $record->telepon ? "Kirim pesan WhatsApp ke {$record->nama}" : null),

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
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->tooltip('Lihat Detail Warga')
                    ->modalHeading(fn (Resident $record): string => "Detail Warga: {$record->nama}")
                    ->modalContent(fn (Resident $record) => view('filament.resources.residents.components.detail-modal', [
                        'resident' => $record->loadMissing('household'),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->extraModalFooterActions(fn (Resident $record): array => $record->household_id ? [
                        Action::make('buka_kk')
                            ->label('Buka Kartu Keluarga')
                            ->icon('heroicon-o-home')
                            ->color('primary')
                            ->url(url('/admin/households/' . $record->household_id)),
                    ] : []),
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
