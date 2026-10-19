<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected string $view = 'filament.resources.events.pages.list-events';

    #[Url]
    public string $search = '';

    #[Url]
    public string $kategoriFilter = ''; // '', 'kerja_bakti', 'arisan', 'rapat', 'pengajian', 'perayaan', 'lainnya'

    #[Url]
    public string $waktuFilter = 'semua'; // 'semua', 'mendatang', 'bulan_ini', 'lewat'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?Event $selectedEvent = null;

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $eventId = null;
    public string $form_judul = '';
    public string $form_kategori = 'kerja_bakti';
    public string $form_penyelenggara = 'Pengurus RT 15';
    public string $form_lokasi = '';
    public ?string $form_tanggal_mulai = null;
    public ?string $form_tanggal_selesai = null;
    public string $form_deskripsi = '';
    public bool $form_is_recurring = false;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('kategori') && empty($this->kategoriFilter)) {
            $this->kategoriFilter = (string) request('kategori');
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

    public function updatedKategoriFilter(): void
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
        $this->kategoriFilter = '';
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
        $this->selectedEvent = Event::with('pembuat')->find($id);
        if ($this->selectedEvent) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedEvent = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->eventId = null;
        $this->form_tanggal_mulai = now()->addDays(1)->setHour(8)->setMinute(0)->format('Y-m-d\TH:i');
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = Event::findOrFail($id);
        $this->eventId = $item->id;
        $this->isEditing = true;
        $this->form_judul = $item->judul;
        $this->form_kategori = $item->kategori;
        $this->form_penyelenggara = $item->penyelenggara ?: 'Pengurus RT 15';
        $this->form_lokasi = $item->lokasi ?? '';
        $this->form_tanggal_mulai = $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('Y-m-d\TH:i') : null;
        $this->form_tanggal_selesai = $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('Y-m-d\TH:i') : null;
        $this->form_deskripsi = $item->deskripsi ?? '';
        $this->form_is_recurring = (bool) $item->is_recurring;

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
        $this->form_kategori = 'kerja_bakti';
        $this->form_penyelenggara = 'Pengurus RT 15';
        $this->form_lokasi = '';
        $this->form_tanggal_mulai = null;
        $this->form_tanggal_selesai = null;
        $this->form_deskripsi = '';
        $this->form_is_recurring = false;
        $this->eventId = null;
        $this->isEditing = false;
    }

    public function saveEvent(): void
    {
        $this->validate([
            'form_judul' => 'required|string|max:255',
            'form_kategori' => 'required|in:kerja_bakti,arisan,rapat,pengajian,perayaan,lainnya',
            'form_penyelenggara' => 'required|string|max:150',
            'form_lokasi' => 'required|string|max:255',
            'form_tanggal_mulai' => 'required|date',
            'form_tanggal_selesai' => 'nullable|date|after_or_equal:form_tanggal_mulai',
            'form_deskripsi' => 'nullable|string',
        ], [
            'form_judul.required' => 'Nama kegiatan wajib diisi.',
            'form_penyelenggara.required' => 'Penyelenggara kegiatan wajib diisi.',
            'form_lokasi.required' => 'Lokasi kegiatan wajib diisi.',
            'form_tanggal_mulai.required' => 'Waktu mulai kegiatan wajib ditentukan.',
            'form_tanggal_selesai.after_or_equal' => 'Waktu selesai tidak boleh sebelum waktu mulai.',
        ]);

        $payload = [
            'judul' => $this->form_judul,
            'kategori' => $this->form_kategori,
            'penyelenggara' => $this->form_penyelenggara ?: 'Pengurus RT 15',
            'lokasi' => $this->form_lokasi,
            'tanggal_mulai' => Carbon::parse($this->form_tanggal_mulai),
            'tanggal_selesai' => $this->form_tanggal_selesai ? Carbon::parse($this->form_tanggal_selesai) : null,
            'deskripsi' => $this->form_deskripsi,
            'is_recurring' => $this->form_is_recurring,
        ];

        if ($this->isEditing && $this->eventId) {
            $event = Event::findOrFail($this->eventId);
            $event->update($payload);

            Notification::make()
                ->title('Agenda Berhasil Diperbarui')
                ->body('Perubahan jadwal kegiatan warga telah disimpan.')
                ->success()
                ->send();
        } else {
            $payload['dibuat_oleh'] = Auth::id() ?? 1;
            Event::create($payload);

            Notification::make()
                ->title('Agenda Baru Berhasil Ditambahkan')
                ->body('Kegiatan baru telah dijadwalkan pada kalender RT 15.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteEvent(int $id): void
    {
        $item = Event::findOrFail($id);
        $item->delete();

        Notification::make()
            ->title('Agenda Berhasil Dihapus')
            ->body('Kegiatan telah dipindahkan ke arsip/sampah.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'agenda-kegiatan-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = Event::with('pembuat')->latest('tanggal_mulai');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
            });
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if ($this->waktuFilter === 'mendatang') {
            $query->where('tanggal_mulai', '>=', now());
        } elseif ($this->waktuFilter === 'bulan_ini') {
            $query->whereMonth('tanggal_mulai', now()->month)
                ->whereYear('tanggal_mulai', now()->year);
        } elseif ($this->waktuFilter === 'lewat') {
            $query->where('tanggal_mulai', '<', now());
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Nama Kegiatan',
                'Kategori',
                'Penyelenggara',
                'Lokasi',
                'Tanggal & Waktu Mulai',
                'Tanggal & Waktu Selesai',
                'Rutin Berkala',
                'Deskripsi',
                'Dibuat Oleh',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->judul,
                    strtoupper($item->kategori),
                    $item->penyelenggara ?: ($item->pembuat ? $item->pembuat->name : 'Pengurus RT 15'),
                    $item->lokasi ?? '-',
                    $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('d/m/Y H:i') : '-',
                    $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-',
                    $item->is_recurring ? 'YA' : 'TIDAK',
                    $item->deskripsi ?? '-',
                    $item->pembuat ? $item->pembuat->name : 'Pengurus RT',
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
        $startOfMonth = now()->startOfMonth()->toDateTimeString();
        $endOfMonth = now()->endOfMonth()->toDateTimeString();
        $nowStr = now()->toDateTimeString();

        $stats = Event::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN tanggal_mulai >= ? AND tanggal_mulai <= ? THEN 1 ELSE 0 END) as bulan_ini,
            SUM(CASE WHEN kategori = 'kerja_bakti' THEN 1 ELSE 0 END) as kerja_bakti,
            SUM(CASE WHEN kategori = 'rapat' THEN 1 ELSE 0 END) as rapat,
            SUM(CASE WHEN tanggal_mulai >= ? THEN 1 ELSE 0 END) as mendatang,
            SUM(CASE WHEN tanggal_mulai < ? THEN 1 ELSE 0 END) as lewat
        ", [$startOfMonth, $endOfMonth, $nowStr, $nowStr])->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiBulanIni = (int) ($stats->bulan_ini ?? 0);
        $kpiKerjaBakti = (int) ($stats->kerja_bakti ?? 0);
        $kpiRapat = (int) ($stats->rapat ?? 0);
        $countMendatang = (int) ($stats->mendatang ?? 0);
        $countLewat = (int) ($stats->lewat ?? 0);

        $query = Event::with('pembuat')->orderBy('tanggal_mulai', 'desc');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(penyelenggara) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
            });
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if ($this->waktuFilter === 'mendatang') {
            $query->where('tanggal_mulai', '>=', now());
        } elseif ($this->waktuFilter === 'bulan_ini') {
            $query->whereMonth('tanggal_mulai', now()->month)
                ->whereYear('tanggal_mulai', now()->year);
        } elseif ($this->waktuFilter === 'lewat') {
            $query->where('tanggal_mulai', '<', now());
        }

        $events = $query->paginate($this->perPage);

        return [
            'events' => $events,
            'kpiTotal' => $kpiTotal,
            'kpiBulanIni' => $kpiBulanIni,
            'kpiKerjaBakti' => $kpiKerjaBakti,
            'kpiRapat' => $kpiRapat,
            'countSemua' => $kpiTotal,
            'countMendatang' => $countMendatang,
            'countBulanIni' => $kpiBulanIni,
            'countLewat' => $countLewat,
            'kategoriList' => ['kerja_bakti', 'arisan', 'rapat', 'pengajian', 'perayaan', 'lainnya'],
        ];
    }
}
