<?php

namespace App\Filament\Resources\Households\Pages;

use App\Filament\Resources\Households\HouseholdResource;
use App\Filament\Resources\Residents\Traits\HandlesEditResidentModal;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ViewHousehold extends ViewRecord
{
    use WithFileUploads, HandlesEditResidentModal;

    protected static string $resource = HouseholdResource::class;

    protected string $view = 'filament.resources.households.pages.view-household';

    // 1. Popup Edit Dokumen KK
    public bool $editDokumenModalOpen = false;
    public string $dokumen_no_kk = '';
    public string $dokumen_alamat = '';
    public string $dokumen_rt_rw = 'RT 15 / RW 04';
    public string $dokumen_status_hunian = 'tetap';
    public ?string $existing_foto_kk = null;
    public ?string $existing_foto_kk_url = null;
    public bool $delete_foto_kk = false;
    public $dokumen_foto_kk = null;

    // 2. Popup Edit Kepala Keluarga
    public bool $editKepalaModalOpen = false;
    public ?int $kepala_id = null;
    public string $kepala_nama = '';
    public string $kepala_nik = '';
    public string $kepala_telepon = '';
    public string $kepala_email = '';
    public string $kepala_password = '';
    public string $kepala_tempat_lahir = 'Jambi';
    public ?string $kepala_tanggal_lahir = null;
    public string $kepala_agama = 'Islam';
    public string $kepala_pekerjaan = '';
    public string $kepala_pendidikan_terakhir = 'SMA / SMK / Sederajat';

    // 3. Popup Edit Akun Portal
    public bool $editAkunModalOpen = false;
    public string $akun_name = '';
    public string $akun_email = '';
    public string $akun_phone = '';
    public string $akun_status = 'aktif';
    public string $akun_password = '';

    public function mount(int | string $record): void
    {
        parent::mount($record);
        $this->loadDokumenData();
        $this->loadKepalaData();
        $this->loadAkunData();
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

    // ==========================================
    // ACTION DOKUMEN KK
    // ==========================================
    public function loadDokumenData(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();
        $this->dokumen_no_kk = (string) $record->no_kk;
        $this->dokumen_alamat = (string) $record->alamat;
        $this->dokumen_rt_rw = (string) ($record->rt_rw ?? 'RT 15 / RW 04');
        $this->dokumen_status_hunian = (string) ($record->status_hunian ?? 'tetap');
        $this->existing_foto_kk = $record->foto_kk;
        $this->existing_foto_kk_url = $record->foto_kk_url;
        $this->delete_foto_kk = false;
        $this->dokumen_foto_kk = null;
    }

    public function openEditDokumen(): void
    {
        $this->loadDokumenData();
        $this->resetValidation();
        $this->editDokumenModalOpen = true;
        $this->dispatch('open-dokumen-modal');
    }

    public function closeEditDokumen(): void
    {
        $this->editDokumenModalOpen = false;
        $this->resetValidation();
        $this->loadDokumenData();
        $this->dispatch('close-dokumen-modal');
    }

    public function removeExistingFotoKk(): void
    {
        $this->existing_foto_kk = null;
        $this->existing_foto_kk_url = null;
        $this->delete_foto_kk = true;
    }

    public function removeDokumenFotoKk(): void
    {
        $this->dokumen_foto_kk = null;
    }

    public function saveDokumen(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();

        $this->validate([
            'dokumen_no_kk' => ['required', 'digits:16'],
            'dokumen_alamat' => ['required', 'string', 'max:255'],
            'dokumen_rt_rw' => ['nullable', 'string', 'max:50'],
            'dokumen_status_hunian' => ['required', 'in:tetap,kontrak,kos'],
            'dokumen_foto_kk' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:10240'],
        ], [
            'dokumen_no_kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'dokumen_no_kk.digits' => 'Nomor KK harus 16 digit angka.',
            'dokumen_alamat.required' => 'Alamat rumah wajib diisi.',
            'dokumen_status_hunian.required' => 'Status hunian wajib dipilih.',
            'dokumen_foto_kk.file' => 'Berkas KK harus berupa file yang valid.',
            'dokumen_foto_kk.mimes' => 'Format berkas KK harus JPG, PNG, WEBP, atau PDF.',
            'dokumen_foto_kk.max' => 'Ukuran berkas KK maksimal 10 MB.',
        ]);

        $kkHash = hash('sha256', $this->dokumen_no_kk);
        if (Household::withTrashed()->where('no_kk_hash', $kkHash)->where('id', '!=', $record->id)->exists()) {
            $this->addError('dokumen_no_kk', 'Nomor KK ini sudah digunakan oleh data KK lain (termasuk pada data arsip/terhapus).');
            return;
        }

        $fotoPath = $record->foto_kk;
        if ($this->dokumen_foto_kk && is_object($this->dokumen_foto_kk) && method_exists($this->dokumen_foto_kk, 'store')) {
            if ($record->foto_kk) {
                Storage::disk('public')->delete($record->foto_kk);
            }
            $fotoPath = $this->dokumen_foto_kk->store('households/foto_kk', 'public');
        } elseif ($this->delete_foto_kk) {
            if ($record->foto_kk) {
                Storage::disk('public')->delete($record->foto_kk);
            }
            $fotoPath = null;
        }

        $record->update([
            'no_kk' => $this->dokumen_no_kk,
            'no_kk_hash' => $kkHash,
            'alamat' => $this->dokumen_alamat,
            'rt_rw' => $this->dokumen_rt_rw,
            'status_hunian' => $this->dokumen_status_hunian,
            'foto_kk' => $fotoPath,
        ]);

        $this->record = $record->fresh();
        $this->loadDokumenData();
        $this->editDokumenModalOpen = false;

        $this->dispatch('close-dokumen-modal');

        Notification::make()
            ->title('Dokumen Kartu Keluarga Diperbarui')
            ->body('Data nomor KK, alamat, dan dokumen fisik berhasil disimpan.')
            ->success()
            ->send();
    }

    // ==========================================
    // ACTION KEPALA KELUARGA
    // ==========================================
    public function loadKepalaData(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();
        $kepala = $record->kepalaKeluarga ?? $record->residents->firstWhere('is_kepala_keluarga', true) ?? $record->residents->first();

        if ($kepala) {
            $this->kepala_id = $kepala->id;
            $this->kepala_nama = (string) $kepala->nama;
            $this->kepala_nik = (string) ($kepala->nik ?? '');
            $this->kepala_telepon = (string) ($kepala->telepon ?? '');
            $this->kepala_email = (string) ($kepala->email ?? '');
            $this->kepala_password = '';
            $this->kepala_tempat_lahir = (string) ($kepala->tempat_lahir ?? 'Jambi');
            $this->kepala_tanggal_lahir = $kepala->tanggal_lahir?->format('Y-m-d');
            $this->kepala_agama = (string) ($kepala->agama ?? 'Islam');
            $this->kepala_pekerjaan = (string) ($kepala->pekerjaan ?? '');
            $this->kepala_pendidikan_terakhir = (string) ($kepala->pendidikan_terakhir ?? 'SMA / SMK / Sederajat');
        } else {
            $this->kepala_id = null;
            $this->kepala_nama = '';
            $this->kepala_nik = '';
            $this->kepala_telepon = '';
            $this->kepala_email = '';
            $this->kepala_password = '';
            $this->kepala_tempat_lahir = 'Jambi';
            $this->kepala_tanggal_lahir = null;
            $this->kepala_agama = 'Islam';
            $this->kepala_pekerjaan = '';
            $this->kepala_pendidikan_terakhir = 'SMA / SMK / Sederajat';
        }
    }

    public function openEditKepala(): void
    {
        $this->loadKepalaData();
        $this->resetValidation();
        $this->editKepalaModalOpen = true;
        $this->dispatch('open-kepala-modal');
    }

    public function closeEditKepala(): void
    {
        $this->editKepalaModalOpen = false;
        $this->resetValidation();
        $this->loadKepalaData();
        $this->dispatch('close-kepala-modal');
    }

    public function saveKepala(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();

        $this->validate([
            'kepala_nama' => ['required', 'string', 'max:100'],
            'kepala_nik' => ['required', 'digits:16'],
            'kepala_telepon' => ['nullable', 'string', 'max:30'],
            'kepala_email' => ['nullable', 'email', 'max:100'],
            'kepala_password' => ['nullable', 'string', 'min:6'],
            'kepala_tempat_lahir' => ['nullable', 'string', 'max:50'],
            'kepala_tanggal_lahir' => ['nullable', 'date'],
            'kepala_agama' => ['nullable', 'string', 'max:50'],
            'kepala_pekerjaan' => ['nullable', 'string', 'max:100'],
            'kepala_pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
        ], [
            'kepala_nama.required' => 'Nama Kepala Keluarga wajib diisi.',
            'kepala_nik.required' => 'NIK Kepala Keluarga wajib diisi.',
            'kepala_nik.digits' => 'NIK Kepala Keluarga harus 16 digit angka.',
            'kepala_password.min' => 'Kata sandi baru Kepala Keluarga minimal harus 6 karakter.',
        ]);

        $kepalaNikHash = hash('sha256', $this->kepala_nik);
        $q = Resident::withTrashed()->where('nik_hash', $kepalaNikHash);
        if ($this->kepala_id) {
            $q->where('id', '!=', $this->kepala_id);
        }
        if ($q->exists()) {
            $this->addError('kepala_nik', 'NIK Kepala Keluarga sudah terdaftar di sistem (termasuk pada data arsip/terhapus).');
            return;
        }

        $data = [
            'nama' => $this->kepala_nama,
            'nik' => $this->kepala_nik,
            'nik_hash' => $kepalaNikHash,
            'tempat_lahir' => $this->kepala_tempat_lahir ?: 'Jambi',
            'tanggal_lahir' => $this->kepala_tanggal_lahir ?: null,
            'jenis_kelamin' => 'L',
            'agama' => $this->kepala_agama ?: 'Islam',
            'pekerjaan' => $this->kepala_pekerjaan ?: null,
            'pendidikan_terakhir' => $this->kepala_pendidikan_terakhir ?: null,
            'status_perkawinan' => 'Kawin',
            'telepon' => $this->kepala_telepon ?: null,
            'email' => $this->kepala_email ?: null,
            'hubungan_keluarga' => 'kepala_keluarga',
            'is_kepala_keluarga' => true,
            'household_id' => $record->id,
        ];

        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $data) {
            if ($this->kepala_id) {
                $kepala = Resident::find($this->kepala_id);
                if ($kepala) {
                    $kepala->update($data);
                }
            } else {
                $kepala = Resident::create($data);
                $this->kepala_id = $kepala->id;
            }

            // Pastikan hanya satu kepala keluarga yang aktif di KK ini
            Resident::where('household_id', $record->id)
                ->where('id', '!=', $this->kepala_id)
                ->where('is_kepala_keluarga', true)
                ->update(['is_kepala_keluarga' => false]);
        });

        if ($record->user) {
            $userUpdates = [
                'name' => $this->kepala_nama,
                'phone' => $this->kepala_telepon ?: $record->user->phone,
            ];
            if (!empty($this->kepala_email)) {
                $userUpdates['email'] = $this->kepala_email;
            }
            if (!empty($this->kepala_password)) {
                $userUpdates['password'] = Hash::make($this->kepala_password);
            }
            $record->user->update($userUpdates);
        }

        $this->record = $record->fresh();
        $this->loadKepalaData();
        $this->editKepalaModalOpen = false;
        $this->dispatch('close-kepala-modal');

        Notification::make()
            ->title('Data Kepala Keluarga Diperbarui')
            ->body("Identitas Kepala Keluarga {$this->kepala_nama} berhasil diperbarui.")
            ->success()
            ->send();
    }

    // ==========================================
    // ACTION AKUN PORTAL WARGA
    // ==========================================
    public function loadAkunData(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();

        if ($record->user) {
            $this->akun_name = (string) $record->user->name;
            $this->akun_email = (string) $record->user->email;
            $this->akun_phone = (string) ($record->user->phone ?? '');
            $this->akun_status = (string) ($record->user->status ?? 'aktif');
            $this->akun_password = '';
        } else {
            $this->akun_name = (string) ($record->kepalaKeluarga?->nama ?? 'Warga ' . $record->id_kk);
            $this->akun_email = $record->kepalaKeluarga?->nik ? $record->kepalaKeluarga->nik . '@warga.simlibas.com' : '';
            $this->akun_phone = (string) ($record->kepalaKeluarga?->telepon ?? '');
            $this->akun_status = 'aktif';
            $this->akun_password = '';
        }
    }

    public function openEditAkun(): void
    {
        $this->loadAkunData();
        $this->resetValidation();
        $this->editAkunModalOpen = true;
        $this->dispatch('open-akun-modal');
    }

    public function closeEditAkun(): void
    {
        $this->editAkunModalOpen = false;
        $this->resetValidation();
        $this->loadAkunData();
        $this->dispatch('close-akun-modal');
    }

    public function saveAkun(): void
    {
        /** @var Household $record */
        $record = $this->getRecord();

        $userId = $record->user_id;

        $this->validate([
            'akun_name' => ['required', 'string', 'max:100'],
            'akun_email' => ['required', 'email', 'max:100', 'unique:users,email' . ($userId ? ",{$userId}" : '')],
            'akun_phone' => ['nullable', 'string', 'max:30'],
            'akun_status' => ['required', 'in:aktif,nonaktif'],
            'akun_password' => ['nullable', 'string', 'min:6'],
        ], [
            'akun_name.required' => 'Nama akun portal wajib diisi.',
            'akun_email.required' => 'Email login portal wajib diisi.',
            'akun_email.email' => 'Format email tidak valid.',
            'akun_email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'akun_password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($record->user) {
            $updates = [
                'name' => $this->akun_name,
                'email' => $this->akun_email,
                'phone' => $this->akun_phone ?: null,
                'status' => $this->akun_status,
            ];
            if (!empty($this->akun_password)) {
                $updates['password'] = Hash::make($this->akun_password);
            }
            $record->user->update($updates);
        } else {
            $user = User::create([
                'name' => $this->akun_name,
                'email' => $this->akun_email,
                'phone' => $this->akun_phone ?: null,
                'password' => Hash::make($this->akun_password ?: 'password'),
                'status' => $this->akun_status,
            ]);
            if (!$user->hasRole('warga')) {
                $user->assignRole('warga');
            }
            $record->update(['user_id' => $user->id]);
        }

        $this->record = $record->fresh();
        $this->loadAkunData();
        $this->editAkunModalOpen = false;
        $this->dispatch('close-akun-modal');

        Notification::make()
            ->title('Akun Portal Warga Diperbarui')
            ->body("Akun login portal warga berhasil disimpan.")
            ->success()
            ->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        /** @var Household $record */
        $record = $this->getRecord();
        $record->loadMissing([
            'kepalaKeluarga',
            'residents' => function ($query) {
                $query->orderByRaw("CASE 
                    WHEN is_kepala_keluarga = 1 OR hubungan_keluarga = 'kepala_keluarga' THEN 1 
                    WHEN hubungan_keluarga = 'istri' THEN 2 
                    WHEN hubungan_keluarga = 'anak' THEN 3 
                    ELSE 4 
                END, tanggal_lahir ASC");
            },
            'user',
            'invoices',
            'wasteWallet',
            'msmeProfile',
        ]);

        return [
            'household' => $record,
            'kepala' => $record->kepalaKeluarga,
            'residents' => $record->residents,
        ];
    }
}
