<?php

namespace App\Filament\Resources\MsmeProfiles\Pages;

use App\Filament\Resources\MsmeProfiles\MsmeProfileResource;
use App\Models\Household;
use App\Models\MsmeProfile;
use App\Services\ImageOptimizerService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListMsmeProfiles extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = MsmeProfileResource::class;

    protected string $view = 'filament.resources.msme-profiles.pages.list-msme-profiles';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'disetujui', 'pending', 'ditolak'

    #[Url]
    public string $kategoriFilter = ''; // '', 'kuliner', 'kerajinan', 'sembako', 'jasa', 'fashion', 'lainnya'

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?MsmeProfile $selectedMsme = null;

    // State Modal Moderasi / Review
    public bool $moderationModalOpen = false;
    public ?int $moderatingId = null;
    public ?MsmeProfile $moderatingMsme = null;
    public string $form_status_moderasi = 'disetujui';
    public string $form_catatan_moderasi = '';

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $msmeId = null;
    public ?int $form_household_id = null;
    public string $form_nama_usaha = '';
    public string $form_deskripsi = '';
    public string $form_produk = '';
    public string $form_kategori = 'kuliner';
    public string $form_alamat = '';
    public string $form_telepon = '';
    public string $form_whatsapp = '';
    public string $form_instagram = '';
    public string $form_tiktok = '';
    public string $form_shopee = '';
    public string $form_facebook = '';
    public string $form_grabfood = '';
    public mixed $form_foto = null;
    public ?string $current_foto_path = null;
    public bool $remove_foto = false;
    public bool $form_is_active = true;

    protected function authorizeAction(): void
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        if (! $user || (! $user->hasAnyRole(['super_admin', 'admin', 'sekretaris']) && ! $user->can('moderasi_umkm'))) {
            abort(403, 'Anda tidak memiliki hak akses untuk aksi ini.');
        }
    }

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

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->kategoriFilter = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
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
        $this->selectedMsme = MsmeProfile::with('household.headOfHousehold')->find($id);
        if ($this->selectedMsme) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedMsme = null;
    }

    public function openModeration(int $id): void
    {
        $this->moderatingMsme = MsmeProfile::with('household.headOfHousehold')->findOrFail($id);
        $this->moderatingId = $this->moderatingMsme->id;
        $this->form_status_moderasi = in_array($this->moderatingMsme->status_moderasi, ['disetujui', 'pending', 'ditolak']) ? $this->moderatingMsme->status_moderasi : 'disetujui';
        $this->form_catatan_moderasi = $this->moderatingMsme->catatan_moderasi ?? '';
        $this->moderationModalOpen = true;
    }

    public function closeModeration(): void
    {
        $this->moderationModalOpen = false;
        $this->moderatingId = null;
        $this->moderatingMsme = null;
        $this->form_catatan_moderasi = '';
        $this->resetValidation();
    }

    public function approveMsme(): void
    {
        $this->authorizeAction();

        if (! $this->moderatingId) {
            return;
        }

        $msme = MsmeProfile::findOrFail($this->moderatingId);
        $msme->status_moderasi = 'disetujui';
        $msme->is_active = true;
        if (filled($this->form_catatan_moderasi)) {
            $msme->catatan_moderasi = trim($this->form_catatan_moderasi);
        }
        $msme->save();

        Notification::make()
            ->title('Pendaftaran UMKM Disetujui')
            ->body("Usaha \"{$msme->nama_usaha}\" telah disetujui dan aktif di katalog UMKM warga.")
            ->success()
            ->send();

        $this->closeModeration();
    }

    public function rejectMsme(): void
    {
        $this->authorizeAction();

        if (! $this->moderatingId) {
            return;
        }

        $this->validate([
            'form_catatan_moderasi' => 'required|string|min:5|max:500',
        ], [
            'form_catatan_moderasi.required' => 'Mohon sertakan alasan penolakan agar warga mengetahui kekurangan pengajuannya.',
            'form_catatan_moderasi.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $msme = MsmeProfile::findOrFail($this->moderatingId);
        $msme->status_moderasi = 'ditolak';
        $msme->is_active = false;
        $msme->catatan_moderasi = trim($this->form_catatan_moderasi);
        $msme->save();

        Notification::make()
            ->title('Pengajuan UMKM Ditolak')
            ->body("Pengajuan usaha \"{$msme->nama_usaha}\" telah ditolak dengan catatan penolakan tersimpan.")
            ->warning()
            ->send();

        $this->closeModeration();
    }

    public function saveModeration(): void
    {
        $this->authorizeAction();

        $this->validate([
            'form_status_moderasi' => 'required|in:disetujui,pending,ditolak',
            'form_catatan_moderasi' => 'nullable|string|max:500',
        ]);

        $msme = MsmeProfile::findOrFail($this->moderatingId);
        $msme->status_moderasi = $this->form_status_moderasi;
        $msme->catatan_moderasi = $this->form_catatan_moderasi;
        $msme->save();

        Notification::make()
            ->title('Status Moderasi Diperbarui')
            ->body("UMKM {$msme->nama_usaha} kini berstatus '{$msme->status_moderasi}'.")
            ->success()
            ->send();

        $this->closeModeration();
    }

    public function toggleActive(int $id): void
    {
        $this->authorizeAction();

        $msme = MsmeProfile::findOrFail($id);
        $msme->is_active = ! $msme->is_active;
        $msme->save();

        Notification::make()
            ->title($msme->is_active ? 'UMKM Diaktifkan' : 'UMKM Dinonaktifkan')
            ->body("Status tayang produk {$msme->nama_usaha} berhasil diubah.")
            ->success()
            ->send();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->msmeId = null;
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = MsmeProfile::findOrFail($id);
        $this->msmeId = $item->id;
        $this->isEditing = true;
        $this->form_household_id = $item->household_id;
        $this->form_nama_usaha = $item->nama_usaha;
        $this->form_deskripsi = $item->deskripsi ?? '';
        $this->form_produk = $item->produk ?? '';
        $this->form_kategori = $item->kategori ?? 'kuliner';
        $this->form_alamat = $item->alamat ?? '';
        $this->form_telepon = $item->telepon;
        $this->form_is_active = (bool) $item->is_active;

        $this->current_foto_path = $item->foto_path;
        $this->form_foto = null;
        $this->remove_foto = false;

        $links = is_array($item->social_links) ? $item->social_links : [];
        $this->form_whatsapp = $links['whatsapp'] ?? $item->whatsapp ?? '';
        $this->form_instagram = $links['instagram'] ?? '';
        $this->form_tiktok = $links['tiktok'] ?? '';
        $this->form_shopee = $links['shopee'] ?? '';
        $this->form_facebook = $links['facebook'] ?? '';
        $this->form_grabfood = $links['grabfood'] ?? '';

        $this->formModalOpen = true;
    }

    public function clearPhoto(): void
    {
        $this->form_foto = null;
        $this->remove_foto = true;
        $this->current_foto_path = null;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form_household_id = null;
        $this->form_nama_usaha = '';
        $this->form_deskripsi = '';
        $this->form_produk = '';
        $this->form_kategori = 'kuliner';
        $this->form_alamat = '';
        $this->form_telepon = '';
        $this->form_whatsapp = '';
        $this->form_instagram = '';
        $this->form_tiktok = '';
        $this->form_shopee = '';
        $this->form_facebook = '';
        $this->form_grabfood = '';
        $this->form_foto = null;
        $this->current_foto_path = null;
        $this->remove_foto = false;
        $this->form_is_active = true;
        $this->msmeId = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function saveMsme(): void
    {
        $this->authorizeAction();

        $rules = [
            'form_household_id' => 'required|exists:households,id',
            'form_nama_usaha' => 'required|string|max:255',
            'form_kategori' => 'required|string|max:100',
            'form_telepon' => 'required|string|max:50',
            'form_alamat' => 'nullable|string|max:255',
            'form_deskripsi' => 'nullable|string',
            'form_produk' => 'nullable|string',
            'form_whatsapp' => 'nullable|string|max:50',
            'form_instagram' => 'nullable|string|max:100',
            'form_tiktok' => 'nullable|string|max:100',
            'form_shopee' => 'nullable|string|max:255',
            'form_facebook' => 'nullable|string|max:255',
            'form_grabfood' => 'nullable|string|max:255',
        ];

        if ($this->form_foto) {
            $rules['form_foto'] = 'image|mimes:jpeg,png,jpg,webp|max:5120';
        }

        $this->validate($rules, [
            'form_household_id.required' => 'Keluarga pemilik UMKM wajib dipilih.',
            'form_nama_usaha.required' => 'Nama usaha UMKM wajib diisi.',
            'form_telepon.required' => 'Nomor kontak telepon/HP wajib diisi.',
            'form_foto.image' => 'File yang diunggah harus berupa citra gambar.',
            'form_foto.mimes' => 'Format gambar harus berupa JPG, PNG, atau WebP.',
            'form_foto.max' => 'Ukuran file foto maksimal 5 MB sebelum kompresi.',
        ]);

        $socialLinks = [];
        if (filled($this->form_whatsapp)) {
            $socialLinks['whatsapp'] = trim($this->form_whatsapp);
        }
        if (filled($this->form_instagram)) {
            $socialLinks['instagram'] = trim($this->form_instagram);
        }
        if (filled($this->form_tiktok)) {
            $socialLinks['tiktok'] = trim($this->form_tiktok);
        }
        if (filled($this->form_shopee)) {
            $socialLinks['shopee'] = trim($this->form_shopee);
        }
        if (filled($this->form_facebook)) {
            $socialLinks['facebook'] = trim($this->form_facebook);
        }
        if (filled($this->form_grabfood)) {
            $socialLinks['grabfood'] = trim($this->form_grabfood);
        }

        $payload = [
            'household_id' => $this->form_household_id,
            'nama_usaha' => $this->form_nama_usaha,
            'deskripsi' => $this->form_deskripsi,
            'produk' => $this->form_produk,
            'kategori' => $this->form_kategori,
            'alamat' => $this->form_alamat,
            'telepon' => $this->form_telepon,
            'whatsapp' => $this->form_whatsapp ?: $this->form_telepon,
            'social_links' => ! empty($socialLinks) ? $socialLinks : null,
            'is_active' => $this->form_is_active,
        ];

        // Optimasi & kompresi foto server-side WebP
        if ($this->form_foto) {
            $optimizer = app(ImageOptimizerService::class);
            $payload['foto_path'] = $optimizer->optimizeAndStore($this->form_foto, 'umkm');
        } elseif ($this->remove_foto && $this->isEditing) {
            if ($this->current_foto_path) {
                app(ImageOptimizerService::class)->deleteOldPhoto($this->current_foto_path);
            }
            $payload['foto_path'] = null;
        }

        if ($this->isEditing && $this->msmeId) {
            $msme = MsmeProfile::findOrFail($this->msmeId);
            $msme->update($payload);

            Notification::make()
                ->title('Profil UMKM Berhasil Diperbarui')
                ->body('Perubahan data katalog dan foto usaha telah disimpan.')
                ->success()
                ->send();
        } else {
            $payload['status_moderasi'] = 'disetujui'; // Admin input langsung disetujui
            MsmeProfile::create($payload);

            Notification::make()
                ->title('UMKM Baru Berhasil Didaftarkan')
                ->body('Usaha warga telah dimasukkan ke dalam Katalog UMKM RT 15.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteMsme(int $id): void
    {
        $this->authorizeAction();

        $item = MsmeProfile::findOrFail($id);
        if ($item->foto_path) {
            app(ImageOptimizerService::class)->deleteOldPhoto($item->foto_path);
        }
        $item->delete();

        Notification::make()
            ->title('UMKM Berhasil Dihapus')
            ->body('Data UMKM telah dipindahkan ke arsip/sampah.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'katalog-umkm-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = MsmeProfile::with('household.headOfHousehold')->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama_usaha) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(produk) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->statusFilter, ['disetujui', 'pending', 'ditolak'])) {
            $query->where('status_moderasi', $this->statusFilter);
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
                'Nama Usaha',
                'Kategori',
                'Produk Unggulan',
                'Pemilik (Kepala KK)',
                'Nomor KK',
                'Alamat Usaha',
                'No. Telepon / WA',
                'Instagram',
                'TikTok',
                'Shopee',
                'Facebook',
                'GrabFood',
                'Status Moderasi',
                'Status Aktif',
            ]);

            foreach ($records as $item) {
                $links = is_array($item->social_links) ? $item->social_links : [];
                fputcsv($handle, [
                    $item->id,
                    $item->nama_usaha,
                    strtoupper($item->kategori ?? 'Lainnya'),
                    $item->produk ?? '-',
                    $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama : 'Warga RT 15',
                    $item->household ? "'{$item->household->no_kk}'" : '-',
                    $item->alamat ?? ($item->household ? $item->household->alamat : '-'),
                    $item->telepon,
                    $links['instagram'] ?? '-',
                    $links['tiktok'] ?? '-',
                    $links['shopee'] ?? '-',
                    $links['facebook'] ?? '-',
                    $links['grabfood'] ?? '-',
                    strtoupper($item->status_moderasi),
                    $item->is_active ? 'AKTIF' : 'NONAKTIF',
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
        $stats = MsmeProfile::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status_moderasi = 'disetujui' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status_moderasi = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status_moderasi = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
            SUM(CASE WHEN kategori = 'kuliner' THEN 1 ELSE 0 END) as kuliner
        ")->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiApproved = (int) ($stats->approved ?? 0);
        $kpiPending = (int) ($stats->pending ?? 0);
        $countDitolak = (int) ($stats->ditolak ?? 0);
        $kpiKuliner = (int) ($stats->kuliner ?? 0);

        $query = MsmeProfile::with('household.headOfHousehold')->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama_usaha) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(produk) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->statusFilter, ['disetujui', 'pending', 'ditolak'])) {
            $query->where('status_moderasi', $this->statusFilter);
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        $msmes = $query->paginate($this->perPage);

        $householdList = Household::with('headOfHousehold')->orderBy('id')->get();

        return [
            'msmes' => $msmes,
            'householdList' => $householdList,
            'kpiTotal' => $kpiTotal,
            'kpiApproved' => $kpiApproved,
            'kpiPending' => $kpiPending,
            'kpiKuliner' => $kpiKuliner,
            'countSemua' => $kpiTotal,
            'countApproved' => $kpiApproved,
            'countPending' => $kpiPending,
            'countDitolak' => $countDitolak,
            'kategoriList' => ['kuliner', 'kerajinan', 'sembako', 'jasa', 'fashion', 'pertanian', 'lainnya'],
        ];
    }
}
