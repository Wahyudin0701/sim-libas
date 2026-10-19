<?php

namespace App\Filament\Resources\Complaints\Pages;

use App\Filament\Resources\Complaints\ComplaintResource;
use App\Models\Complaint;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;

    protected string $view = 'filament.resources.complaints.pages.list-complaints';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'diterima', 'diproses', 'selesai', 'ditolak'

    #[Url]
    public string $kategoriFilter = ''; // '', 'infrastruktur', 'kebersihan', 'keamanan', 'kebisingan', 'lainnya'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?Complaint $selectedComplaint = null;

    // State Modal Tangani / Update Status
    public bool $handlingModalOpen = false;
    public ?int $complaintId = null;
    public string $form_status = 'diproses';
    public string $form_catatan_penanganan = '';
    public ?int $form_penanggung_jawab_id = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && $this->statusFilter === 'semua') {
            $this->statusFilter = (string) request('status');
        }
        if (request()->has('kategori') && empty($this->kategoriFilter)) {
            $this->kategoriFilter = (string) request('kategori');
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
        $this->statusFilter = 'semua';
        $this->kategoriFilter = '';
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
        $this->selectedComplaint = Complaint::with(['pelapor', 'penanggungJawab'])->find($id);
        if ($this->selectedComplaint) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedComplaint = null;
    }

    public function openHandling(int $id): void
    {
        $complaint = Complaint::findOrFail($id);
        $this->complaintId = $complaint->id;
        $this->form_status = $complaint->status;
        $this->form_catatan_penanganan = $complaint->catatan_penanganan ?? '';
        $this->form_penanggung_jawab_id = $complaint->penanggung_jawab_id ?? Auth::id();
        $this->handlingModalOpen = true;
    }

    public function closeHandling(): void
    {
        $this->handlingModalOpen = false;
        $this->complaintId = null;
        $this->form_catatan_penanganan = '';
    }

    public function saveHandling(): void
    {
        $this->validate([
            'form_status' => 'required|in:diterima,diproses,selesai,ditolak',
            'form_catatan_penanganan' => 'nullable|string|max:1000',
            'form_penanggung_jawab_id' => 'nullable|exists:users,id',
        ]);

        $complaint = Complaint::findOrFail($this->complaintId);
        $complaint->status = $this->form_status;
        $complaint->catatan_penanganan = $this->form_catatan_penanganan;
        $complaint->penanggung_jawab_id = $this->form_penanggung_jawab_id ?? Auth::id();

        if ($this->form_status === 'selesai' && ! $complaint->tanggal_selesai) {
            $complaint->tanggal_selesai = now();
        } elseif ($this->form_status !== 'selesai') {
            $complaint->tanggal_selesai = null;
        }

        $complaint->save();

        // Kirim notifikasi sistem ke warga pelapor
        NotificationService::notifyComplaintUpdated($complaint);

        Notification::make()
            ->title('Status Pengaduan Diperbarui')
            ->body("Pengaduan berhasil diubah statusnya menjadi '{$complaint->status}'.")
            ->success()
            ->send();

        $this->closeHandling();
    }

    public function deleteComplaint(int $id): void
    {
        $item = Complaint::findOrFail($id);
        $item->delete();

        Notification::make()
            ->title('Pengaduan Berhasil Dihapus')
            ->body('Data pengaduan telah dipindahkan ke arsip/sampah.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'pengaduan-warga-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = Complaint::with(['pelapor', 'penanggungJawab'])->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->statusFilter, ['diterima', 'diproses', 'selesai', 'ditolak'])) {
            $query->where('status', $this->statusFilter);
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Status',
                'Kategori',
                'Judul Pengaduan',
                'Deskripsi',
                'Lokasi Kejadian',
                'Pelapor',
                'Anonim',
                'Penanggung Jawab',
                'Catatan Penanganan',
                'Tanggal Masuk',
                'Tanggal Selesai',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id,
                    strtoupper($item->status),
                    strtoupper($item->kategori),
                    $item->judul,
                    $item->deskripsi,
                    $item->lokasi ?? '-',
                    $item->is_anonim ? 'Anonim (Disamarkan)' : ($item->pelapor ? $item->pelapor->name : 'Warga RT'),
                    $item->is_anonim ? 'YA' : 'TIDAK',
                    $item->penanggungJawab ? $item->penanggungJawab->name : '-',
                    $item->catatan_penanganan ?? '-',
                    $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
                    $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-',
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
        $stats = Complaint::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as diterima,
            SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
        ")->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiDiterima = (int) ($stats->diterima ?? 0);
        $kpiDiproses = (int) ($stats->diproses ?? 0);
        $kpiSelesai = (int) ($stats->selesai ?? 0);
        $kpiDitolak = (int) ($stats->ditolak ?? 0);

        $query = Complaint::with(['pelapor', 'penanggungJawab'])->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->statusFilter, ['diterima', 'diproses', 'selesai', 'ditolak'])) {
            $query->where('status', $this->statusFilter);
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        $complaints = $query->paginate($this->perPage);
        $pengurusList = User::select('id', 'name')->whereHas('roles', function ($q) {
            $q->whereIn('name', ['super_admin', 'ketua_rt', 'sekretaris', 'bendahara', 'seksi_keamanan', 'seksi_kebersihan', 'pengurus_rt']);
        })->get();

        if ($pengurusList->isEmpty()) {
            $pengurusList = User::select('id', 'name')->take(10)->get();
        }

        return [
            'complaints' => $complaints,
            'pengurusList' => $pengurusList,
            'kpiDiterima' => $kpiDiterima,
            'kpiDiproses' => $kpiDiproses,
            'kpiSelesai' => $kpiSelesai,
            'kpiDitolak' => $kpiDitolak,
            'kpiTotal' => $kpiTotal,
            'countSemua' => $kpiTotal,
            'countDiterima' => $kpiDiterima,
            'countDiproses' => $kpiDiproses,
            'countSelesai' => $kpiSelesai,
            'countDitolak' => $kpiDitolak,
            'kategoriList' => ['infrastruktur', 'kebersihan', 'keamanan', 'kebisingan', 'lainnya'],
        ];
    }
}
