<?php

namespace Tests\Feature;

use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_super_admin_can_access_role_management_page(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $this->actingAs($superAdmin)
            ->get('/admin/roles')
            ->assertStatus(200)
            ->assertSee('Manajemen Role & Hak Akses')
            ->assertSee('Super Administrator')
            ->assertSee('Ketua RT 15')
            ->assertSee('Sekretaris RT 15');
    }

    public function test_non_super_admin_cannot_access_role_management_page(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first();
        $warga = User::where('email', 'warga.dedi@simlibas.com')->first();

        $this->actingAs($bendahara)
            ->get('/admin/roles')
            ->assertStatus(403);

        $this->actingAs($warga)
            ->get('/admin/roles')
            ->assertStatus(403);
    }

    public function test_super_admin_can_open_detail_modal_and_view_permissions(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $ketuaRtRole = Role::where('name', 'ketua_rt')->first();

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openDetail', $ketuaRtRole->id)
            ->assertSet('detailModalOpen', true)
            ->assertSet('detailRoleId', $ketuaRtRole->id)
            ->call('closeDetail')
            ->assertSet('detailModalOpen', false);
    }

    public function test_super_admin_can_create_edit_and_delete_custom_role(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();

        // 1. Tambah Role Kustom Baru
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openCreate')
            ->assertSet('formModalOpen', true)
            ->set('form_name', 'koordinator_humas')
            ->set('selectedPermissions', ['kelola_pengumuman', 'kelola_agenda'])
            ->call('saveRole')
            ->assertSet('formModalOpen', false);

        $this->assertDatabaseHas('roles', [
            'name' => 'koordinator_humas',
            'guard_name' => 'web',
        ]);

        $customRole = Role::where('name', 'koordinator_humas')->first();
        $this->assertTrue($customRole->hasPermissionTo('kelola_pengumuman'));
        $this->assertTrue($customRole->hasPermissionTo('kelola_agenda'));
        $this->assertFalse($customRole->hasPermissionTo('kelola_pengguna'));

        // 2. Ubah Hak Akses Role
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openEdit', $customRole->id)
            ->assertSet('formModalOpen', true)
            ->set('selectedPermissions', ['kelola_pengumuman', 'kelola_agenda', 'rsvp_kegiatan'])
            ->call('saveRole')
            ->assertSet('formModalOpen', false);

        $this->assertTrue($customRole->fresh()->hasPermissionTo('rsvp_kegiatan'));

        // 3. Hapus Role Kustom
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('deleteRole', $customRole->id);

        $this->assertDatabaseMissing('roles', [
            'id' => $customRole->id,
            'name' => 'koordinator_humas',
        ]);
    }

    public function test_core_roles_cannot_be_deleted(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('deleteRole', $superAdminRole->id);

        // Core role tetap ada di database
        $this->assertDatabaseHas('roles', [
            'id' => $superAdminRole->id,
            'name' => 'super_admin',
        ]);
    }

    public function test_super_admin_can_update_user_name_email_and_password_for_role(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $ketuaRtRole = Role::where('name', 'ketua_rt')->first();
        $ketuaRtUser = User::where('email', 'rt@simlibas.com')->first();

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openManageAccounts', $ketuaRtRole->id)
            ->assertSet('accountModalOpen', true)
            ->call('startEditUser', $ketuaRtUser->id)
            ->assertSet('editingUserId', $ketuaRtUser->id)
            ->set('edit_user_name', 'Bpk. Ahmad Sudirman (Diperbarui)')
            ->set('edit_user_email', 'ketua.baru@simlibas.com')
            ->set('edit_user_password', 'passwordBaru123')
            ->set('edit_user_password_confirmation', 'passwordBaru123')
            ->call('updateUserAccount')
            ->assertSet('editingUserId', null);

        $ketuaRtUser->refresh();
        $this->assertEquals('Bpk. Ahmad Sudirman (Diperbarui)', $ketuaRtUser->name);
        $this->assertEquals('ketua.baru@simlibas.com', $ketuaRtUser->email);
        $this->assertTrue(Hash::check('passwordBaru123', $ketuaRtUser->password));
    }

    public function test_super_admin_can_create_new_user_and_assign_to_role(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $rwRole = Role::where('name', 'rw_kelurahan')->first();

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openManageAccounts', $rwRole->id)
            ->assertSet('accountModalOpen', true)
            ->call('toggleCreateUser')
            ->assertSet('isCreatingUser', true)
            ->set('new_user_name', 'Bpk. Lurah Pematang Sulur')
            ->set('new_user_email', 'lurah@pematangsulur.id')
            ->set('new_user_password', 'lurahPassword123')
            ->set('new_user_password_confirmation', 'lurahPassword123')
            ->call('createUserForRole')
            ->assertSet('isCreatingUser', false);

        $this->assertDatabaseHas('users', [
            'name' => 'Bpk. Lurah Pematang Sulur',
            'email' => 'lurah@pematangsulur.id',
        ]);

        $createdUser = User::where('email', 'lurah@pematangsulur.id')->first();
        $this->assertTrue($createdUser->hasRole('rw_kelurahan'));
    }

    public function test_super_admin_can_remove_role_from_user(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $wargaRole = Role::where('name', 'warga')->first();
        $wargaUser = User::where('email', 'warga.dedi@simlibas.com')->first();

        $this->assertTrue($wargaUser->hasRole('warga'));

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openManageAccounts', $wargaRole->id)
            ->call('removeUserFromRole', $wargaUser->id);

        $this->assertFalse($wargaUser->fresh()->hasRole('warga'));
    }

    public function test_super_admin_role_cannot_be_edited_or_deleted(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        // 1. openEdit pada super_admin harus ditolak (form modal tidak terbuka)
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openEdit', $superAdminRole->id)
            ->assertSet('formModalOpen', false);

        // 2. saveRole pada super_admin tidak boleh mengubah izin
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->set('isEditing', true)
            ->set('roleId', $superAdminRole->id)
            ->set('form_name', 'super_admin')
            ->set('selectedPermissions', ['kelola_pengaturan_rt'])
            ->call('saveRole')
            ->assertSet('formModalOpen', false);

        // Permissions super_admin tetap lengkap
        $this->assertEquals(Permission::count(), $superAdminRole->fresh()->permissions->count());

        // 3. deleteRole pada super_admin ditolak
        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('deleteRole', $superAdminRole->id);

        $this->assertDatabaseHas('roles', ['name' => 'super_admin']);
    }

    public function test_super_admin_account_cannot_be_created_assigned_or_removed(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $otherUser = User::where('email', 'rt@simlibas.com')->first();

        $component = Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openManageAccounts', $superAdminRole->id);

        // 1. toggleCreateUser tidak boleh membuka form tambah akun
        $component->call('toggleCreateUser')
            ->assertSet('isCreatingUser', false);

        // 2. createUserForRole ditolak
        $component->set('new_user_name', 'Admin Palsu')
            ->set('new_user_email', 'palsu@simlibas.com')
            ->set('new_user_password', 'password123')
            ->set('new_user_password_confirmation', 'password123')
            ->call('createUserForRole');

        $this->assertDatabaseMissing('users', ['email' => 'palsu@simlibas.com']);

        // 3. toggleAssignUser tidak boleh membuka form assign
        $component->call('toggleAssignUser')
            ->assertSet('isAssigningUser', false);

        // 4. assignExistingUser ditolak
        $component->set('assign_user_id', $otherUser->id)
            ->call('assignExistingUser');

        $this->assertFalse($otherUser->fresh()->hasRole('super_admin'));

        // 5. removeUserFromRole ditolak untuk akun super_admin
        $component->call('removeUserFromRole', $superAdmin->id);
        $this->assertTrue($superAdmin->fresh()->hasRole('super_admin'));
    }

    public function test_super_admin_account_can_update_name_email_and_password(): void
    {
        $superAdmin = User::where('email', 'admin@simlibas.com')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        Livewire::actingAs($superAdmin)
            ->test(ListRoles::class)
            ->call('openManageAccounts', $superAdminRole->id)
            ->call('startEditUser', $superAdmin->id)
            ->assertSet('editingUserId', $superAdmin->id)
            ->set('edit_user_name', 'Super Administrator Utama')
            ->set('edit_user_email', 'superadmin.baru@simlibas.com')
            ->set('edit_user_password', 'superRahasia2026')
            ->set('edit_user_password_confirmation', 'superRahasia2026')
            ->call('updateUserAccount')
            ->assertSet('editingUserId', null);

        $superAdmin->refresh();
        $this->assertEquals('Super Administrator Utama', $superAdmin->name);
        $this->assertEquals('superadmin.baru@simlibas.com', $superAdmin->email);
        $this->assertTrue(Hash::check('superRahasia2026', $superAdmin->password));
    }
}
