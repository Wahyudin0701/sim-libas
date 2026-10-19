<?php

namespace App\Filament\Resources\Residents\Traits;

use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

trait HandlesEditResidentModal
{
    // State Modal Edit Warga
    public bool $editResidentModalOpen = false;
    public ?int $edit_resident_id = null;
    public string $edit_id_warga = '';
    public string $edit_nama = '';
    public string $edit_nik = '';
    public string $edit_tempat_lahir = 'Jambi';
    public ?string $edit_tanggal_lahir = null;
    public string $edit_jenis_kelamin = 'L';
    public string $edit_agama = 'Islam';
    public string $edit_pendidikan_terakhir = '';
    public string $edit_pekerjaan = '';
    public string $edit_status_perkawinan = 'Kawin';
    public string $edit_hubungan_keluarga = 'anak';
    public string $edit_status_kependudukan = 'aktif';
    public string $edit_telepon = '';
    public string $edit_email = '';
    public string $edit_kategori_warga = 'memiliki_kk';
    public ?int $edit_household_id = null;
    public string $edit_status_tempat_tinggal = '';
    public string $edit_alamat_domisili = '';
    public string $edit_password = '';
    public bool $has_user_account = false;
    public string $user_account_email = '';

    // State Modal Detail Warga
    public bool $detailResidentModalOpen = false;
    public ?Resident $detailResident = null;

    /**
     * Membuka modal detail warga dengan data segar dari database.
     */
    public function openDetailResident(int $id): void
    {
        $this->detailResident = Resident::with(['household.kepalaKeluarga'])->findOrFail($id);
        $this->detailResidentModalOpen = true;
        $this->dispatch('open-detail-resident-modal');
    }

    /**
     * Menutup modal detail warga.
     */
    public function closeDetailResident(): void
    {
        $this->detailResidentModalOpen = false;
        $this->detailResident = null;
        $this->dispatch('close-detail-resident-modal');
    }

    /**
     * Mengambil daftar KK untuk pilihan dropdown edit modal secara efisien.
     */
    public function getHouseholdsListProperty(): Collection
    {
        return Household::with('kepalaKeluarga')->orderBy('id_kk')->get();
    }

    /**
     * Membuka modal edit warga dengan data segar dari database.
     */
    public function openEditResident(int $id): void
    {
        $resident = Resident::with(['household', 'user'])->findOrFail($id);

        $this->edit_resident_id = $resident->id;
        $this->edit_id_warga = (string) ($resident->id_warga ?? '');
        $this->edit_nama = (string) $resident->nama;
        $this->edit_nik = (string) ($resident->nik ?? '');
        $this->edit_tempat_lahir = (string) ($resident->tempat_lahir ?? 'Jambi');
        $this->edit_tanggal_lahir = $resident->tanggal_lahir?->format('Y-m-d');
        $this->edit_jenis_kelamin = (string) ($resident->jenis_kelamin ?? 'L');
        $this->edit_agama = (string) ($resident->agama ?? 'Islam');
        $this->edit_pendidikan_terakhir = (string) ($resident->pendidikan_terakhir ?? '');
        $this->edit_pekerjaan = (string) ($resident->pekerjaan ?? '');
        $this->edit_status_perkawinan = (string) ($resident->status_perkawinan ?? 'Kawin');
        $this->edit_hubungan_keluarga = (string) ($resident->hubungan_keluarga ?? 'anak');
        $this->edit_status_kependudukan = (string) ($resident->status_kependudukan ?? 'aktif');
        $this->edit_telepon = (string) ($resident->telepon ?? '');
        $this->edit_email = (string) ($resident->email ?? '');
        $this->edit_kategori_warga = (string) ($resident->kategori_warga ?? ($resident->household_id ? 'memiliki_kk' : 'tanpa_kk'));
        $this->edit_household_id = $resident->household_id;
        $this->edit_status_tempat_tinggal = (string) ($resident->status_tempat_tinggal ?? '');
        $this->edit_alamat_domisili = (string) ($resident->alamat_domisili ?? '');
        $this->edit_password = '';
        $this->has_user_account = (bool) $resident->user;
        $this->user_account_email = (string) ($resident->user?->email ?? ($resident->email ?: ''));

        $this->resetValidation();
        $this->editResidentModalOpen = true;
        $this->dispatch('open-edit-resident-modal');
    }

    /**
     * Menutup modal edit warga dan mereset validasi.
     */
    public function closeEditResident(): void
    {
        $this->editResidentModalOpen = false;
        $this->edit_password = '';
        $this->resetValidation();
        $this->dispatch('close-edit-resident-modal');
    }

