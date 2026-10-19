<?php

namespace App\Filament\Resources\AuditLogs;

use App\Filament\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Audit Log';

    protected static ?string $modelLabel = 'Audit Log';

    protected static ?string $pluralModelLabel = 'Audit Log';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 3;

    /**
     * Menu Audit Log dapat diakses oleh Super Admin dan pengguna dengan izin lihat_audit_log (seperti Ketua RT).
     */
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return (bool) ($user && ($user->hasRole('super_admin') || $user->can('lihat_audit_log')));
    }

    /**
     * Audit log bersifat immutable (tidak dapat ditambah secara manual).
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Audit log bersifat immutable (tidak dapat diubah untuk menjaga integritas pembuktian).
     */
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    /**
     * Audit log bersifat immutable (tidak dapat dihapus sesuai UU PDP No. 27/2022).
     */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
        ];
    }
}
