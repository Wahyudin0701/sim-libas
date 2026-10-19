<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Models\Complaint;
use App\Services\NotificationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification as FilamentToast;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Pengaduan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'infrastruktur' => 'primary',
                        'kebersihan' => 'success',
                        'keamanan' => 'danger',
                        'kebisingan' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('pelapor.name')
                    ->label('Pelapor')
                    ->formatStateUsing(function (string $state, Complaint $record): string {
                        return $record->is_anonim ? '🔒 Warga (Anonim)' : $state;
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('penanggungJawab.name')
                    ->label('PIC Penanganan')
                    ->default('- Belum Ditugaskan -'),

                TextColumn::make('created_at')
                    ->label('Waktu Lapor')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'diterima' => 'Diterima',
                        'diproses' => 'Sedang Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ]),
                SelectFilter::make('kategori')
                    ->options([
                        'infrastruktur' => 'Infrastruktur / Jalan / Lampu',
                        'kebersihan' => 'Kebersihan & Sampah',
                        'keamanan' => 'Keamanan & Ketertiban',
                        'kebisingan' => 'Kebisingan / Gangguan Lingkungan',
                        'lainnya' => 'Lainnya',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('proses')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (Complaint $record): bool => $record->status === 'diterima')
                    ->action(function (Complaint $record): void {
                        $record->update([
                            'status' => 'diproses',
                            'penanggung_jawab_id' => auth()->id(),
                        ]);

                        // Kirim notifikasi sistem ke warga pelapor
                        NotificationService::sendToUser(
                            $record->user_id,
                            'Pengaduan Sedang Diproses',
                            "Laporan Anda mengenai '{$record->judul}' sedang ditindaklanjuti oleh pengurus RT.",
                            route('warga.pengaduan')
                        );

                        FilamentToast::make()
                            ->title('Pengaduan Sedang Diproses')
                            ->info()
                            ->send();
                    }),
                Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Textarea::make('catatan_penanganan')
                            ->label('Catatan Penyelesaian Masalah')
                            ->required()
                            ->placeholder('Jelaskan tindakan yang telah dilakukan untuk menyelesaikan laporan ini...'),
                    ])
                    ->visible(fn (Complaint $record): bool => $record->status === 'diproses')
                    ->action(function (Complaint $record, array $data): void {
                        $record->update([
                            'status' => 'selesai',
                            'catatan_penanganan' => $data['catatan_penanganan'],
                            'tanggal_selesai' => now(),
                        ]);

                        // Kirim notifikasi sistem ke warga pelapor
                        NotificationService::sendToUser(
                            $record->user_id,
                            'Pengaduan Selesai Ditangani',
                            "Laporan Anda mengenai '{$record->judul}' telah selesai ditangani. Catatan: " . $data['catatan_penanganan'],
                            route('warga.pengaduan')
                        );

                        FilamentToast::make()
                            ->title('Pengaduan Berhasil Diselesaikan')
                            ->success()
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
