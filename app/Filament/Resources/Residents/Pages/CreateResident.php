<?php

namespace App\Filament\Resources\Residents\Pages;

use App\Filament\Resources\Residents\ResidentResource;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateResident extends Page
{
    use WithFileUploads;

    protected static string $resource = ResidentResource::class;

    protected string $view = 'filament.resources.residents.pages.create-resident';

    // 1. Kategori & Alamat Domisili
    public string $kategori_warga = 'memiliki_kk'; // 'memiliki_kk' atau 'tanpa_kk'
    public ?string $household_id = null;
    public string $hubungan_keluarga = 'anak';
    public string $status_tempat_tinggal = 'kontrak'; // 'kontrak', 'kos', 'pendatang', 'lainnya'
    public string $alamat_domisili = '';

    // 2. Data Identitas Warga
    public string $nama = '';
    public string $nik = '';
    public string $tempat_lahir = 'Jambi';
    public ?string $tanggal_lahir = null;
    public string $jenis_kelamin = 'L'; // 'L', 'P'
    public string $agama = 'Islam';
    public string $pendidikan_terakhir = 'SMA / SMK / Sederajat';
    public string $pekerjaan = '';
    public string $status_perkawinan = 'Belum Kawin';
    public string $telepon = '';
    public string $email = '';
    public string $status_kependudukan = 'aktif';

    // 3. Foto Profil / Dokumen Warga
    public $foto_warga = null;

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function updatedKategoriWarga(string $value): void
    {
        if ($value === 'tanpa_kk') {
            $this->household_id = null;
            $this->hubungan_keluarga = 'lainnya';
        } else {
            $this->status_tempat_tinggal = 'kontrak';
            $this->alamat_domisili = '';
            if ($this->hubungan_keluarga === 'lainnya') {
                $this->hubungan_keluarga = 'anak';
            }
        }
    }

    public function updatedHubunganKeluarga(string $value): void
    {
        if ($value === 'istri') {
            $this->jenis_kelamin = 'P';
        } elseif ($value === 'kepala_keluarga') {
            $this->jenis_kelamin = 'L';
        }
    }

    public function removeFotoWarga(): void
    {
        $this->foto_warga = null;
    }

    /**
     * Mengambil daftar Kartu Keluarga untuk pilihan dropdown.
     */
    public function getHouseholdsProperty(): Collection
    {
        return Household::with('kepalaKeluarga')
            ->orderByRaw('CAST(SUBSTRING(id_kk, 3) AS UNSIGNED) ASC')
            ->get();
    }

    /**
     * Mengambil data Kartu Keluarga yang sedang dipilih.
     */
    public function getSelectedHouseholdProperty(): ?Household
    {
        if (empty($this->household_id)) {
            return null;
        }

        return Household::with(['kepalaKeluarga', 'residents'])->find($this->household_id);
    }

    /**
     * Menyimpan data warga baru ke database.
     */
    public function saveResident(): void
    {
        // Validasi kondisional berdasarkan kategori warga
        $rules = [
            'kategori_warga' => ['required', 'in:memiliki_kk,tanpa_kk'],
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'digits:16'],
            'tempat_lahir' => ['nullable', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'agama' => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'status_perkawinan' => ['nullable', 'string', 'max:50'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'status_kependudukan' => ['required', 'in:aktif,pindah,meninggal,lahir_baru'],
            'foto_warga' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];

        $messages = [
            'nama.required' => 'Nama lengkap warga wajib diisi.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 digit angka.',
            'tanggal_lahir.required' => 'Tanggal lahir warga wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_kependudukan.required' => 'Status kependudukan wajib dipilih.',
            'foto_warga.image' => 'File foto warga harus berupa gambar valid.',
            'foto_warga.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto_warga.max' => 'Ukuran foto maksimal 5 MB.',
        ];

        if ($this->kategori_warga === 'memiliki_kk') {
            $rules['household_id'] = ['required', 'exists:households,id'];
            $rules['hubungan_keluarga'] = ['required', 'string'];
            $messages['household_id.required'] = 'Pilih Kartu Keluarga (KK) yang terdaftar di RT 15.';
            $messages['household_id.exists'] = 'Kartu Keluarga yang dipilih tidak ditemukan dalam sistem.';
            $messages['hubungan_keluarga.required'] = 'Hubungan dalam keluarga wajib dipilih.';
        } else {
            $rules['status_tempat_tinggal'] = ['required', 'string', 'max:50'];
            $rules['alamat_domisili'] = ['required', 'string', 'max:255'];
            $messages['status_tempat_tinggal.required'] = 'Status tempat tinggal wajib dipilih.';
            $messages['alamat_domisili.required'] = 'Alamat tempat tinggal / domisili di RT 15 wajib diisi.';
        }

        if (!empty($this->email)) {
            $rules['email'] = ['nullable', 'email', 'max:100', 'unique:users,email'];
            $messages['email.unique'] = 'Alamat email ini sudah digunakan oleh akun lain.';
        }

        $this->validate($rules, $messages);

        // Cek duplikasi NIK via Hash SHA-256 (termasuk arsip soft-deleted)
        $nikHash = hash('sha256', $this->nik);
        if (Resident::withTrashed()->where('nik_hash', $nikHash)->exists()) {
            $this->addError('nik', 'NIK ini sudah terdaftar di sistem (termasuk pada data arsip/terhapus).');
            return;
        }

        // Simpan file foto warga bila diunggah
        $fotoPath = null;
        if ($this->foto_warga && !is_string($this->foto_warga)) {
            $fotoPath = $this->foto_warga->store('residents/foto_profil', 'public');
        }

        $savedNama = $this->nama;
        $savedKategori = $this->kategori_warga;

        DB::transaction(function () use ($fotoPath) {
            // 1. Buat / Hubungkan Akun User Portal Warga
            $userEmail = !empty($this->email) ? $this->email : $this->nik . '@warga.simlibas.com';
            $user = User::firstOrCreate(
                ['email' => $userEmail],
                [
                    'name' => $this->nama,
                    'phone' => $this->telepon ?: null,
                    'password' => Hash::make($this->nik),
                    'status' => 'aktif',
                ]
            );

            // Pastikan nama dan password NIK hash sinkron
            $user->update([
                'name' => $this->nama,
                'password' => Hash::make($this->nik),
            ]);

            if (!$user->hasRole('warga')) {
                $user->assignRole('warga');
            }

            $isKepala = ($this->kategori_warga === 'memiliki_kk' && $this->hubungan_keluarga === 'kepala_keluarga');

            // Jika warga baru ini kepala keluarga, nonaktifkan flag kepala keluarga pada anggota lama di KK tersebut
            if ($isKepala && $this->household_id) {
                Resident::where('household_id', $this->household_id)
                    ->where('is_kepala_keluarga', true)
                    ->update([
                        'is_kepala_keluarga' => false,
                        'hubungan_keluarga' => 'lainnya',
                    ]);

                Household::where('id', $this->household_id)->update(['user_id' => $user->id]);
            }

            // 2. Simpan Data Resident (Warga)
            Resident::create([
                'household_id' => $this->kategori_warga === 'memiliki_kk' ? $this->household_id : null,
                'kategori_warga' => $this->kategori_warga,
                'status_tempat_tinggal' => $this->kategori_warga === 'tanpa_kk' ? $this->status_tempat_tinggal : null,
                'alamat_domisili' => $this->kategori_warga === 'tanpa_kk' ? $this->alamat_domisili : null,
                'user_id' => $user->id,
                'nik' => $this->nik,
                'nama' => $this->nama,
                'tempat_lahir' => $this->tempat_lahir ?: 'Jambi',
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'agama' => $this->agama ?: 'Islam',
                'pendidikan_terakhir' => $this->pendidikan_terakhir ?: null,
                'pekerjaan' => $this->pekerjaan ?: null,
                'status_perkawinan' => $this->status_perkawinan ?: null,
                'hubungan_keluarga' => $this->kategori_warga === 'memiliki_kk' ? $this->hubungan_keluarga : 'lainnya',
                'status_kependudukan' => $this->status_kependudukan ?: 'aktif',
                'telepon' => $this->telepon ?: null,
                'email' => $userEmail,
                'foto_path' => $fotoPath,
                'is_kepala_keluarga' => $isKepala,
            ]);
        });

        $deskripsiInfo = $savedKategori === 'memiliki_kk'
            ? "Data warga {$savedNama} berhasil ditambahkan ke dalam Kartu Keluarga terpilih."
            : "Data warga {$savedNama} (domisili tanpa KK) berhasil disimpan ke sistem.";

        Notification::make()
            ->title('Data Warga Berhasil Ditambahkan')
            ->body($deskripsiInfo)
            ->success()
            ->send();

        $this->redirect(ResidentResource::getUrl('index'));
    }
}
