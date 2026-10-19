<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\CashTransaction;
use App\Models\Payment;
use App\Services\NotificationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification as FilamentToast;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.household.kepalaKeluarga.nama')
                    ->label('Warga / KK')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Payment $record): string => $record->invoice?->household?->alamat ?? '-'),

                TextColumn::make('invoice.feeType.nama')
                    ->label('Jenis Iuran')
                    ->badge()
                    ->color('info'),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),

                TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer' => 'primary',
                        'tunai' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                ImageColumn::make('bukti_path')
                    ->label('Bukti Transfer')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-receipt.png')),

                TextColumn::make('tanggal_bayar')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('validator.name')
                    ->label('Divalidasi Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Valid (Lunas)',
                        'ditolak' => 'Ditolak',
                    ]),
                SelectFilter::make('metode')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('validasi')
                    ->label('Validasi Lunas')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Validasi Pembayaran')
                    ->modalDescription('Apakah pembayaran ini sudah diverifikasi dan masuk kas RT? Tagihan warga akan otomatis berstatus Lunas dan dicatat di Buku Kas.')
                    ->visible(fn (Payment $record): bool => $record->status === 'pending')
                    ->action(function (Payment $record): void {
                        $record->update([
                            'status' => 'valid',
                            'divalidasi_oleh' => auth()->id(),
                            'tanggal_validasi' => now(),
                        ]);

                        // Update invoice
                        $record->invoice?->update([
                            'status' => 'lunas',
                        ]);

                        // Catat otomatis ke buku kas RT
                        CashTransaction::create([
                            'jenis' => 'masuk',
                            'kategori' => 'Iuran Warga',
                            'deskripsi' => 'Pembayaran ' . ($record->invoice?->feeType?->nama ?? 'Iuran') . ' oleh ' . ($record->invoice?->household?->kepalaKeluarga?->nama ?? 'Warga'),
                            'jumlah' => $record->jumlah,
                            'tanggal' => now(),
                            'bukti_path' => $record->bukti_path,
                            'dicatat_oleh' => auth()->id(),
                        ]);

                        // Kirim notifikasi sistem ke keluarga bersangkutan
                        NotificationService::sendToHousehold(
                            $record->invoice?->household,
                            'Pembayaran Iuran Divalidasi',
                            'Pembayaran ' . ($record->invoice?->feeType?->nama ?? 'Iuran RT') . ' sebesar Rp ' . number_format($record->jumlah, 0, ',', '.') . ' telah divalidasi dan lunas oleh Bendahara RT.',
                            route('warga.iuran')
                        );

                        FilamentToast::make()
                            ->title('Pembayaran Berhasil Divalidasi')
                            ->body('Status tagihan menjadi Lunas dan tercatat di Kas RT.')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak Bukti')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Jelaskan bukti tidak terbaca / dana belum masuk rekening RT...'),
                    ])
                    ->visible(fn (Payment $record): bool => $record->status === 'pending')
                    ->action(function (Payment $record, array $data): void {
                        $record->update([
                            'status' => 'ditolak',
                            'catatan' => $data['catatan'],
                            'divalidasi_oleh' => auth()->id(),
                            'tanggal_validasi' => now(),
                        ]);

                        // Kirim notifikasi sistem ke keluarga bersangkutan
                        NotificationService::sendToHousehold(
                            $record->invoice?->household,
                            'Bukti Pembayaran Iuran Ditolak',
                            'Bukti pembayaran ' . ($record->invoice?->feeType?->nama ?? 'Iuran RT') . ' ditolak oleh Bendahara RT. Catatan: ' . $data['catatan'],
                            route('warga.iuran')
                        );

                        FilamentToast::make()
                            ->title('Bukti Pembayaran Ditolak')
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
