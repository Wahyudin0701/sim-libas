<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Select::make('metode')
                    ->options(['tunai' => 'Tunai', 'transfer' => 'Transfer'])
                    ->default('tunai')
                    ->required(),
                TextInput::make('bukti_path'),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                DatePicker::make('tanggal_bayar')
                    ->required(),
                TextInput::make('divalidasi_oleh')
                    ->numeric(),
                DateTimePicker::make('tanggal_validasi'),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'valid' => 'Valid', 'ditolak' => 'Ditolak'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
