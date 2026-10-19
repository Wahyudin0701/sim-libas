<?php

namespace App\Filament\Resources\Announcements\Pages;

use App\Filament\Resources\Announcements\AnnouncementResource;
use App\Models\Announcement;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAnnouncements extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = AnnouncementResource::class;

    protected string $view = 'filament.resources.announcements.pages.list-announcements';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'tayang', 'pinned', 'draft'

    #[Url]
    public string $targetFilter = ''; // '', 'semua', 'pengurus', 'warga'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?Announcement $selectedAnnouncement = null;

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $announcementId = null;
    public string $form_judul = '';
    public string $form_isi = '';
    public string $form_target = 'semua';
    public bool $form_is_pinned = false;
    public bool $form_is_published = true;
    public ?string $form_tanggal_tayang = null;
    public ?string $form_tanggal_berakhir = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && $this->statusFilter === 'semua') {
            $this->statusFilter = (string) request('status');
        }
        if (request()->has('target') && empty($this->targetFilter)) {
            $this->targetFilter = (string) request('target');
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

    public function updatedTargetFilter(): void
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
        $this->targetFilter = '';
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
        $this->selectedAnnouncement = Announcement::with('pembuat')->find($id);
        if ($this->selectedAnnouncement) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedAnnouncement = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->announcementId = null;
        $this->form_tanggal_tayang = now()->format('Y-m-d\TH:i');
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = Announcement::findOrFail($id);
        $this->announcementId = $item->id;
        $this->isEditing = true;
        $this->form_judul = $item->judul;
        $this->form_isi = $item->isi;
        $this->form_target = $item->target;
        $this->form_is_pinned = (bool) $item->is_pinned;
        $this->form_is_published = (bool) $item->is_published;
        $this->form_tanggal_tayang = $item->tanggal_tayang ? Carbon::parse($item->tanggal_tayang)->format('Y-m-d\TH:i') : null;
        $this->form_tanggal_berakhir = $item->tanggal_berakhir ? Carbon::parse($item->tanggal_berakhir)->format('Y-m-d\TH:i') : null;

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form_judul = '';
        $this->form_isi = '';
        $this->form_target = 'semua';
        $this->form_is_pinned = false;
        $this->form_is_published = true;
        $this->form_tanggal_tayang = null;
        $this->form_tanggal_berakhir = null;
        $this->announcementId = null;
        $this->isEditing = false;
    }

    public function saveAnnouncement(): void
    {
        $this->validate([
            'form_judul' => 'required|string|max:255',
            'form_isi' => 'required|string',
            'form_target' => 'required|in:semua,pengurus,warga',
            'form_tanggal_tayang' => 'nullable|date',
            'form_tanggal_berakhir' => 'nullable|date|after_or_equal:form_tanggal_tayang',
        ], [
            'form_judul.required' => 'Judul pengumuman wajib diisi.',
            'form_isi.required' => 'Isi pengumuman wajib diisi.',
            'form_target.required' => 'Target audiens pengumuman wajib dipilih.',
            'form_tanggal_berakhir.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal tayang.',
        ]);

        $payload = [
            'judul' => $this->form_judul,
            'isi' => $this->form_isi,
            'target' => $this->form_target,
            'is_pinned' => $this->form_is_pinned,
            'is_published' => $this->form_is_published,
            'tanggal_tayang' => $this->form_tanggal_tayang ? Carbon::parse($this->form_tanggal_tayang) : now(),
            'tanggal_berakhir' => $this->form_tanggal_berakhir ? Carbon::parse($this->form_tanggal_berakhir) : null,
        ];

        if ($this->isEditing && $this->announcementId) {
            $announcement = Announcement::findOrFail($this->announcementId);
            $announcement->update($payload);

            Notification::make()
                ->title('Pengumuman Berhasil Diperbarui')
                ->body('Data pengumuman telah disimpan ke database.')
                ->success()
                ->send();
        } else {
            $payload['dibuat_oleh'] = Auth::id() ?? 1;
            Announcement::create($payload);

            Notification::make()
                ->title('Pengumuman Berhasil Diterbitkan')
                ->body('Pengumuman baru telah aktif dan dapat dibaca oleh warga.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function togglePin(int $id): void
    {
        $item = Announcement::findOrFail($id);
        $item->is_pinned = !$item->is_pinned;
        $item->save();

        Notification::make()
            ->title($item->is_pinned ? 'Pengumuman Disematkan' : 'Sematkan Pengumuman Dilepas')
            ->body($item->is_pinned ? 'Pengumuman akan tampil di bagian teratas.' : 'Status pinned berhasil dinonaktifkan.')
            ->success()
            ->send();
    }

    public function deleteAnnouncement(int $id): void
    {
        $item = Announcement::findOrFail($id);
        $item->delete();

        Notification::make()
            ->title('Pengumuman Berhasil Dihapus')
            ->body('Data pengumuman telah dipindahkan ke arsip/sampah.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'pengumuman-warga-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = Announcement::with('pembuat')->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(isi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(target) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($this->statusFilter === 'tayang') {
            $query->active();
        } elseif ($this->statusFilter === 'berakhir') {
            $query->expired();
        } elseif ($this->statusFilter === 'terjadwal') {
            $query->scheduled();
        } elseif ($this->statusFilter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        if (filled($this->targetFilter)) {
            $query->where('target', $this->targetFilter);
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Judul Pengumuman',
                'Isi Pengumuman',
                'Target Audiens',
                'Status Pinned',
                'Status Publikasi',
                'Status Dinamis',
                'Tanggal Tayang',
                'Tanggal Berakhir',
                'Dibuat Oleh',
                'Waktu Dibuat',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->judul,
                    $item->isi,
                    strtoupper($item->target),
                    $item->is_pinned ? 'YA' : 'TIDAK',
                    $item->is_published ? 'TAYANG' : 'DRAFT',
                    strtoupper($item->status_label),
                    $item->tanggal_tayang ? Carbon::parse($item->tanggal_tayang)->format('d/m/Y H:i') : '-',
                    $item->tanggal_berakhir ? Carbon::parse($item->tanggal_berakhir)->format('d/m/Y H:i') : 'Selamanya',
                    $item->pembuat ? $item->pembuat->name : 'Pengurus RT',
                    $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
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
        $now = now()->toDateTimeString();
        $stats = Announcement::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN is_pinned = 1 THEN 1 ELSE 0 END) as pinned,
            SUM(CASE WHEN is_published = 1 AND (tanggal_tayang IS NULL OR tanggal_tayang <= '{$now}') AND (tanggal_berakhir IS NULL OR tanggal_berakhir >= '{$now}') THEN 1 ELSE 0 END) as tayang,
            SUM(CASE WHEN is_published = 1 AND tanggal_berakhir IS NOT NULL AND tanggal_berakhir < '{$now}' THEN 1 ELSE 0 END) as berakhir,
            SUM(CASE WHEN is_published = 1 AND tanggal_tayang IS NOT NULL AND tanggal_tayang > '{$now}' THEN 1 ELSE 0 END) as terjadwal,
            SUM(CASE WHEN is_published = 0 THEN 1 ELSE 0 END) as draft,
            SUM(CASE WHEN target = 'pengurus' THEN 1 ELSE 0 END) as target_pengurus
        ")->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiPinned = (int) ($stats->pinned ?? 0);
        $kpiTayang = (int) ($stats->tayang ?? 0);
        $kpiBerakhir = (int) ($stats->berakhir ?? 0);
        $kpiTerjadwal = (int) ($stats->terjadwal ?? 0);
        $kpiDraft = (int) ($stats->draft ?? 0);
        $kpiTargetPengurus = (int) ($stats->target_pengurus ?? 0);

        $query = Announcement::with('pembuat')
            ->orderByDesc('is_pinned')
            ->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(isi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(target) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($this->statusFilter === 'tayang') {
            $query->active();
        } elseif ($this->statusFilter === 'berakhir') {
            $query->expired();
        } elseif ($this->statusFilter === 'terjadwal') {
            $query->scheduled();
        } elseif ($this->statusFilter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        if (filled($this->targetFilter)) {
            $query->where('target', $this->targetFilter);
        }

        $announcements = $query->paginate($this->perPage);

        return [
            'announcements' => $announcements,
            'kpiTotal' => $kpiTotal,
            'kpiPinned' => $kpiPinned,
            'kpiTayang' => $kpiTayang,
            'kpiBerakhir' => $kpiBerakhir,
            'kpiTerjadwal' => $kpiTerjadwal,
            'kpiDraft' => $kpiDraft,
            'kpiTargetPengurus' => $kpiTargetPengurus,
            'countSemua' => $kpiTotal,
            'countTayang' => $kpiTayang,
            'countBerakhir' => $kpiBerakhir,
            'countTerjadwal' => $kpiTerjadwal,
            'countPinned' => $kpiPinned,
            'countDraft' => $kpiDraft,
        ];
    }
}
