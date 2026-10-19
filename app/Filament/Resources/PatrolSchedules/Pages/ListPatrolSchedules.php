<?php

namespace App\Filament\Resources\PatrolSchedules\Pages;

use App\Filament\Resources\PatrolSchedules\PatrolScheduleResource;
use App\Models\PatrolMember;
use App\Models\PatrolSchedule;
use App\Models\Resident;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPatrolSchedules extends ListRecords
{
    protected static string $resource = PatrolScheduleResource::class;

    protected string $view = 'filament.resources.patrol-schedules.pages.list-patrol-schedules';

    #[Url]
    public string $search = '';

    #[Url]
    public string $reguFilter = '';

    #[Url]
    public string $waktuFilter = 'semua'; // 'semua', 'mendatang', 'selesai'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?PatrolSchedule $selectedSchedule = null;

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $scheduleId = null;
    public ?string $form_tanggal = null;
    public string $form_regu = 'Regu 1 (Garuda)';
    public string $form_waktu_mulai = '23:00';
    public string $form_waktu_selesai = '04:00';
    public string $form_catatan = '';
    public array $form_resident_ids = [];

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('regu') && empty($this->reguFilter)) {
            $this->reguFilter = (string) request('regu');
        }
        if (request()->has('waktu') && $this->waktuFilter === 'semua') {
            $this->waktuFilter = (string) request('waktu');
        }
    }

    public function setWaktu(string $waktu): void
    {
        $this->waktuFilter = $waktu;
        $this->resetPage();
    }

    public function updatedWaktuFilter(): void
    {
        $this->resetPage();
    }

    public function updatedReguFilter(): void
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
        $this->reguFilter = '';
        $this->waktuFilter = 'semua';
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
        $this->selectedSchedule = PatrolSchedule::with('members.resident')->find($id);
        if ($this->selectedSchedule) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedSchedule = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->scheduleId = null;
        $this->form_tanggal = now()->format('Y-m-d');
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = PatrolSchedule::with('members')->findOrFail($id);
        $this->scheduleId = $item->id;
        $this->isEditing = true;
        $this->form_tanggal = $item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : null;
        $this->form_regu = $item->regu;
        $this->form_waktu_mulai = $item->waktu_mulai ? substr($item->waktu_mulai, 0, 5) : '23:00';
        $this->form_waktu_selesai = $item->waktu_selesai ? substr($item->waktu_selesai, 0, 5) : '04:00';
        $this->form_catatan = $item->catatan ?? '';
        $this->form_resident_ids = $item->members->pluck('resident_id')->map(fn ($id) => (string) $id)->toArray();

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->scheduleId = null;
        $this->form_tanggal = null;
        $this->form_regu = 'Regu 1 (Garuda)';
        $this->form_waktu_mulai = '23:00';
        $this->form_waktu_selesai = '04:00';
        $this->form_catatan = '';
        $this->form_resident_ids = [];
        $this->isEditing = false;
    }

    public function saveSchedule(): void
    {
        $this->validate([
            'form_tanggal' => 'required|date',
            'form_regu' => 'required|string|max:100',
            'form_waktu_mulai' => 'required',
            'form_waktu_selesai' => 'required',
            'form_catatan' => 'nullable|string',
            'form_resident_ids' => 'nullable|array',
            'form_resident_ids.*' => 'exists:residents,id',
        ], [
            'form_tanggal.required' => 'Tanggal ronda wajib ditentukan.',
            'form_regu.required' => 'Nama regu ronda wajib diisi.',
            'form_waktu_mulai.required' => 'Jam mulai ronda wajib diisi.',
            'form_waktu_selesai.required' => 'Jam selesai ronda wajib diisi.',
        ]);

        $payload = [
            'tanggal' => Carbon::parse($this->form_tanggal),
            'regu' => $this->form_regu,
            'waktu_mulai' => $this->form_waktu_mulai,
            'waktu_selesai' => $this->form_waktu_selesai,
            'catatan' => $this->form_catatan,
        ];

        if ($this->isEditing && $this->scheduleId) {
            $schedule = PatrolSchedule::findOrFail($this->scheduleId);
            $schedule->update($payload);

            // Sync members
            PatrolMember::where('patrol_schedule_id', $schedule->id)->delete();
            foreach ($this->form_resident_ids as $resId) {
                PatrolMember::create([
                    'patrol_schedule_id' => $schedule->id,
                    'resident_id' => (int) $resId,
                    'is_pengganti' => false,
                ]);
            }

            Notification::make()
                ->title('Jadwal Ronda Diperbarui')
                ->body('Perubahan jadwal dan anggota poskamling telah disimpan.')
                ->success()
                ->send();
        } else {
            $schedule = PatrolSchedule::create($payload);

            foreach ($this->form_resident_ids as $resId) {
                PatrolMember::create([
                    'patrol_schedule_id' => $schedule->id,
                    'resident_id' => (int) $resId,
                    'is_pengganti' => false,
                ]);
            }

            Notification::make()
                ->title('Jadwal Ronda Berhasil Dibuat')
                ->body('Jadwal ronda malam dan personil telah ditugaskan.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteSchedule(int $id): void
    {
        $item = PatrolSchedule::findOrFail($id);
        $item->delete();

        Notification::make()
            ->title('Jadwal Ronda Dihapus')
            ->body('Jadwal ronda telah dihapus dari sistem.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'jadwal-ronda-malam-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = PatrolSchedule::with('members.resident')->latest('tanggal');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(regu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                    ->orWhereHas('residents', function ($rq) use ($term) {
                        $rq->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                    });
            });
        }

        if (filled($this->reguFilter)) {
            $query->where('regu', $this->reguFilter);
        }

        if ($this->waktuFilter === 'mendatang') {
            $query->where('tanggal', '>=', now()->toDateString());
        } elseif ($this->waktuFilter === 'selesai') {
            $query->where('tanggal', '<', now()->toDateString());
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Tanggal Ronda',
                'Regu Ronda',
                'Jam Ronda',
                'Jumlah Personil',
                'Daftar Anggota Bertugas',
                'Catatan / Pos',
            ]);

            foreach ($records as $item) {
                $memberNames = $item->members->map(fn ($m) => $m->resident ? $m->resident->nama_lengkap : 'Warga')->implode(', ');

                fputcsv($handle, [
                    $item->id,
                    $item->tanggal ? Carbon::parse($item->tanggal)->format('d/m/Y') : '-',
                    $item->regu,
                    "{$item->waktu_mulai} - {$item->waktu_selesai}",
                    $item->members->count(),
                    $memberNames ?: 'Belum ditentukan',
                    $item->catatan ?? '-',
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
        $today = now()->toDateString();

        $stats = PatrolSchedule::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN tanggal >= ? THEN 1 ELSE 0 END) as mendatang,
            SUM(CASE WHEN tanggal < ? THEN 1 ELSE 0 END) as selesai
        ", [$today, $today])->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiMendatang = (int) ($stats->mendatang ?? 0);
        $countSelesai = (int) ($stats->selesai ?? 0);
        $kpiTotalPetugas = PatrolMember::distinct('resident_id')->count('resident_id');
        $kpiTotalRegu = PatrolSchedule::distinct('regu')->count('regu');

        $query = PatrolSchedule::with('members.resident')->orderBy('tanggal', 'desc');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(regu) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                    ->orWhereHas('residents', function ($rq) use ($term) {
                        $rq->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                    });
            });
        }

        if (filled($this->reguFilter)) {
            $query->where('regu', $this->reguFilter);
        }

        if ($this->waktuFilter === 'mendatang') {
            $query->where('tanggal', '>=', $today);
        } elseif ($this->waktuFilter === 'selesai') {
            $query->where('tanggal', '<', $today);
        }

        $schedules = $query->paginate($this->perPage);

        $reguOptions = PatrolSchedule::select('regu')->distinct()->pluck('regu');

        $allResidents = Resident::select('id', 'nama', 'id_warga', 'household_id')
            ->with('household:id,id_kk,alamat')
            ->where('status_kependudukan', 'aktif')
            ->orderBy('nama')
            ->get();

        if ($allResidents->isEmpty()) {
            $allResidents = Resident::select('id', 'nama', 'id_warga', 'household_id')
                ->with('household:id,id_kk,alamat')
                ->orderBy('nama')
                ->get();
        }

        return [
            'schedules' => $schedules,
            'reguOptions' => $reguOptions,
            'allResidents' => $allResidents,
            'kpiTotal' => $kpiTotal,
            'kpiMendatang' => $kpiMendatang,
            'kpiTotalPetugas' => $kpiTotalPetugas,
            'kpiTotalRegu' => $kpiTotalRegu,
            'countSemua' => $kpiTotal,
            'countMendatang' => $kpiMendatang,
            'countSelesai' => $countSelesai,
        ];
    }
}
