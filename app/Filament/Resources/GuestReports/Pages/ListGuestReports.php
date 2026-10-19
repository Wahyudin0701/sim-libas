<?php

namespace App\Filament\Resources\GuestReports\Pages;

use App\Filament\Resources\GuestReports\GuestReportResource;
use App\Models\GuestReport;
use App\Models\Household;
use App\Services\NotificationService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListGuestReports extends ListRecords
{
    protected static string $resource = GuestReportResource::class;

    protected string $view = 'filament.resources.guest-reports.pages.list-guest-reports';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'menginap', 'pulang'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?GuestReport $selectedReport = null;

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $reportId = null;
    public ?int $form_household_id = null;
    public string $form_nama_tamu = '';
    public string $form_nik_tamu = '';
    public string $form_asal = '';
    public string $form_tujuan = '';
    public ?string $form_tanggal_datang = null;
    public ?string $form_tanggal_pulang = null;
    public string $form_status = 'menginap';

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && $this->statusFilter === 'semua') {
            $this->statusFilter = (string) request('status');
        }
    }

    public function setStatus(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->resetPage();
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function openDetail(int $id): void
    {
        $this->selectedReport = GuestReport::with(['household.headOfHousehold', 'pelapor'])->find($id);
        if ($this->selectedReport) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedReport = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->reportId = null;
        $this->form_status = 'menginap';
        $this->form_tanggal_datang = now()->format('Y-m-d');
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = GuestReport::findOrFail($id);
        $this->reportId = $item->id;
        $this->isEditing = true;
        $this->form_household_id = $item->household_id;
        $this->form_nama_tamu = $item->nama_tamu;
        $this->form_nik_tamu = $item->nik_tamu ?? '';
        $this->form_asal = $item->asal;
        $this->form_tujuan = $item->tujuan ?? '';
        $this->form_status = $item->status ?? 'menginap';
        $this->form_tanggal_datang = $item->tanggal_datang ? Carbon::parse($item->tanggal_datang)->format('Y-m-d') : null;
        $this->form_tanggal_pulang = $item->tanggal_pulang ? Carbon::parse($item->tanggal_pulang)->format('Y-m-d') : null;

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form_household_id = null;
        $this->form_nama_tamu = '';
        $this->form_nik_tamu = '';
        $this->form_asal = '';
        $this->form_tujuan = '';
        $this->form_status = 'menginap';
        $this->form_tanggal_datang = null;
        $this->form_tanggal_pulang = null;
        $this->reportId = null;
        $this->isEditing = false;
    }

    public function catatPulang(int $id): void
    {
        $report = GuestReport::findOrFail($id);
        $report->status = 'pulang';
        $report->tanggal_pulang = now()->toDateString();
        $report->save();

        Notification::make()
            ->title('Kepulangan Tamu Dicatat')
            ->body("Tamu {$report->nama_tamu} telah dicatat kepulangannya pada hari ini.")
            ->success()
            ->send();
    }

    public function saveGuestReport(): void
    {
        $this->validate([
            'form_household_id' => 'required|exists:households,id',
            'form_nama_tamu' => 'required|string|max:255',
            'form_nik_tamu' => 'nullable|string|digits:16',
            'form_asal' => 'required|string|max:255',
            'form_tujuan' => 'nullable|string|max:500',
            'form_status' => 'required|in:menginap,pulang',
            'form_tanggal_datang' => 'required|date',
            'form_tanggal_pulang' => 'nullable|date|after_or_equal:form_tanggal_datang',
        ], [
            'form_household_id.required' => 'Keluarga tuan rumah wajib dipilih.',
            'form_nama_tamu.required' => 'Nama tamu wajib diisi.',
            'form_asal.required' => 'Kota/asal daerah tamu wajib diisi.',
            'form_tanggal_datang.required' => 'Tanggal kedatangan wajib diisi.',
            'form_tanggal_pulang.after_or_equal' => 'Tanggal kepulangan tidak boleh sebelum tanggal kedatangan.',
        ]);

        $payload = [
            'household_id' => $this->form_household_id,
            'nama_tamu' => $this->form_nama_tamu,
            'nik_tamu' => $this->form_nik_tamu ?: null,
            'asal' => $this->form_asal,
            'tujuan' => $this->form_tujuan,
            'status' => $this->form_status ?: 'menginap',
            'tanggal_datang' => Carbon::parse($this->form_tanggal_datang),
            'tanggal_pulang' => $this->form_tanggal_pulang ? Carbon::parse($this->form_tanggal_pulang) : null,
        ];

        // Jika status ditandai pulang tapi tanggal pulang belum diisi, otomatis isi tanggal hari ini
        if ($payload['status'] === 'pulang' && empty($payload['tanggal_pulang'])) {
            $payload['tanggal_pulang'] = now()->toDateString();
        }

        if ($this->isEditing && $this->reportId) {
            $report = GuestReport::findOrFail($this->reportId);
            $report->update($payload);

            Notification::make()
                ->title('Laporan Tamu Diperbarui')
                ->body('Data tamu menginap berhasil diperbarui.')
                ->success()
                ->send();
        } else {
            $payload['dilaporkan_oleh'] = Auth::id() ?? 1;
            $newReport = GuestReport::create($payload);

            NotificationService::notifyNewGuestReport($newReport);

            Notification::make()
                ->title('Laporan Tamu Tersimpan')
                ->body('Data tamu 1x24 jam telah berhasil dicatat.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteGuestReport(int $id): void
    {
        $item = GuestReport::findOrFail($id);
        $item->delete();

        Notification::make()
            ->title('Laporan Tamu Dihapus')
            ->body('Data laporan tamu telah dihapus dari sistem.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'lapor-tamu-1x24jam-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = GuestReport::with(['household.headOfHousehold', 'pelapor'])->latest('tanggal_datang');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama_tamu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(nik_tamu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(asal) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(tujuan) LIKE ?', ["%{$term}%"])
                    ->orWhereHas('household', function ($hq) use ($term) {
                        $hq->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(alamat) LIKE ?', ["%{$term}%"]);
                    });
            });
        }

        if ($this->statusFilter === 'menginap') {
            $query->where('status', 'menginap');
        } elseif ($this->statusFilter === 'pulang') {
            $query->where('status', 'pulang');
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Nama Tamu',
                'NIK Tamu',
                'Asal Daerah',
                'Tujuan Berkunjung',
                'Tuan Rumah (Kepala KK)',
                'Nomor KK',
                'Alamat Singgah',
                'Tanggal Datang',
                'Tanggal Pulang',
                'Status Menginap',
                'Pelapor',
            ]);

            foreach ($records as $item) {
                $isMenginap = ($item->status ?? 'menginap') === 'menginap';

                fputcsv($handle, [
                    $item->id,
                    $item->nama_tamu,
                    $item->nik_tamu ? "'{$item->nik_tamu}'" : '-',
                    $item->asal,
                    $item->tujuan ?? '-',
                    $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama_lengkap : 'Keluarga RT 15',
                    $item->household ? "'{$item->household->nomor_kk}'" : '-',
                    $item->household ? $item->household->alamat_lengkap : '-',
                    $item->tanggal_datang ? Carbon::parse($item->tanggal_datang)->format('d/m/Y') : '-',
                    $item->tanggal_pulang ? Carbon::parse($item->tanggal_pulang)->format('d/m/Y') : 'Masih Menginap',
                    $isMenginap ? 'MENGINAP (AKTIF)' : 'SUDAH PULANG',
                    $item->pelapor ? $item->pelapor->name : 'Pengurus RT',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    protected function getViewData(): array
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $stats = GuestReport::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'menginap' THEN 1 ELSE 0 END) as menginap,
            SUM(CASE WHEN status = 'pulang' THEN 1 ELSE 0 END) as pulang,
            SUM(CASE WHEN tanggal_datang >= ? AND tanggal_datang <= ? THEN 1 ELSE 0 END) as bulan_ini
        ", [$startOfMonth, $endOfMonth])->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiMenginap = (int) ($stats->menginap ?? 0);
        $kpiPulang = (int) ($stats->pulang ?? 0);
        $kpiBulanIni = (int) ($stats->bulan_ini ?? 0);

        $query = GuestReport::with(['household.headOfHousehold', 'pelapor'])->latest('tanggal_datang');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama_tamu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(nik_tamu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(asal) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(tujuan) LIKE ?', ["%{$term}%"])
                    ->orWhereHas('household', function ($hq) use ($term) {
                        $hq->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(alamat) LIKE ?', ["%{$term}%"]);
                    });
            });
        }

        if ($this->statusFilter === 'menginap') {
            $query->where('status', 'menginap');
        } elseif ($this->statusFilter === 'pulang') {
            $query->where('status', 'pulang');
        }

        $reports = $query->paginate($this->perPage);

        $householdList = Household::with('headOfHousehold')
            ->orderBy('id')
            ->get();

        return [
            'reports' => $reports,
            'householdList' => $householdList,
            'kpiTotal' => $kpiTotal,
            'kpiMenginap' => $kpiMenginap,
            'kpiPulang' => $kpiPulang,
            'kpiBulanIni' => $kpiBulanIni,
            'countSemua' => $kpiTotal,
            'countMenginap' => $kpiMenginap,
            'countPulang' => $kpiPulang,
        ];
    }
}
