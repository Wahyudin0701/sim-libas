<?php

namespace App\Filament\Resources\RtSettings\Pages;

use App\Filament\Resources\RtSettings\RtSettingResource;
use App\Models\RtSetting;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class ListRtSettings extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = RtSettingResource::class;

    protected string $view = 'filament.resources.rt-settings.pages.list-rt-settings';

    #[Url]
    public string $search = '';

    public int $perPage = 10;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?RtSetting $selectedSetting = null;

    // State Modal Form (Ubah Pengaturan)
    public bool $formModalOpen = false;
    public ?int $settingId = null;
    public string $nama_rt = '';
    public string $nama_ketua_rt = '';
    public string $alamat = '';
    public string $kelurahan = '';
    public string $kecamatan = '';
    public string $kota = '';
    public string $provinsi = '';
    public string $kode_pos = '';
    public string $telepon = '';
    public string $email = '';
    public string $format_nomor_surat = '';
    public $tanda_tangan = null;
    public $stempel = null;
    public ?string $existing_tanda_tangan = null;
    public ?string $existing_stempel = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }

        // Pastikan record singleton selalu ada minimal 1 baris
        RtSetting::instance();
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

    protected function getViewData(): array
    {
        $primarySetting = RtSetting::instance();
        $totalSettings = RtSetting::count();

        $query = RtSetting::query()->latest('id');

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama_rt', 'like', "%{$term}%")
                  ->orWhere('nama_ketua_rt', 'like', "%{$term}%")
                  ->orWhere('alamat', 'like', "%{$term}%")
                  ->orWhere('kelurahan', 'like', "%{$term}%")
                  ->orWhere('kecamatan', 'like', "%{$term}%")
                  ->orWhere('kota', 'like', "%{$term}%")
                  ->orWhere('provinsi', 'like', "%{$term}%")
                  ->orWhere('telepon', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('format_nomor_surat', 'like', "%{$term}%");
            });
        }

        $settings = $query->paginate($this->perPage);

        return [
            'settings' => $settings,
            'primarySetting' => $primarySetting,
            'totalSettings' => $totalSettings,
            'search' => $this->search,
        ];
    }

    public function openDetail(?int $id = null): void
    {
        $id = $id ?: (RtSetting::first()?->id ?? 1);
        $this->selectedSetting = RtSetting::find($id);
        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedSetting = null;
    }

    public function openEdit(?int $id = null): void
    {
        $this->resetValidation();
        $setting = $id ? RtSetting::findOrFail($id) : RtSetting::instance();

        $this->settingId = $setting->id;
        $this->nama_rt = (string) ($setting->nama_rt ?? '');
        $this->nama_ketua_rt = (string) ($setting->nama_ketua_rt ?? '');
        $this->alamat = (string) ($setting->alamat ?? '');
        $this->kelurahan = (string) ($setting->kelurahan ?? '');
        $this->kecamatan = (string) ($setting->kecamatan ?? '');
        $this->kota = (string) ($setting->kota ?? '');
        $this->provinsi = (string) ($setting->provinsi ?? '');
        $this->kode_pos = (string) ($setting->kode_pos ?? '');
        $this->telepon = (string) ($setting->telepon ?? '');
        $this->email = (string) ($setting->email ?? '');
        $this->format_nomor_surat = (string) ($setting->format_nomor_surat ?? '{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}');
        $this->existing_tanda_tangan = $setting->tanda_tangan_path;
        $this->existing_stempel = $setting->stempel_path;
        $this->tanda_tangan = null;
        $this->stempel = null;

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->tanda_tangan = null;
        $this->stempel = null;
        $this->resetValidation();
    }

    public function removeTtd(): void
    {
        if ($this->settingId) {
            $setting = RtSetting::find($this->settingId);
            if ($setting && $setting->tanda_tangan_path) {
                if (Storage::disk('public')->exists($setting->tanda_tangan_path)) {
                    Storage::disk('public')->delete($setting->tanda_tangan_path);
                }
                $setting->update(['tanda_tangan_path' => null]);
                $this->existing_tanda_tangan = null;

                Notification::make()
                    ->title('Tanda tangan digital berhasil dihapus')
                    ->success()
                    ->send();
            }
        }
    }

    public function removeStempel(): void
    {
        if ($this->settingId) {
            $setting = RtSetting::find($this->settingId);
            if ($setting && $setting->stempel_path) {
                if (Storage::disk('public')->exists($setting->stempel_path)) {
                    Storage::disk('public')->delete($setting->stempel_path);
                }
                $setting->update(['stempel_path' => null]);
                $this->existing_stempel = null;

                Notification::make()
                    ->title('Stempel digital berhasil dihapus')
                    ->success()
                    ->send();
            }
        }
    }

    public function saveSetting(): void
    {
        $this->validate([
            'nama_rt' => 'required|string|max:150',
            'nama_ketua_rt' => 'required|string|max:150',
            'alamat' => 'required|string|max:500',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'format_nomor_surat' => 'required|string|max:200',
            'tanda_tangan' => 'nullable|image|max:2048',
            'stempel' => 'nullable|image|max:2048',
        ], [
            'nama_rt.required' => 'Nama RT wajib diisi.',
            'nama_ketua_rt.required' => 'Nama Ketua RT wajib diisi.',
            'alamat.required' => 'Alamat sekretariat wajib diisi.',
            'kelurahan.required' => 'Kelurahan wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kota.required' => 'Kota / Kabupaten wajib diisi.',
            'provinsi.required' => 'Provinsi wajib diisi.',
            'format_nomor_surat.required' => 'Format nomor surat wajib ditentukan.',
            'tanda_tangan.image' => 'Berkas tanda tangan harus berupa gambar (PNG/JPG/WEBP).',
            'tanda_tangan.max' => 'Ukuran berkas tanda tangan maksimal 2MB.',
            'stempel.image' => 'Berkas stempel harus berupa gambar (PNG/JPG/WEBP).',
            'stempel.max' => 'Ukuran berkas stempel maksimal 2MB.',
        ]);

        $setting = $this->settingId ? RtSetting::findOrFail($this->settingId) : RtSetting::instance();

        $ttdPath = $setting->tanda_tangan_path;
        if ($this->tanda_tangan) {
            $ttdPath = $this->tanda_tangan->store('settings', 'public');
        }

        $stempelPath = $setting->stempel_path;
        if ($this->stempel) {
            $stempelPath = $this->stempel->store('settings', 'public');
        }

        $setting->update([
            'nama_rt' => $this->nama_rt,
            'nama_ketua_rt' => $this->nama_ketua_rt,
            'alamat' => $this->alamat,
            'kelurahan' => $this->kelurahan,
            'kecamatan' => $this->kecamatan,
            'kota' => $this->kota,
            'provinsi' => $this->provinsi,
            'kode_pos' => $this->kode_pos,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'format_nomor_surat' => $this->format_nomor_surat,
            'tanda_tangan_path' => $ttdPath,
            'stempel_path' => $stempelPath,
        ]);

        $this->existing_tanda_tangan = $ttdPath;
        $this->existing_stempel = $stempelPath;
        $this->tanda_tangan = null;
        $this->stempel = null;
        $this->formModalOpen = false;

        Notification::make()
            ->title('Pengaturan Sistem berhasil disimpan')
            ->body('Konfigurasi identitas wilayah RT 15 dan administrasi surat telah diperbarui.')
            ->success()
            ->send();
    }
}
