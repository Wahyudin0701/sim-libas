<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use SensitiveParameter;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profil Pengurus';

    protected string $view = 'filament.pages.auth.edit-profile';

    protected Width | string | null $maxWidth = Width::Full;

    public static function getLabel(): string
    {
        return 'Profil Pengurus';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Profil pengurus berhasil disimpan';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Akun & Pengurus')
                    ->description('Kelola identitas dan kontak pengurus RT 15.')
                    ->icon('heroicon-o-user-circle')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                    ])
                    ->components([
                        Placeholder::make('roles_display')
                            ->label('Jabatan / Peran Pengurus')
                            ->columnSpanFull()
                            ->content(function () {
                                /** @var \App\Models\User|null $user */
                                $user = auth()->user();
                                if (! $user) {
                                    return '-';
                                }

                                $roleMap = [
                                    'super_admin' => 'Super Admin (Administrator Sistem)',
                                    'admin' => 'Admin RT',
                                    'ketua_rt' => 'Ketua RT 15',
                                    'sekretaris' => 'Sekretaris RT 15',
                                    'bendahara' => 'Bendahara RT 15',
                                    'koordinator_bank_sampah' => 'Koordinator Bank Sampah',
                                    'koordinator_keamanan' => 'Koordinator Keamanan Lingkungan',
                                    'rw_kelurahan' => 'Perwakilan RW / Kelurahan',
                                    'warga' => 'Warga RT 15',
                                ];

                                $roles = $user->getRoleNames()->map(fn ($r) => $roleMap[$r] ?? ucfirst(str_replace('_', ' ', $r)))->join(', ');

                                return $roles ?: 'Pengurus RT 15';
                            }),


                        $this->getNameFormComponent()
                            ->label('Nama Lengkap')
                            ->required()
                            ->columnSpan(['default' => 1, 'sm' => 1]),

                        $this->getEmailFormComponent()
                            ->label('Alamat Email')
                            ->required()
                            ->columnSpan(['default' => 1, 'sm' => 1]),

                        TextInput::make('phone')
                            ->label('Nomor WhatsApp / HP')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Contoh: 081274001501')
                            ->columnSpanFull()
                            ->helperText('Nomor aktif untuk keperluan komunikasi dan koordinasi pengurus RT 15.'),
                    ]),

                Section::make('Keamanan & Kata Sandi')
                    ->description('Kosongkan bagian ini apabila Anda tidak ingin mengubah kata sandi.')
                    ->icon('heroicon-o-lock-closed')
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                    ])
                    ->components([
                        $this->getCurrentPasswordFormComponent()
                            ->label('Kata Sandi Saat Ini'),
                        $this->getPasswordFormComponent()
                            ->label('Kata Sandi Baru'),
                        $this->getPasswordConfirmationFormComponent()
                            ->label('Konfirmasi Kata Sandi Baru'),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, #[SensitiveParameter] array $data): Model
    {
        $user = parent::handleRecordUpdate($record, $data);

        // Jika akun user ini terhubung dengan data warga di RT 15, selaraskan data kontak
        if ($user->resident) {
            $user->resident->update(array_filter([
                'nama' => $data['name'] ?? null,
                'telepon' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
            ]));
        }

        return $user;
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan Perubahan')
            ->icon(null)
            ->extraAttributes([
                'class' => '!text-white font-bold bg-[#0B3A85] hover:bg-[#07275E] shadow-xs cursor-pointer',
                'style' => 'color: #ffffff !important;',
            ]);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Kembali')
            ->icon(null)
            ->extraAttributes([
                'class' => 'cursor-pointer',
            ]);
    }
}
