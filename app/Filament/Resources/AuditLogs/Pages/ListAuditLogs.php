<?php

namespace App\Filament\Resources\AuditLogs\Pages;

use App\Filament\Resources\AuditLogs\AuditLogResource;
use App\Models\AuditLog;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected string $view = 'filament.resources.audit-logs.pages.list-audit-logs';

    #[Url]
    public string $search = '';

    #[Url]
    public string $eventFilter = 'semua'; // 'semua', 'created', 'updated', 'deleted', 'login'

    #[Url]
    public string $moduleFilter = 'semua';

    #[Url]
    public string $periodFilter = 'semua'; // 'semua', 'hari_ini', '7_hari', '30_hari', 'bulan_ini'

    public int $perPage = 15;

    // State Modal Detail Popup
    public bool $detailModalOpen = false;
    public ?int $selectedLogId = null;
    public ?AuditLog $selectedLog = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('event') && $this->eventFilter === 'semua') {
            $this->eventFilter = (string) request('event');
        }
        if (request()->has('module') && $this->moduleFilter === 'semua') {
            $this->moduleFilter = (string) request('module');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedEventFilter(): void
    {
        $this->resetPage();
    }

    public function updatedModuleFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPeriodFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->eventFilter = 'semua';
        $this->moduleFilter = 'semua';
        $this->periodFilter = 'semua';
        $this->resetPage();

        Notification::make()
            ->title('Filter Direset')
            ->body('Seluruh parameter pencarian audit log telah dikembalikan ke awal.')
            ->info()
            ->send();
    }

    public function openDetail(int $id): void
    {
        $this->selectedLogId = $id;
        $this->selectedLog = AuditLog::with('causer')->find($id);

        if (!$this->selectedLog) {
            Notification::make()
                ->title('Log Tidak Ditemukan')
                ->body('Data log aktivitas dengan ID #' . $id . ' tidak tersedia.')
                ->danger()
                ->send();
            return;
        }

        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedLogId = null;
        $this->selectedLog = null;
    }

    /**
     * Ekspor data log yang tersaring ke berkas CSV berstandar audit.
     */
    public function exportCsv(): StreamedResponse
    {
        $query = $this->buildFilteredQuery();
        $logs = $query->take(2000)->get();

        $filename = 'audit-log-sim-libas-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return Response::stream(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID Log',
                'Waktu & Tanggal (WIB)',
                'Tindakan (Event)',
                'Modul / Subjek',
                'ID Objek',
                'Nama Pelaku',
                'Email Pelaku',
                'Peran Pelaku',
                'Deskripsi Perubahan',
            ], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->formatted_date,
                    $log->action_label,
                    $log->module_name,
                    $log->subject_id ?? '-',
                    $log->causer_name,
                    $log->causer_email ?? '-',
                    $log->causer_role,
                    $log->description,
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Membangun query data log yang konsisten dengan filter aktif.
     */
    protected function buildFilteredQuery()
    {
        $query = AuditLog::query()->with('causer')->search($this->search);

        if ($this->eventFilter !== 'semua') {
            $query->where('event', $this->eventFilter);
        }

        if ($this->moduleFilter !== 'semua') {
            $query->where('subject_type', 'like', "%{$this->moduleFilter}%");
        }

        if ($this->periodFilter !== 'semua') {
            match ($this->periodFilter) {
                'hari_ini' => $query->whereDate('created_at', Carbon::today()),
                '7_hari' => $query->where('created_at', '>=', Carbon::now()->subDays(7)),
                '30_hari' => $query->where('created_at', '>=', Carbon::now()->subDays(30)),
                'bulan_ini' => $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year),
                default => null,
            };
        }

        return $query->latest('id');
    }

    /**
     * Ambil data log terpaginasi untuk tabel.
     */
    public function getLogsProperty(): LengthAwarePaginator
    {
        return $this->buildFilteredQuery()->paginate($this->perPage);
    }

    /**
     * Daftar pilihan modul unik untuk dropdown filter.
     */
    public function getAvailableModulesProperty(): array
    {
        $types = AuditLog::query()
            ->whereNotNull('subject_type')
            ->select('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->toArray();

        $modules = [];
        foreach ($types as $type) {
            $shortName = class_basename($type);
            $label = AuditLog::MODULE_NAMES[$type] ?? $shortName;
            $modules[$shortName] = $label;
        }

        return $modules;
    }

    protected function getViewData(): array
    {
        $totalLogs = AuditLog::count();
        $todayLogs = AuditLog::whereDate('created_at', Carbon::today())->count();
        $changeLogs = AuditLog::whereIn('event', ['created', 'updated', 'deleted'])->count();

        // Cari pelaku tindakan teraktif
        $topCauser = AuditLog::query()
            ->whereNotNull('causer_id')
            ->selectRaw('causer_id, count(*) as total')
            ->groupBy('causer_id')
            ->orderByDesc('total')
            ->with('causer')
            ->first();

        $mostActiveUserName = $topCauser?->causer?->name ?? 'Sistem Otomatis';
        $mostActiveUserCount = $topCauser?->total ?? 0;

        return [
            'logs' => $this->logs,
            'totalLogs' => $totalLogs,
            'todayLogs' => $todayLogs,
            'changeLogs' => $changeLogs,
            'mostActiveUserName' => $mostActiveUserName,
            'mostActiveUserCount' => $mostActiveUserCount,
            'availableModules' => $this->availableModules,
        ];
    }
}
