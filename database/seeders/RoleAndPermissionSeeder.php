<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed Roles dan Permissions SIM-LiBas sesuai PRD Bagian 5
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definisikan Permissions
        $permissions = [
            // Pengaturan & Sistem
            'kelola_pengaturan_rt',
            'kelola_pengguna',
            'lihat_audit_log',

            // Data Warga & KK
            'lihat_data_warga',
            'tambah_data_warga',
            'edit_data_warga',
            'hapus_data_warga',
            'impor_ekspor_warga',
            'kelola_mutasi_warga',

            // Surat Pengantar
            'ajukan_surat',
            'periksa_surat',
            'setujui_surat',
            'tolak_surat',
            'kelola_template_surat',
            'arsip_buku_surat',

            // Keuangan & Kas RT
            'kelola_jenis_iuran',
            'terbitkan_tagihan',
            'bayar_tagihan',
            'validasi_pembayaran',
            'kelola_buku_kas',
            'lihat_laporan_keuangan',

            // Pengumuman & Agenda
            'kelola_pengumuman',
            'kelola_agenda',
            'rsvp_kegiatan',

            // Pengaduan
            'kirim_pengaduan',
            'tangani_pengaduan',
            'lihat_semua_pengaduan',

            // Keamanan Lingkungan
            'kelola_jadwal_ronda',
            'lapor_tamu',
            'lihat_laporan_tamu',

            // Bank Sampah Pinang Masak Lestari
            'kelola_master_sampah',
            'catat_setoran_sampah',
            'kelola_saldo_sampah',
            'lihat_dampak_lingkungan',

            // UMKM Warga
            'kelola_profil_umkm',
            'moderasi_umkm',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 2. Definisikan Roles & Assign Permissions
        // Super Admin (Semua hak akses)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Ketua RT (Peran Eksekutif / Pimpinan Wilayah: Kependudukan, Approval Surat, Penanganan Aduan, Monitoring Kas)
        $ketuaRt = Role::firstOrCreate(['name' => 'ketua_rt', 'guard_name' => 'web']);
        $ketuaRt->givePermissionTo([
            'lihat_audit_log',
            'lihat_data_warga',
            'tambah_data_warga',
            'edit_data_warga',
            'kelola_mutasi_warga',
            'periksa_surat',
            'setujui_surat',
            'tolak_surat',
            'arsip_buku_surat',
            'lihat_laporan_keuangan',
            'lihat_semua_pengaduan',
            'tangani_pengaduan',
        ]);

        // Sekretaris (Administrasi Umum, Surat Menyurat, Pengumuman, Agenda, Moderasi UMKM)
        $sekretaris = Role::firstOrCreate(['name' => 'sekretaris', 'guard_name' => 'web']);
        $sekretaris->givePermissionTo([
            'lihat_data_warga',
            'tambah_data_warga',
            'edit_data_warga',
            'hapus_data_warga',
            'impor_ekspor_warga',
            'kelola_mutasi_warga',
            'periksa_surat',
            'kelola_template_surat',
            'arsip_buku_surat',
            'kelola_pengumuman',
            'kelola_agenda',
            'lihat_semua_pengaduan',
            'tangani_pengaduan',
            'moderasi_umkm',
        ]);

        // Bendahara (Tata Kelola Keuangan, Tagihan, Pembayaran, Kas RT, Jenis Iuran)
        $bendahara = Role::firstOrCreate(['name' => 'bendahara', 'guard_name' => 'web']);
        $bendahara->givePermissionTo([
            'kelola_jenis_iuran',
            'terbitkan_tagihan',
            'validasi_pembayaran',
            'kelola_buku_kas',
            'lihat_laporan_keuangan',
        ]);

        // Koordinator Bank Sampah
        $koorBankSampah = Role::firstOrCreate(['name' => 'koordinator_bank_sampah', 'guard_name' => 'web']);
        $koorBankSampah->givePermissionTo([
            'kelola_master_sampah',
            'catat_setoran_sampah',
            'kelola_saldo_sampah',
            'lihat_dampak_lingkungan',
        ]);

        // Koordinator Keamanan
        $koorKeamanan = Role::firstOrCreate(['name' => 'koordinator_keamanan', 'guard_name' => 'web']);
        $koorKeamanan->givePermissionTo([
            'kelola_jadwal_ronda',
            'lihat_laporan_tamu',
        ]);

        // Warga (Kepala Keluarga / Anggota)
        $warga = Role::firstOrCreate(['name' => 'warga', 'guard_name' => 'web']);
        $warga->givePermissionTo([
            'ajukan_surat',
            'bayar_tagihan',
            'rsvp_kegiatan',
            'kirim_pengaduan',
            'lapor_tamu',
            'kelola_profil_umkm',
        ]);

        // RW / Kelurahan (Pemantau)
        $rwKelurahan = Role::firstOrCreate(['name' => 'rw_kelurahan', 'guard_name' => 'web']);
        $rwKelurahan->givePermissionTo([
            'lihat_laporan_keuangan',
            'lihat_dampak_lingkungan',
        ]);
    }
}