    /**
     * Set kata sandi cepat ke nilai bawaan ('password')
     */
    public function resetPasswordToDefault(): void
    {
        $this->edit_password = 'password';
        Notification::make()
            ->title('Kata Sandi Diatur ke Default')
            ->body('Kolom kata sandi diisi dengan "password". Klik tombol Simpan Perubahan untuk mengonfirmasi.')
            ->info()
            ->send();
    }

    /**
     * Menyimpan perubahan data warga dengan validasi integritas ketat.
     */
    public function saveResident(): void
    {
        $resident = Resident::findOrFail($this->edit_resident_id);

        $this->validate([
            'edit_nama' => ['required', 'string', 'max:100'],
            'edit_nik' => ['required', 'digits:16'],
            'edit_tempat_lahir' => ['nullable', 'string', 'max:50'],
            'edit_tanggal_lahir' => ['required', 'date'],
            'edit_jenis_kelamin' => ['required', 'in:L,P'],
            'edit_agama' => ['required', 'string', 'max:50'],
            'edit_pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'edit_pekerjaan' => ['nullable', 'string', 'max:100'],
            'edit_status_perkawinan' => ['nullable', 'string', 'max:50'],
            'edit_hubungan_keluarga' => ['required', 'string', 'max:50'],
            'edit_status_kependudukan' => ['required', 'in:aktif,pindah,meninggal,lahir_baru'],
            'edit_telepon' => ['nullable', 'string', 'max:30'],
            'edit_email' => ['nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($resident->user_id)],
            'edit_kategori_warga' => ['required', 'in:memiliki_kk,tanpa_kk'],
            'edit_household_id' => ['nullable', 'exists:households,id'],
            'edit_status_tempat_tinggal' => ['nullable', 'string', 'max:50'],
            'edit_alamat_domisili' => ['nullable', 'string', 'max:255'],
        ], [
            'edit_nama.required' => 'Nama lengkap wajib diisi.',
            'edit_nik.required' => 'NIK wajib diisi 16 digit angka.',
            'edit_nik.digits' => 'NIK harus berupa 16 digit angka.',
            'edit_tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'edit_jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'edit_agama.required' => 'Agama wajib dipilih.',
            'edit_hubungan_keluarga.required' => 'Hubungan keluarga wajib dipilih.',
            'edit_status_kependudukan.required' => 'Status kependudukan wajib dipilih.',
            'edit_email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        // Validasi NIK unik via SHA-256 hash (termasuk cek soft-deleted records)
        $nikHash = hash('sha256', $this->edit_nik);
        $duplicate = Resident::withTrashed()
            ->where('nik_hash', $nikHash)
            ->where('id', '!=', $resident->id)
            ->exists();

        if ($duplicate) {
            $this->addError('edit_nik', 'NIK ini sudah terdaftar pada data warga lain (termasuk arsip/terhapus).');
            return;
        }

        if (!empty($this->edit_password)) {
            if (strlen($this->edit_password) < 6) {
                $this->addError('edit_password', 'Kata sandi baru minimal harus 6 karakter.');
                return;
            }
        }

        $isKepala = ($this->edit_kategori_warga === 'memiliki_kk' && $this->edit_hubungan_keluarga === 'kepala_keluarga');
        $targetHouseholdId = $this->edit_kategori_warga === 'memiliki_kk' ? $this->edit_household_id : null;

        DB::transaction(function () use ($resident, $nikHash, $isKepala, $targetHouseholdId) {
            // Jika warga ini dijadikan kepala keluarga di sebuah KK, nonaktifkan flag kepala keluarga pada anggota lain
            if ($isKepala && $targetHouseholdId) {
                Resident::where('household_id', $targetHouseholdId)
                    ->where('id', '!=', $resident->id)
                    ->where('is_kepala_keluarga', true)
                    ->update([
                        'is_kepala_keluarga' => false,
                        'hubungan_keluarga' => 'lainnya',
                    ]);
            }

            // Update akun user terkait jika ada, atau buatkan baru jika belum memiliki akun
            $userEmail = !empty($this->edit_email) ? $this->edit_email : $this->edit_nik . '@warga.simlibas.com';
            $resolvedUserId = $resident->user_id;

            if ($resident->user) {
                $userUpdates = ['name' => $this->edit_nama];
                if (!empty($this->edit_email)) {
                    $userUpdates['email'] = $this->edit_email;
                }
                if (!empty($this->edit_telepon)) {
                    $userUpdates['phone'] = $this->edit_telepon;
                }
                if (!empty($this->edit_password)) {
                    $userUpdates['password'] = Hash::make($this->edit_password);
                }
                $resident->user->update($userUpdates);
            } else {
                $newUser = User::firstOrCreate(
                    ['email' => $userEmail],
                    [
                        'name' => $this->edit_nama,
                        'phone' => $this->edit_telepon ?: null,
                        'password' => Hash::make(!empty($this->edit_password) ? $this->edit_password : 'password'),
                        'status' => 'aktif',
                    ]
                );
                if (!empty($this->edit_password)) {
                    $newUser->update(['password' => Hash::make($this->edit_password)]);
                }
                if (!$newUser->hasRole('warga')) {
                    $newUser->assignRole('warga');
                }
                $resolvedUserId = $newUser->id;
            }

            $resident->update([
                'user_id' => $resolvedUserId,
                'nama' => $this->edit_nama,
                'nik' => $this->edit_nik,
                'nik_hash' => $nikHash,
                'tempat_lahir' => $this->edit_tempat_lahir ?: 'Jambi',
                'tanggal_lahir' => $this->edit_tanggal_lahir,
                'jenis_kelamin' => $this->edit_jenis_kelamin,
                'agama' => $this->edit_agama,
                'pendidikan_terakhir' => $this->edit_pendidikan_terakhir ?: null,
                'pekerjaan' => $this->edit_pekerjaan ?: null,
                'status_perkawinan' => $this->edit_status_perkawinan ?: null,
                'hubungan_keluarga' => $this->edit_kategori_warga === 'memiliki_kk' ? $this->edit_hubungan_keluarga : 'lainnya',
                'status_kependudukan' => $this->edit_status_kependudukan,
                'telepon' => $this->edit_telepon ?: null,
                'email' => $userEmail,
                'kategori_warga' => $this->edit_kategori_warga,
                'household_id' => $targetHouseholdId,
                'status_tempat_tinggal' => $this->edit_kategori_warga === 'tanpa_kk' ? ($this->edit_status_tempat_tinggal ?: null) : null,
                'alamat_domisili' => $this->edit_kategori_warga === 'tanpa_kk' ? ($this->edit_alamat_domisili ?: null) : null,
                'is_kepala_keluarga' => $isKepala,
            ]);
        });

        $this->editResidentModalOpen = false;
        $this->dispatch('close-edit-resident-modal');

        if (method_exists($this, 'getRecord')) {
            $this->record = $this->getRecord()->fresh();
            if (method_exists($this, 'loadKepalaData')) {
                $this->loadKepalaData();
            }
        }

        $bodyMsg = "Identitas dan data kependudukan warga {$this->edit_nama} berhasil disimpan.";
        if (!empty($this->edit_password)) {
            $bodyMsg .= " Kata sandi akun login warga berhasil diperbarui.";
        }

        Notification::make()
            ->title('Data Warga Berhasil Diperbarui')
            ->body($bodyMsg)
            ->success()
            ->send();
    }

    /**
     * Menghapus (soft-delete) data warga dengan proteksi integritas Kepala Keluarga.
     */
    public function deleteResident(int $id): void
    {
        $resident = Resident::findOrFail($id);

        // Proteksi: jangan hapus jika kepala keluarga dan masih memiliki anggota keluarga lain di KK
        if ($resident->is_kepala_keluarga && $resident->household_id) {
            $hasOtherMembers = Resident::where('household_id', $resident->household_id)
                ->where('id', '!=', $resident->id)
                ->exists();

            if ($hasOtherMembers) {
                Notification::make()
                    ->title('Penghapusan Warga Ditolak')
                    ->body("Warga {$resident->nama} adalah Kepala Keluarga dan masih memiliki anggota keluarga lain di KK ini. Tunjuk Kepala Keluarga baru terlebih dahulu sebelum menghapus warga ini.")
                    ->danger()
                    ->send();
                return;
            }
        }

        $nama = $resident->nama;
        $resident->delete();

        if ($this->detailResident && $this->detailResident->id === $id) {
            $this->closeDetailResident();
        }

        if (method_exists($this, 'getRecord')) {
            $this->record = $this->getRecord()->fresh();
            if (method_exists($this, 'loadKepalaData')) {
                $this->loadKepalaData();
            }
        }

        Notification::make()
            ->title('Data Warga Berhasil Dihapus')
            ->body("Data kependudukan warga {$nama} telah berhasil dipindahkan ke tempat sampah.")
            ->success()
            ->send();
    }
}
