<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class SimLibasAvatarProvider implements AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(function (string $segment): string {
                $letters = preg_replace('/^[^\p{L}\p{N}]+/u', '', $segment);
                return filled($letters) ? mb_substr($letters, 0, 1) : '';
            })
            ->filter()
            ->take(2)
            ->join(' ');

        // Warna hijau tua elegan (#166534) khas SIM-LiBas RT 15, bukan warna hitam bawaan
        $background = '166534';

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&format=svg&color=FFFFFF&background=' . $background . '&bold=true';
    }
}
