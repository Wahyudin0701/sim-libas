<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                Select::make('fee_type_id')
                    ->relationship('feeType', 'id')
                    ->required(),
                TextInput::make('periode_bulan')
                    ->required()
                    ->numeric(),
                TextInput::make('periode_tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['belum_bayar' => 'Belum bayar', 'sebagian' => 'Sebagian', 'lunas' => 'Lunas'])
                    ->default('belum_bayar')
                    ->required(),
                DatePicker::make('tanggal_jatuh_tempo'),
            ]);
    }
}
