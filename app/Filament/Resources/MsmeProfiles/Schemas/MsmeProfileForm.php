<?php

namespace App\Filament\Resources\MsmeProfiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MsmeProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('household_id')
                    ->relationship('household', 'no_kk')
                    ->label('Keluarga (No. KK)')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nama_usaha')
                    ->label('Nama Usaha / Toko')
                    ->required()
                    ->maxLength(255),
                Select::make('kategori')
                    ->label('Kategori Usaha')
                    ->options([
                        'kuliner' => 'Kuliner & Makanan',
                        'sembako' => 'Warung & Sembako',
                        'jasa' => 'Jasa & Layanan',
                        'kerajinan' => 'Kerajinan Tangan',
                        'fashion' => 'Fashion & Busana',
                        'pertanian' => 'Hasil Kebun & Tani',
                        'lainnya' => 'Lainnya',
                    ])
                    ->default('kuliner')
                    ->required(),
                FileUpload::make('foto_path')
                    ->label('Foto Usaha / Produk')
                    ->image()
                    ->disk('public')
                    ->directory('umkm')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Format JPG, PNG, atau WebP maks. 5MB. Otomatis dioptimasi ke WebP.')
                    ->columnSpanFull(),
                TextInput::make('alamat')
                    ->label('Alamat Usaha')
                    ->placeholder('Kosongkan jika sama dengan domisili KK')
                    ->maxLength(255),
                TextInput::make('telepon')
                    ->label('Nomor Telepon / HP')
                    ->tel()
                    ->required()
                    ->maxLength(50),
                TextInput::make('whatsapp')
                    ->label('Nomor WhatsApp')
                    ->tel()
                    ->placeholder('081234567890')
                    ->maxLength(50),
                TextInput::make('social_links.instagram')
                    ->label('Akun Instagram')
                    ->placeholder('@username atau tautan profil')
                    ->maxLength(100),
                TextInput::make('social_links.tiktok')
                    ->label('Akun TikTok')
                    ->placeholder('@username atau tautan profil')
                    ->maxLength(100),
                TextInput::make('social_links.shopee')
                    ->label('Toko Shopee')
                    ->placeholder('Nama toko atau https://shopee.co.id/...')
                    ->maxLength(255),
                TextInput::make('produk')
                    ->label('Menu / Produk Unggulan')
                    ->placeholder('Pisahkan dengan tanda koma (misal: Pempek, Tekwan, Model)')
                    ->columnSpanFull(),
                Textarea::make('deskripsi')
                    ->label('Deskripsi Profil Usaha')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('status_moderasi')
                    ->label('Status Moderasi')
                    ->options(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'])
                    ->default('disetujui')
                    ->required(),
                Textarea::make('catatan_moderasi')
                    ->label('Catatan Moderasi / Alasan Penolakan')
                    ->rows(2)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Aktif Tayang di Katalog Warga')
                    ->default(true)
                    ->required(),
            ]);
    }
}
