<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.roles.pages.list-roles';

    public const CORE_ROLES = [
        'super_admin',
        'ketua_rt',
        'sekretaris',
        'bendahara',
        'koordinator_bank_sampah',
        'koordinator_keamanan',
        'warga',
        'rw_kelurahan',
    ];

    #[Url]
    public string $search = '';

    #[Url]
    public string $typeFilter = 'semua'; // 'semua', 'core', 'kustom'

    public int $perPage = 15;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?int $detailRoleId = null;
    public ?Role $detailRole = null;
    public array $detailPermissionsByModule = [];

    // State Modal Form Role (Tambah / Ubah Role)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $roleId = null;
    public string $form_name = '';
    public string $form_guard_name = 'web';
    public array $selectedPermissions = [];

    // State Modal Kelola Akun Pemegang Role (Nama, Email, Password)
    public bool $accountModalOpen = false;
    public ?int $accountRoleId = null;
    public ?Role $accountRole = null;
    public ?int $editingUserId = null;
    public bool $isCreatingUser = false;
    public bool $isAssigningUser = false;

    // Field Form Ubah Akun Eksisting
    public string $edit_user_name = '';
    public string $edit_user_email = '';
    public string $edit_user_phone = '';
    public string $edit_user_password = '';
    public string $edit_user_password_confirmation = '';

    // Field Form Tambah Akun Baru
    public string $new_user_name = '';
    public string $new_user_email = '';
    public string $new_user_phone = '';
    public string $new_user_password = '';
    public string $new_user_password_confirmation = '';

    // Field Tugaskan User Eksisting
    public ?int $assign_user_id = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('type') && $this->typeFilter === 'semua') {
            $this->typeFilter = (string) request('type');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->typeFilter = 'semua';
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

    // =========================================================================
    // MODAL DETAIL ROLE & HAK AKSES
    // =========================================================================
    public function openDetail(int $id): void
    {
        $this->detailRoleId = $id;
        $this->detailRole = Role::with(['permissions', 'users' => function ($q) {
            $q->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.status', 'users.avatar_url')
              ->take(30);
        }])->findOrFail($id);

        $activePerms = $this->detailRole->permissions->pluck('name')->flip()->toArray();
        $modules = static::getPermissionModules();
        $grouped = [];

        foreach ($modules as $moduleKey => $module) {
            $activeInModule = [];
            foreach ($module['permissions'] as $permName => $permLabel) {
                if (isset($activePerms[$permName])) {
                    $activeInModule[$permName] = $permLabel;
                }
            }
            $grouped[$moduleKey] = [
                'title' => $module['title'],
                'icon' => $module['icon'],
                'color' => $module['color'],
                'active_permissions' => $activeInModule,
                'total_in_module' => count($module['permissions']),
            ];
        }

        $this->detailPermissionsByModule = $grouped;
        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->detailRoleId = null;
        $this->detailRole = null;
        $this->detailPermissionsByModule = [];
    }

    // =========================================================================
    // MODAL TAMBAH / UBAH ROLE (FORM)
    // =========================================================================
    public function openCreate(): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->roleId = null;
        $this->form_name = '';
        $this->form_guard_name = 'web';
        $this->selectedPermissions = [];
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetValidation();
        $role = Role::with('permissions')->findOrFail($id);

        if ($role->name === 'super_admin') {
            Notification::make()
                ->warning()
                ->title('Aksi Dibatasi')
                ->body('Role Super Administrator memiliki hak akses penuh permanen yang tertanam di sistem dan tidak dapat diubah.')
                ->send();
            return;
        }

        $this->isEditing = true;
        $this->roleId = $role->id;
        $this->form_name = $role->name;
        $this->form_guard_name = $role->guard_name ?? 'web';
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetValidation();
        $this->isEditing = false;
        $this->roleId = null;
        $this->form_name = '';
        $this->selectedPermissions = [];
    }

    public function toggleAllInModule(string $moduleKey): void
    {
        $modules = static::getPermissionModules();
        if (! isset($modules[$moduleKey])) {
            return;
        }

        $modulePerms = array_keys($modules[$moduleKey]['permissions']);
        $allSelected = count(array_intersect($modulePerms, $this->selectedPermissions)) === count($modulePerms);

        if ($allSelected) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $modulePerms));
        } else {
            $this->selectedPermissions = array_values(array_unique(array_merge($this->selectedPermissions, $modulePerms)));
        }
    }

    public function selectAllPermissions(): void
    {
        $all = Permission::pluck('name')->toArray();
        $this->selectedPermissions = $all;
    }

    public function deselectAllPermissions(): void
    {
        $this->selectedPermissions = [];
    }

    public function saveRole(): void
    {
        $this->form_name = Str::slug($this->form_name, '_');

        if ($this->isEditing && $this->roleId) {
            $role = Role::findOrFail($this->roleId);

            if ($role->name === 'super_admin') {
                Notification::make()
                    ->warning()
                    ->title('Aksi Dibatasi')
                    ->body('Role Super Administrator memiliki hak akses penuh permanen yang tertanam di sistem dan tidak dapat diubah.')
                    ->send();
                $this->closeForm();
                return;
            }

            $this->validate([
                'form_name' => 'required|string|max:60|regex:/^[a-z0-9_]+$/|unique:roles,name,' . $role->id,
            ], [
                'form_name.required' => 'Nama identifier role wajib diisi.',
                'form_name.regex' => 'Format nama role hanya boleh menggunakan huruf kecil, angka, dan garis bawah (_).',
                'form_name.unique' => 'Nama identifier role ini sudah digunakan.',
            ]);

            if (in_array($role->name, self::CORE_ROLES)) {
                $role->update(['guard_name' => 'web']);
            } else {
                $role->update([
                    'name' => $this->form_name,
                    'guard_name' => 'web',
                ]);
            }

            if ($role->name === 'super_admin') {
                $all = Permission::all();
                $role->syncPermissions($all);
            } else {
                $role->syncPermissions($this->selectedPermissions);
            }

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            Notification::make()
                ->title('Peran Berhasil Diperbarui')
                ->body("Konfigurasi dan hak akses role '{$role->name}' berhasil disimpan.")
                ->success()
                ->send();
        } else {
            $this->validate([
                'form_name' => 'required|string|max:60|regex:/^[a-z0-9_]+$/|unique:roles,name',
            ], [
                'form_name.required' => 'Nama identifier role wajib diisi.',
                'form_name.regex' => 'Format nama role hanya boleh menggunakan huruf kecil, angka, dan garis bawah (_).',
                'form_name.unique' => 'Nama identifier role ini sudah digunakan.',
            ]);

            $role = Role::create([
                'name' => $this->form_name,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($this->selectedPermissions);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            Notification::make()
                ->title('Role Baru Berhasil Dibuat')
                ->body("Peran '{$role->name}' telah didaftarkan dengan " . count($this->selectedPermissions) . ' hak akses.')
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteRole(int $id): void
    {
        $role = Role::withCount('users')->findOrFail($id);

        if (in_array($role->name, self::CORE_ROLES)) {
            Notification::make()
                ->title('Role Sistem Dilindungi')
                ->body("Role '{$role->name}' merupakan peran inti sistem RT 15 dan tidak boleh dihapus.")
                ->danger()
                ->send();

            return;
        }

        if ($role->users_count > 0) {
            Notification::make()
                ->title('Role Masih Digunakan')
                ->body("Role '{$role->name}' masih digunakan oleh {$role->users_count} pengguna aktif. Lepaskan role dari pengguna terlebih dahulu.")
                ->danger()
                ->send();

            return;
        }

        $roleName = $role->name;
        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Notification::make()
            ->title('Role Berhasil Dihapus')
            ->body("Peran kustom '{$roleName}' telah dihapus dari sistem.")
            ->success()
            ->send();
    }

    // =========================================================================
    // MODAL MANAJEMEN AKUN PENGGUNA (NAMA, EMAIL, & PASSWORD)
    // =========================================================================
    public function openManageAccounts(int $roleId): void
    {
        $this->resetValidation();
        $this->accountRoleId = $roleId;
        $this->accountRole = Role::with(['users'])->findOrFail($roleId);
        $this->editingUserId = null;
        $this->isCreatingUser = false;
        $this->isAssigningUser = false;
        $this->assign_user_id = null;
        $this->accountModalOpen = true;
    }

    public function closeManageAccounts(): void
    {
        $this->accountModalOpen = false;
        $this->accountRoleId = null;
        $this->accountRole = null;
        $this->editingUserId = null;
        $this->isCreatingUser = false;
        $this->isAssigningUser = false;
        $this->assign_user_id = null;
        $this->resetValidation();
    }

    public function startEditUser(int $userId): void
    {
        $this->resetValidation();
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->edit_user_name = $user->name;
        $this->edit_user_email = $user->email;
        $this->edit_user_phone = $user->phone ?? '';
        $this->edit_user_password = '';
        $this->edit_user_password_confirmation = '';
        $this->isCreatingUser = false;
        $this->isAssigningUser = false;
    }

    public function cancelEditUser(): void
    {
        $this->editingUserId = null;
        $this->edit_user_name = '';
        $this->edit_user_email = '';
        $this->edit_user_phone = '';
        $this->edit_user_password = '';
        $this->edit_user_password_confirmation = '';
        $this->resetValidation();
    }

    public function updateUserAccount(): void
    {
        if (! $this->editingUserId) {
            return;
        }

        $this->validate([
            'edit_user_name' => 'required|string|max:255',
            'edit_user_email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'edit_user_phone' => 'nullable|string|max:25',
            'edit_user_password' => 'nullable|string|min:6|same:edit_user_password_confirmation',
        ], [
            'edit_user_name.required' => 'Nama lengkap akun wajib diisi.',
            'edit_user_email.required' => 'Alamat email login wajib diisi.',
            'edit_user_email.email' => 'Format alamat email tidak valid.',
            'edit_user_email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'edit_user_password.min' => 'Password baru minimal harus 6 karakter.',
            'edit_user_password.same' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = User::findOrFail($this->editingUserId);
        $user->name = $this->edit_user_name;
        $user->email = $this->edit_user_email;
        $user->phone = $this->edit_user_phone ?: null;

        $passwordUpdated = false;
        if (filled($this->edit_user_password)) {
            $user->password = Hash::make($this->edit_user_password);
            $passwordUpdated = true;
        }

        $user->save();

        $bodyText = "Informasi akun untuk {$user->name} berhasil diperbarui.";
        if ($passwordUpdated) {
            $bodyText .= ' Kata sandi login juga berhasil diperbarui.';
        }

        Notification::make()
            ->title('Akun Berhasil Disimpan')
            ->body($bodyText)
            ->success()
            ->send();

        $this->editingUserId = null;
        $this->edit_user_password = '';
        $this->edit_user_password_confirmation = '';
        $this->accountRole->load('users');
    }

    public function toggleCreateUser(): void
    {
        if ($this->accountRole && $this->accountRole->name === 'super_admin') {
            Notification::make()
                ->warning()
                ->title('Aksi Dibatasi')
                ->body('Role Super Administrator memiliki satu akun sistem utama tertanam dan tidak dapat ditambah.')
                ->send();
            return;
        }

        $this->resetValidation();
        $this->isCreatingUser = ! $this->isCreatingUser;
        $this->isAssigningUser = false;
        $this->editingUserId = null;
        $this->new_user_name = '';
        $this->new_user_email = '';
        $this->new_user_phone = '';
        $this->new_user_password = '';
        $this->new_user_password_confirmation = '';
    }

    public function createUserForRole(): void
    {
        if (! $this->accountRole) {
            return;
        }

        if ($this->accountRole->name === 'super_admin') {
            Notification::make()
                ->danger()
                ->title('Aksi Ditolak')
                ->body('Akun Super Administrator tertanam di sistem dan tidak dapat ditambah.')
                ->send();
            return;
        }

        $this->validate([
            'new_user_name' => 'required|string|max:255',
            'new_user_email' => 'required|email|max:255|unique:users,email',
            'new_user_phone' => 'nullable|string|max:25',
            'new_user_password' => 'required|string|min:6|same:new_user_password_confirmation',
        ], [
            'new_user_name.required' => 'Nama akun personil wajib diisi.',
            'new_user_email.required' => 'Email login wajib diisi.',
            'new_user_email.unique' => 'Alamat email ini sudah terdaftar.',
            'new_user_password.required' => 'Password akun wajib ditentukan.',
            'new_user_password.min' => 'Password minimal harus 6 karakter.',
            'new_user_password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $newUser = User::create([
            'name' => $this->new_user_name,
            'email' => $this->new_user_email,
            'phone' => $this->new_user_phone ?: null,
            'password' => Hash::make($this->new_user_password),
            'status' => 'aktif',
        ]);

        $newUser->assignRole($this->accountRole->name);

        Notification::make()
            ->title('Akun Baru Berhasil Dibuat')
            ->body("Akun {$newUser->name} ({$newUser->email}) berhasil dibuat dan memegang peran '{$this->accountRole->name}'.")
            ->success()
            ->send();

        $this->isCreatingUser = false;
        $this->new_user_name = '';
        $this->new_user_email = '';
        $this->new_user_phone = '';
        $this->new_user_password = '';
        $this->new_user_password_confirmation = '';
        $this->accountRole->load('users');
    }

    public function toggleAssignUser(): void
    {
        if ($this->accountRole && $this->accountRole->name === 'super_admin') {
            Notification::make()
                ->warning()
                ->title('Aksi Dibatasi')
                ->body('Role Super Administrator memiliki satu akun sistem utama tertanam dan tidak dapat ditambah.')
                ->send();
            return;
        }

        $this->resetValidation();
        $this->isAssigningUser = ! $this->isAssigningUser;
        $this->isCreatingUser = false;
        $this->editingUserId = null;
        $this->assign_user_id = null;
    }

    public function assignExistingUser(): void
    {
        if (! $this->accountRole || ! $this->assign_user_id) {
            return;
        }

        if ($this->accountRole->name === 'super_admin') {
            Notification::make()
                ->danger()
                ->title('Aksi Ditolak')
                ->body('Akun Super Administrator tertanam di sistem dan tidak dapat ditambah.')
                ->send();
            return;
        }

        $this->validate([
            'assign_user_id' => 'required|exists:users,id',
        ], [
            'assign_user_id.required' => 'Pilih pengguna yang ingin ditugaskan.',
        ]);

        $user = User::findOrFail($this->assign_user_id);
        $user->assignRole($this->accountRole->name);

        Notification::make()
            ->title('Pengguna Berhasil Ditugaskan')
            ->body("Peran '{$this->accountRole->name}' berhasil diberikan kepada {$user->name}.")
            ->success()
            ->send();

        $this->isAssigningUser = false;
        $this->assign_user_id = null;
        $this->accountRole->load('users');
    }

    public function removeUserFromRole(int $userId): void
    {
        if (! $this->accountRole) {
            return;
        }

        if ($this->accountRole->name === 'super_admin') {
            Notification::make()
                ->danger()
                ->title('Aksi Ditolak')
                ->body('Akun Super Administrator tertanam di dalam sistem dan tidak dapat dilepaskan atau dihapus.')
                ->send();
            return;
        }

        $user = User::findOrFail($userId);
        $user->removeRole($this->accountRole->name);

        Notification::make()
            ->title('Peran Dilepaskan')
            ->body("Peran '{$this->accountRole->name}' berhasil dilepaskan dari pengguna {$user->name}.")
            ->success()
            ->send();

        $this->accountRole->load('users');
    }

    // =========================================================================
    // HELPER & METADATA PERMISSIONS & ROLES
    // =========================================================================
    public static function getPermissionModules(): array
    {
        return [
            'pengaturan' => [
                'title' => 'Pengaturan & Sistem',
                'icon' => 'heroicon-o-cog-6-tooth',
                'color' => 'slate',
                'permissions' => [
                    'kelola_pengaturan_rt' => 'Kelola Profil & Pengaturan RT',
                    'kelola_pengguna' => 'Kelola Akun Pengguna & Hak Akses',
                    'lihat_audit_log' => 'Akses Riwayat Log Aktivitas Sistem',
                ],
            ],
            'kependudukan' => [
                'title' => 'Kependudukan & Warga (KK)',
                'icon' => 'heroicon-o-user-group',
                'color' => 'blue',
                'permissions' => [
                    'lihat_data_warga' => 'Lihat Data Warga & Kartu Keluarga',
                    'tambah_data_warga' => 'Tambah Data Warga & KK Baru',
                    'edit_data_warga' => 'Ubah Data Warga & Kartu Keluarga',
                    'hapus_data_warga' => 'Hapus Data Warga & Kartu Keluarga',
                    'impor_ekspor_warga' => 'Impor & Ekspor Data Kependudukan',
                    'kelola_mutasi_warga' => 'Kelola Mutasi Warga (Pindah/Meninggal)',
                ],
            ],
            'surat' => [
                'title' => 'Layanan Surat Pengantar',
                'icon' => 'heroicon-o-document-text',
                'color' => 'indigo',
                'permissions' => [
                    'ajukan_surat' => 'Buat & Ajukan Surat Pengantar',
                    'periksa_surat' => 'Verifikasi & Periksa Pengajuan Surat',
                    'setujui_surat' => 'Setujui & Sahkan Nomor Surat Pengantar',
                    'tolak_surat' => 'Tolak Pengajuan Surat Warga',
                    'kelola_template_surat' => 'Kelola Master Format Jenis Surat',
                    'arsip_buku_surat' => 'Akses Buku Register & Arsip Surat',
                ],
            ],
            'keuangan' => [
                'title' => 'Keuangan & Kas RT',
                'icon' => 'heroicon-o-banknotes',
                'color' => 'emerald',
                'permissions' => [
                    'kelola_jenis_iuran' => 'Kelola Master Tarif Pos Iuran',
                    'terbitkan_tagihan' => 'Terbitkan Lembar Tagihan Iuran Warga',
                    'bayar_tagihan' => 'Input Pembayaran Tagihan Warga',
                    'validasi_pembayaran' => 'Validasi Bukti Pembayaran Iuran',
                    'kelola_buku_kas' => 'Kelola Buku Kas (Arus Masuk/Keluar)',
                    'lihat_laporan_keuangan' => 'Lihat & Ekspor Laporan Keuangan RT',
                ],
            ],
            'komunikasi' => [
                'title' => 'Komunikasi & Agenda RT',
                'icon' => 'heroicon-o-megaphone',
                'color' => 'amber',
                'permissions' => [
                    'kelola_pengumuman' => 'Buat & Siarkan Pengumuman Warga',
                    'kelola_agenda' => 'Susun & Terbitkan Agenda Kegiatan RT',
                    'rsvp_kegiatan' => 'Konfirmasi Kehadiran Kegiatan (RSVP)',
                ],
            ],
            'pengaduan' => [
                'title' => 'Layanan Pengaduan Warga',
                'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                'color' => 'rose',
                'permissions' => [
                    'kirim_pengaduan' => 'Kirim Laporan Aduan Masalah Lingkungan',
                    'tangani_pengaduan' => 'Tindak Lanjuti & Selesaikan Pengaduan',
                    'lihat_semua_pengaduan' => 'Pantau Rekapitulasi Seluruh Pengaduan',
                ],
            ],
            'keamanan' => [
                'title' => 'Keamanan Lingkungan & Ronda',
                'icon' => 'heroicon-o-shield-check',
                'color' => 'purple',
                'permissions' => [
                    'kelola_jadwal_ronda' => 'Atur & Kelola Jadwal Pos Ronda Malam',
                    'lapor_tamu' => 'Laporkan Tamu Menginap 1x24 Jam',
                    'lihat_laporan_tamu' => 'Pantau Buku Tamu Wajib Lapor',
                ],
            ],
            'bank_sampah' => [
                'title' => 'Bank Sampah Pinang Masak Lestari',
                'icon' => 'heroicon-o-arrow-path-rounded-square',
                'color' => 'teal',
                'permissions' => [
                    'kelola_master_sampah' => 'Atur Katalog Komoditas & Harga Beli',
                    'catat_setoran_sampah' => 'Timbang & Catat Transaksi Setoran Sampah',
                    'kelola_saldo_sampah' => 'Kelola Dompet & Penarikan Saldo Sampah',
                    'lihat_dampak_lingkungan' => 'Pantau Statistik Dampak Lingkungan',
                ],
            ],
            'umkm' => [
                'title' => 'Pemberdayaan UMKM Warga',
                'icon' => 'heroicon-o-building-storefront',
                'color' => 'amber',
                'permissions' => [
                    'kelola_profil_umkm' => 'Daftarkan & Kelola Profil Usaha Warga',
                    'moderasi_umkm' => 'Moderasi & Verifikasi Tayang Produk UMKM',
                ],
            ],
        ];
    }

    public static function getRoleDetails(string $roleName): array
    {
        return match ($roleName) {
            'super_admin' => [
                'label' => 'Super Administrator',
                'desc' => 'Akses penuh tanpa batas ke seluruh konfigurasi, audit log, dan data sistem SIM-LiBas.',
                'badge_color' => 'bg-purple-50 text-purple-700 border-purple-200',
                'is_core' => true,
            ],
            'ketua_rt' => [
                'label' => 'Ketua RT 15',
                'desc' => 'Pimpinan eksekutif wilayah RT 15: validasi surat pengantar, monitoring kas, pengaduan, dan data warga.',
                'badge_color' => 'bg-blue-50 text-blue-700 border-blue-200',
                'is_core' => true,
            ],
            'sekretaris' => [
                'label' => 'Sekretaris RT 15',
                'desc' => 'Pengelola administrasi persuratan, arsip buku register, data kependudukan, pengumuman, dan agenda warga.',
                'badge_color' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'is_core' => true,
            ],
            'bendahara' => [
                'label' => 'Bendahara RT 15',
                'desc' => 'Pengelola keuangan kas RT, penetapan pos iuran, penerbitan invoice tagihan, dan validasi pembayaran warga.',
                'badge_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'is_core' => true,
            ],
            'koordinator_bank_sampah' => [
                'label' => 'Koordinator Bank Sampah',
                'desc' => 'Penanggung jawab operasional Bank Sampah Lestari: master harga komoditas, penimbangan, dan pencatatan setoran.',
                'badge_color' => 'bg-teal-50 text-teal-700 border-teal-200',
                'is_core' => true,
            ],
            'koordinator_keamanan' => [
                'label' => 'Koordinator Keamanan',
                'desc' => 'Pengawas ketertiban dan keamanan lingkungan: penyusunan jadwal pos ronda malam dan monitoring buku tamu wajib lapor.',
                'badge_color' => 'bg-amber-50 text-amber-700 border-amber-200',
                'is_core' => true,
            ],
            'warga' => [
                'label' => 'Warga RT 15',
                'desc' => 'Akses portal mandiri warga: permohonan surat pengantar online, konfirmasi bayar iuran, buku tamu, dan aduan.',
                'badge_color' => 'bg-slate-100 text-slate-700 border-slate-200',
                'is_core' => true,
            ],
            'rw_kelurahan' => [
                'label' => 'Pihak RW / Kelurahan',
                'desc' => 'Akses pemantauan eksternal untuk verifikasi rekapitulasi data kependudukan dan laporan keuangan transparansi RT.',
                'badge_color' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'is_core' => true,
            ],
            default => [
                'label' => ucwords(str_replace(['_', '-'], ' ', $roleName)),
                'desc' => 'Peran kustom operasional yang dibuat oleh Super Administrator untuk tugas khusus di RT 15.',
                'badge_color' => 'bg-slate-50 text-slate-700 border-slate-200',
                'is_core' => false,
            ],
        };
    }

    protected function getViewData(): array
    {
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $totalAssignedUsers = User::whereHas('roles')->count();
        $coreRolesCount = count(self::CORE_ROLES);

        $query = Role::withCount(['users', 'permissions'])
            ->with(['permissions' => function ($q) {
                $q->select('permissions.id', 'permissions.name')->take(6);
            }])
            ->orderBy('id', 'asc');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                  ->orWhereHas('permissions', function ($p) use ($term) {
                      $p->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                  });
            });
        }

        if ($this->typeFilter === 'core') {
            $query->whereIn('name', self::CORE_ROLES);
        } elseif ($this->typeFilter === 'kustom') {
            $query->whereNotIn('name', self::CORE_ROLES);
        }

        $roles = $query->paginate($this->perPage);

        // Daftar semua pengguna untuk dropdown penugasan (opsional)
        $allUsers = User::orderBy('name')->get(['id', 'name', 'email']);

        return [
            'roles' => $roles,
            'totalRoles' => $totalRoles,
            'totalPermissions' => $totalPermissions,
            'totalAssignedUsers' => $totalAssignedUsers,
            'coreRolesCount' => $coreRolesCount,
            'permissionModules' => static::getPermissionModules(),
            'allUsers' => $allUsers,
        ];
    }
}
