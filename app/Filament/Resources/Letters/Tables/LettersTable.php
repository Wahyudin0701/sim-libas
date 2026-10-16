<?php

namespace App\Filament\Resources\Letters\Tables;

use App\Models\Letter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification as FilamentToast;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable()
                    ->default('(Belum Terbit)')
                    ->badge()
                    ->color(fn (?string $state): string => $state ? 'success' : 'gray'),

                TextColumn::make('letterType.nama')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('resident.nama')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Letter $record): string => 'NIK: ' . ($record->resident?->masked_nik ?? '-')),

                TextColumn::make('keperluan')
                    ->label('Keperluan')
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diajukan' => 'warning',
                        'diperiksa' => 'info',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Diajukan Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'diperiksa' => 'Diperiksa',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),
                SelectFilter::make('letter_type_id')
                    ->label('Jenis Surat')
                    ->relationship('letterType', 'nama'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Surat Pengantar')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui surat pengantar ini? Nomor surat resmi akan diterbitkan secara otomatis.')
                    ->visible(fn (Letter $record): bool => in_array($record->status, ['diajukan', 'diperiksa']))
                    ->action(function (Letter $record): void {
                        $count = Letter::whereYear('created_at', date('Y'))->count() + 1;
                        $noFormat = str_pad($count, 3, '0', STR_PAD_LEFT);
                        $bulanRomawi = match ((int) date('n')) {
                            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
                        };

                        $record->update([
                            'status' => 'disetujui',
                            'nomor_surat' => "{$noFormat}/RT.15/PMS/{$bulanRomawi}/" . date('Y'),
                            'disetujui_oleh' => auth()->id(),
                            'tanggal_disetujui' => now(),
                        ]);

                        FilamentToast::make()
                            ->title('Surat Berhasil Disetujui')
                            ->body("Nomor Surat: {$record->nomor_surat}")
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('alasan_tolak')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Jelaskan kekurangan dokumen atau alasan penolakan...'),
                    ])
                    ->visible(fn (Letter $record): bool => in_array($record->status, ['diajukan', 'diperiksa']))
                    ->action(function (Letter $record, array $data): void {
                        $record->update([
                            'status' => 'ditolak',
                            'alasan_tolak' => $data['alasan_tolak'],
                            'disetujui_oleh' => auth()->id(),
                        ]);

                        FilamentToast::make()
                            ->title('Permohonan Surat Ditolak')
                            ->body('Catatan penolakan telah dikirim ke pemohon.')
                            ->warning()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
