<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\ListRoles;
use BackedEnum;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Manajemen Role';

    protected static ?string $modelLabel = 'Manajemen Role';

    protected static ?string $pluralModelLabel = 'Manajemen Role';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 2;

    /**
     * Menu Manajemen Role hanya dapat diakses oleh Super Admin atau pengguna dengan izin kelola_pengguna.
     */
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && ($user->hasRole('super_admin') || $user->can('kelola_pengguna')));
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && ($user->hasRole('super_admin') || $user->can('kelola_pengguna')));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
        ];
    }
}
