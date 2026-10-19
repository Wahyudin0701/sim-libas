<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.pages.auth.login-layout';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Masuk Portal Pengurus RT 15 | SIM-LiBas';
    }

    public function getHeading(): string | Htmlable | null
    {
        return 'Portal Pengurus RT 15';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Masuk ke panel kendali administrasi, kependudukan, dan layanan warga RT 15.';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email Pengurus')
            ->placeholder('nama.pengurus@simlibas.id')
            ->email()
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi Akun')
            ->placeholder('••••••••')
            ->password()
            ->revealable()
            ->autocomplete('current-password')
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya di perangkat ini');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Masuk ke Panel Pengurus')
            ->icon(null)
            ->size('lg');
    }
}
