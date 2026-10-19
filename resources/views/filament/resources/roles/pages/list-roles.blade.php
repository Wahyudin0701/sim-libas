<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            accountModalOpen: @entangle('accountModalOpen'),
            deleteConfirmModalOpen: false,
            deleteRoleId: null,
            deleteRoleName: '',
            showEditPassword: false,
            showNewPassword: false,
            confirmDelete(id, name) {
                this.deleteRoleId = id;
                this.deleteRoleName = name;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteRoleId = null;
                this.deleteRoleName = '';
            },
            executeDelete() {
                if (this.deleteRoleId) {
                    const id = this.deleteRoleId;
                    this.closeDeleteConfirm();
                    $wire.deleteRole(id);
                }
            }
        }"
        @keydown.escape.window="
            closeDeleteConfirm();
            if (accountModalOpen) { accountModalOpen = false; $wire.closeManageAccounts(); }
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); }
            if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }
        "
        class="space-y-4"
    >
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, JUDUL, DAN TOMBOL AKSI UTAMA
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>PENGATURAN SISTEM</span>
                    <x-page-guide 
                        title="Manajemen Role & Hak Akses"
                        category="PENGATURAN SISTEM"
                        description="Modul konfigurasi hak akses pengguna, personil pemegang peran, dan kredensial login (nama, email, password) di panel SIM-LiBas RT 15."
                        :steps="[
                            ['title' => 'Pencarian & Filter Role', 'desc' => 'Cari nama peran struktural atau saring berdasarkan tipe role (Sistem / Kustom).'],
                            ['title' => 'Atur Akun & Kredensial', 'desc' => 'Klik tombol biru/indigo \'Kelola Akun\' pada tabel untuk mengganti nama personil, email login, dan password akun pemegang role tersebut.'],
                            ['title' => 'Kelola Hak Akses (Permissions)', 'desc' => 'Gunakan tombol \'Ubah\' untuk menyesuaikan permission modul yang boleh diakses oleh masing-masing peran.'],
                            ['title' => 'Rincian & Personil', 'desc' => 'Klik tombol \'Lihat Detail\' untuk meninjau personil pengurus yang memegang role tersebut beserta matriks izin lengkapnya.']
                        ]"
                        role="Super Administrator"
                        :tips="[
                            'Setiap role struktural (Ketua RT, Sekretaris, Bendahara, dll.) dapat langsung diatur kredensial akun login-nya di halaman ini.',
                            'Saat mengubah password akun, admin tidak perlu mengetahui password lama.',
                            'Role bawaan sistem dilindungi agar alur kerja esensial sistem tetap stabil.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Manajemen Role & Hak Akses
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Primer: Tambah Role Baru --}}
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-sm cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Role Baru</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. KARTU STATISTIK (KPI OVERVIEW)
             ==================================================================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Card 1: Total Role --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Total Role</span>
                    <span class="p-1.5 rounded-lg bg-blue-50 text-[#0B3A85]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ $totalRoles }}</span>
                    <span class="text-xs text-[#5B6B82] font-semibold">peran</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Struktur kewenangan di sistem</p>
            </div>

            {{-- Card 2: Total Permissions --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Total Hak Akses</span>
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ $totalPermissions }}</span>
                    <span class="text-xs text-emerald-700 font-semibold">permissions</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Izin wewenang lintas modul</p>
            </div>

            {{-- Card 3: Pengguna Terdaftar --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Pengguna Berperan</span>
                    <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ $totalAssignedUsers }}</span>
                    <span class="text-xs text-indigo-700 font-semibold">pengguna</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Akun dengan penugasan role</p>
            </div>

            {{-- Card 4: Role Bawaan Sistem --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Role Sistem Inti</span>
                    <span class="p-1.5 rounded-lg bg-purple-50 text-purple-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ $coreRolesCount }}</span>
                    <span class="text-xs text-purple-700 font-semibold">role inti</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Dilindungi dari penghapusan</p>
            </div>
        </div>

        {{-- ====================================================================
             3. SEARCH BAR & DROPDOWN FILTER & RESET FILTER
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-between gap-2.5">
            {{-- Search Bar --}}
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama role, slug, atau hak akses..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    @if(filled($search))
                        <button 
                            type="button" 
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                            title="Hapus kata kunci"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Filter Sisi Kanan --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                <div class="w-full sm:w-48">
                    <select 
                        wire:model.live="typeFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="semua">Semua Tipe Role</option>
                        <option value="core">Role Sistem Inti</option>
                        <option value="kustom">Role Kustom Tambahan</option>
                    </select>
                </div>

                @if(filled($search) || $typeFilter !== 'semua')
                    <button 
                        type="button" 
                        wire:click="resetFilters" 
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs"
                        title="Reset semua filter pencarian"
                    >
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset Filter</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             4. TABEL DATA ROLE & HAK AKSES (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="search, typeFilter, resetFilters, previousPage, nextPage, gotoPage" 
                text="Memperbarui data role & hak akses..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-12 min-w-[48px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 min-w-[220px]">NAMA PERAN (ROLE) & SLUG</th>
                            <th class="py-2.5 px-3 min-w-[270px]">DESKRIPSI & CAKUPAN TUGAS</th>
                            <th class="py-2.5 px-3 text-center min-w-[130px] whitespace-nowrap">PENGGUNA / AKUN</th>
                            <th class="py-2.5 px-3 text-center min-w-[130px] whitespace-nowrap">HAK AKSES</th>
                            <th class="py-2.5 px-3 text-center min-w-[110px] whitespace-nowrap">TIPE PERAN</th>
                            <th class="py-2.5 px-3 text-center min-w-[150px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#F1F5F9] text-xs">
                        @forelse($roles as $index => $role)
                            @php
                                $detail = \App\Filament\Resources\Roles\Pages\ListRoles::getRoleDetails($role->name);
                                $isCore = in_array($role->name, \App\Filament\Resources\Roles\Pages\ListRoles::CORE_ROLES);
                            @endphp
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                {{-- 1. NO --}}
                                <td class="py-3 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $roles->firstItem() ? ($roles->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- 2. NAMA PERAN & SLUG --}}
                                <td class="py-3 px-3">
                                    <div class="font-extrabold text-[#0F1F3D] text-[13px]">
                                        {{ $detail['label'] }}
                                    </div>
                                    <div class="mt-0.5 flex items-center gap-1.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $role->name }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            #{{ $role->id }}
                                        </span>
                                    </div>
                                </td>

                                {{-- 3. DESKRIPSI & CAKUPAN TUGAS --}}
                                <td class="py-3 px-3">
                                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $detail['desc'] }}
                                    </p>
                                </td>

                                {{-- 4. JUMLAH PENGGUNA (INTERACTIVE BADGE TO MANAGE ACCOUNTS) --}}
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <button 
                                        type="button" 
                                        wire:click="openManageAccounts({{ $role->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $role->users_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 hover:border-indigo-300' : 'bg-slate-50 text-slate-400 border border-slate-200 hover:bg-slate-100' }} transition-all cursor-pointer group shadow-2xs"
                                        title="Klik untuk kelola akun, nama personil, email, dan password pemegang role ini"
                                    >
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $role->users_count }} Akun</span>
                                        <svg class="w-3 h-3 text-indigo-400 group-hover:text-indigo-600 transition-colors ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                </td>

                                {{-- 5. TOTAL HAK AKSES --}}
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    @if($role->name === 'super_admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Semua Akses (Full)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span>{{ $role->permissions_count }} Permissions</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- 6. TIPE PERAN --}}
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    @if($isCore)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200" title="Role bawaan sistem yang esensial">
                                            <svg class="w-2.5 h-2.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Sistem Inti
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200" title="Role kustom tambahan pengurus">
                                            Kustom
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. AKSI (4 TOMBOL KONSISTEN) --}}
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        {{-- Tombol 1: Kelola Akun & Kredensial (Indigo) --}}
                                        <button 
                                            type="button" 
                                            wire:click="openManageAccounts({{ $role->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-indigo-300 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800 hover:border-indigo-400 transition-all shadow-2xs cursor-pointer"
                                            title="Atur Akun Pemegang Role (Nama, Email, & Password)"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol 2: Detail Hak Akses (Biru) --}}
                                        <button 
                                            type="button" 
                                            wire:click="openDetail({{ $role->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-blue-300 bg-blue-50 text-[#0B3A85] hover:bg-blue-100 hover:text-[#07275E] hover:border-blue-400 transition-all shadow-2xs cursor-pointer"
                                            title="Rincian Hak Akses & Matriks Modul"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol 3: Ubah Permissions (Amber) --}}
                                        @if($role->name === 'super_admin')
                                            <button 
                                                type="button" 
                                                disabled 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed"
                                                title="Role Super Administrator tertanam di sistem dan hak aksesnya tidak dapat diubah"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button 
                                                type="button" 
                                                wire:click="openEdit({{ $role->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer"
                                                title="Ubah Konfigurasi Hak Akses Role"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Tombol 4: Hapus (Rose) --}}
                                        @if($isCore)
                                            <button 
                                                type="button" 
                                                disabled 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed"
                                                title="Role inti sistem dilindungi dari penghapusan"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button 
                                                type="button" 
                                                @click="confirmDelete({{ $role->id }}, '{{ addslashes($role->name) }}')"
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                                title="Hapus Role Kustom"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Tidak ada data role yang cocok</p>
                                        <p class="text-xs text-slate-400 mt-1">Sesuaikan kata kunci pencarian atau filter tipe peran Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($roles->hasPages())
                <div class="p-3 border-t border-[#F1F5F9] bg-[#F8FAFC]">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL MANAJEMEN AKUN PEMEGANG ROLE (NAMA, EMAIL, & PASSWORD)
             ==================================================================== --}}
        <div 
            x-show="accountModalOpen" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-account-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="accountModalOpen = false; $wire.closeManageAccounts()"
            ></div>

            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    x-show="accountModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-2xl border border-slate-200 max-h-[90vh] flex flex-col" 
                    @click.stop
                >
                    {{-- Header Deep Navy --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Pengaturan Akun & Kredensial Login</h3>
                                <p class="text-[11px] text-white/70">
                                    {{ $accountRole ? 'Role: ' . \App\Filament\Resources\Roles\Pages\ListRoles::getRoleDetails($accountRole->name)['label'] . ' (' . $accountRole->name . ')' : 'Kelola Akun' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="accountModalOpen = false; $wire.closeManageAccounts()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body Scrollable --}}
                    <div class="p-5 overflow-y-auto space-y-4 text-xs text-slate-700">
                        @if($accountRole)
                            @php
                                $roleMeta = \App\Filament\Resources\Roles\Pages\ListRoles::getRoleDetails($accountRole->name);
                            @endphp

                            {{-- Top Banner: Ringkasan Role & Toolbar Tambah Akun --}}
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-[#0B3A85] text-white font-bold flex items-center justify-center text-sm shrink-0">
                                        {{ strtoupper(substr($accountRole->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-[#0F1F3D] text-sm">{{ $roleMeta['label'] }}</div>
                                        @if($accountRole->name === 'super_admin')
                                            <div class="text-[11px] text-slate-500 font-medium">
                                                Akun sistem utama tertanam permanen (akun tunggal)
                                            </div>
                                        @else
                                            <div class="text-[11px] text-slate-500 font-mono">
                                                {{ $accountRole->users->count() }} akun terdaftar memegang peran ini
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($accountRole->name === 'super_admin')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span>Akun Sistem Utama (Tunggal)</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <button 
                                            type="button" 
                                            wire:click="toggleCreateUser" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $isCreatingUser ? 'bg-slate-200 text-slate-700 font-bold' : 'bg-[#0B3A85] text-white font-bold hover:bg-[#07275E]' }} text-xs transition-colors cursor-pointer shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span>{{ $isCreatingUser ? 'Tutup Form' : 'Buat Akun Baru' }}</span>
                                        </button>

                                        <button 
                                            type="button" 
                                            wire:click="toggleAssignUser" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $isAssigningUser ? 'bg-slate-200 text-slate-700 font-bold' : 'bg-white text-indigo-700 border border-indigo-200 hover:bg-indigo-50 font-bold' }} text-xs transition-colors cursor-pointer shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                            </svg>
                                            <span>{{ $isAssigningUser ? 'Tutup' : 'Tugaskan Akun Ada' }}</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Form Tambah Akun Baru untuk Role Ini --}}
                            @if($isCreatingUser)
                                <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/40 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-extrabold text-[#0B3A85] text-xs uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                            </svg>
                                            <span>Buat Akun Baru untuk Role {{ $roleMeta['label'] }}</span>
                                        </h5>
                                        <button type="button" wire:click="toggleCreateUser" class="text-slate-400 hover:text-slate-600 text-xs">Batal</button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Nama Lengkap Personil *</label>
                                            <input 
                                                type="text" 
                                                wire:model="new_user_name" 
                                                placeholder="Contoh: Bpk. Ahmad Dahlan"
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                            >
                                            @error('new_user_name') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Alamat Email Login *</label>
                                            <input 
                                                type="email" 
                                                wire:model="new_user_email" 
                                                placeholder="Contoh: nama@simlibas.id"
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                            >
                                            @error('new_user_email') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Nomor HP / WhatsApp</label>
                                            <input 
                                                type="text" 
                                                wire:model="new_user_phone" 
                                                placeholder="Contoh: 0812-3456-7890"
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                            >
                                            @error('new_user_phone') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Password Baru (min. 6 karakter) *</label>
                                            <div class="relative">
                                                <input 
                                                    :type="showNewPassword ? 'text' : 'password'" 
                                                    wire:model="new_user_password" 
                                                    placeholder="Minimal 6 karakter..."
                                                    class="w-full pl-3 pr-9 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                                >
                                                <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('new_user_password') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Konfirmasi Password Baru *</label>
                                            <input 
                                                type="password" 
                                                wire:model="new_user_password_confirmation" 
                                                placeholder="Ulangi password baru..."
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                            >
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 pt-2 border-t border-blue-200/60">
                                        <button 
                                            type="button" 
                                            wire:click="toggleCreateUser" 
                                            class="px-3.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50"
                                        >
                                            Batal
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="createUserForRole" 
                                            class="px-4 py-1.5 rounded-lg bg-[#0B3A85] text-white font-bold text-xs hover:bg-[#07275E] shadow-2xs"
                                        >
                                            Simpan & Daftarkan Akun
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- Form Tugaskan Pengguna Terdaftar --}}
                            @if($isAssigningUser)
                                <div class="p-4 rounded-xl border border-indigo-200 bg-indigo-50/40 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-extrabold text-indigo-900 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            <span>Tugaskan Akun Warga/Pengurus ke Role Ini</span>
                                        </h5>
                                        <button type="button" wire:click="toggleAssignUser" class="text-slate-400 hover:text-slate-600 text-xs">Batal</button>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 text-[11px] mb-1">Pilih Pengguna Terdaftar *</label>
                                        <select 
                                            wire:model="assign_user_id" 
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                        >
                                            <option value="">-- Pilih Akun Pengguna --</option>
                                            @foreach($allUsers as $u)
                                                @if(! $accountRole->users->contains('id', $u->id))
                                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('assign_user_id') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex justify-end gap-2 pt-1">
                                        <button 
                                            type="button" 
                                            wire:click="toggleAssignUser" 
                                            class="px-3.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50"
                                        >
                                            Batal
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="assignExistingUser" 
                                            class="px-4 py-1.5 rounded-lg bg-indigo-700 text-white font-bold text-xs hover:bg-indigo-800 shadow-2xs"
                                        >
                                            Tugaskan Peran Ini
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- Daftar Akun Pemegang Role Saat Ini --}}
                            <div>
                                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Daftar Akun Pemegang Role Saat Ini ({{ $accountRole->users->count() }})</span>
                                    </span>
                                </h5>

                                @if($accountRole->users->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($accountRole->users as $u)
                                            @if($editingUserId === $u->id)
                                                {{-- Inline Form Edit Akun & Password --}}
                                                <div class="p-4 rounded-xl border-2 border-[#0B3A85] bg-blue-50/20 space-y-3">
                                                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                                        <div class="flex items-center gap-2">
                                                            <span class="p-1.5 rounded-lg bg-blue-100 text-[#0B3A85]">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </span>
                                                            <div>
                                                                <div class="font-extrabold text-[#0F1F3D] text-xs">Ubah Data Akun & Password</div>
                                                                <div class="text-[10.5px] text-slate-500 font-mono">ID Pengguna #{{ $u->id }}</div>
                                                            </div>
                                                        </div>
                                                        <button type="button" wire:click="cancelEditUser" class="text-slate-400 hover:text-slate-600 text-xs font-bold">
                                                            Batal
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        {{-- 1. Nama Akun --}}
                                                        <div>
                                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">
                                                                Nama Lengkap Personil <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input 
                                                                type="text" 
                                                                wire:model="edit_user_name" 
                                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-bold focus:outline-none focus:border-[#0B3A85]"
                                                            >
                                                            @error('edit_user_name') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                                        </div>

                                                        {{-- 2. Email Login --}}
                                                        <div>
                                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">
                                                                Alamat Email Login <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input 
                                                                type="email" 
                                                                wire:model="edit_user_email" 
                                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-mono focus:outline-none focus:border-[#0B3A85]"
                                                            >
                                                            @error('edit_user_email') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                                        </div>

                                                        {{-- 3. Nomor HP --}}
                                                        <div class="sm:col-span-2">
                                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">
                                                                Nomor HP / WhatsApp
                                                            </label>
                                                            <input 
                                                                type="text" 
                                                                wire:model="edit_user_phone" 
                                                                placeholder="Contoh: 0812-7400-1501"
                                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                                            >
                                                            @error('edit_user_phone') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                                        </div>

                                                        {{-- 4. Password Baru (Opsional) --}}
                                                        <div>
                                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">
                                                                Password Baru <span class="text-slate-400 font-normal">(Kosongkan jika tetap)</span>
                                                            </label>
                                                            <div class="relative">
                                                                <input 
                                                                    :type="showEditPassword ? 'text' : 'password'" 
                                                                    wire:model="edit_user_password" 
                                                                    placeholder="Ketik password baru jika ingin diubah..."
                                                                    class="w-full pl-3 pr-9 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                                                >
                                                                <button type="button" @click="showEditPassword = !showEditPassword" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            @error('edit_user_password') <span class="text-rose-600 text-[10.5px] font-bold block mt-0.5">{{ $message }}</span> @enderror
                                                        </div>

                                                        {{-- 5. Konfirmasi Password Baru --}}
                                                        <div>
                                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">
                                                                Ulangi Password Baru
                                                            </label>
                                                            <input 
                                                                type="password" 
                                                                wire:model="edit_user_password_confirmation" 
                                                                placeholder="Ulangi kata sandi baru..."
                                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]"
                                                            >
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200">
                                                        <button 
                                                            type="button" 
                                                            wire:click="cancelEditUser" 
                                                            class="px-3.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors"
                                                        >
                                                            Batal
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            wire:click="updateUserAccount" 
                                                            class="px-4 py-1.5 rounded-lg bg-[#0B3A85] text-white font-bold text-xs hover:bg-[#07275E] transition-all shadow-2xs"
                                                        >
                                                            Simpan Kredensial Akun
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Tampilan Normal Card Akun Pengguna --}}
                                                <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-colors flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-2xs">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 font-extrabold flex items-center justify-center text-sm shrink-0">
                                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-extrabold text-[#0F1F3D] text-xs sm:text-[13px]">{{ $u->name }}</span>
                                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9.5px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                    {{ $u->status ?? 'aktif' }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                                                <span class="font-mono text-slate-600 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">{{ $u->email }}</span>
                                                                @if($u->phone)
                                                                    <span>• {{ $u->phone }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                                        {{-- Tombol Ubah Akun & Password --}}
                                                        <button 
                                                            type="button" 
                                                            wire:click="startEditUser({{ $u->id }})" 
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 text-xs font-bold transition-all cursor-pointer shadow-2xs"
                                                            title="Ubah nama, email, atau password pengguna ini"
                                                        >
                                                            <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                            </svg>
                                                            <span>Ubah Akun & Password</span>
                                                        </button>

                                                        {{-- Tombol Lepas Role (Dihilangkan untuk Super Admin) --}}
                                                        @if($accountRole->name !== 'super_admin')
                                                            <button 
                                                                type="button" 
                                                                wire:click="removeUserFromRole({{ $u->id }})" 
                                                                wire:confirm="Yakin ingin melepaskan role '{{ $accountRole->name }}' dari akun {{ $u->name }}?"
                                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:border-rose-300 transition-all cursor-pointer shadow-2xs"
                                                                title="Lepaskan peran dari akun ini"
                                                            >
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-8 rounded-xl border border-dashed border-slate-200 text-center text-slate-400 bg-slate-50/50">
                                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <p class="font-bold text-slate-700 text-xs">Belum ada akun pengguna yang memegang peran ini.</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Gunakan tombol "Buat Akun Baru" atau "Tugaskan Akun Ada" di atas untuk mengisi pemegang peran ini.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Footer Modal --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end shrink-0">
                        <button 
                            type="button" 
                            @click="accountModalOpen = false; $wire.closeManageAccounts()" 
                            class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL DETAIL ROLE (HAK AKSES LENGKAP & DAFTAR USER)
             ==================================================================== --}}
        <div 
            x-show="detailModalOpen" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-detail-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="detailModalOpen = false; $wire.closeDetail()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    x-show="detailModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-2xl border border-slate-200 max-h-[90vh] flex flex-col" 
                    @click.stop
                >
                    {{-- Header Modal Deep Navy --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Rincian Peran & Hak Akses</h3>
                                <p class="text-[11px] text-white/70">
                                    {{ $detailRole ? 'Role #' . $detailRole->id . ' • ' . \App\Filament\Resources\Roles\Pages\ListRoles::getRoleDetails($detailRole->name)['label'] : 'Detail Role' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Body Modal Scrollable --}}
                    <div class="p-5 overflow-y-auto space-y-5 text-xs text-slate-700">
                        @if($detailRole)
                            @php
                                $roleMeta = \App\Filament\Resources\Roles\Pages\ListRoles::getRoleDetails($detailRole->name);
                            @endphp
                            {{-- Info Banner Role --}}
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-base font-black text-[#0F1F3D]">{{ $roleMeta['label'] }}</h4>
                                        <span class="font-mono text-[11px] px-2 py-0.5 rounded font-bold bg-white text-[#0B3A85] border border-slate-200">
                                            {{ $detailRole->name }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        {{ $roleMeta['desc'] }}
                                    </p>
                                </div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $detailRole->permissions->count() }} Hak Akses Aktif
                                    </span>
                                </div>
                            </div>

                            {{-- Personil Pengurus yang Memiliki Role Ini --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Pengguna dengan Role Ini ({{ $detailRole->users->count() }})</span>
                                    </h5>
                                    <button 
                                        type="button" 
                                        wire:click="openManageAccounts({{ $detailRole->id }})" 
                                        class="text-[11px] font-bold text-[#0B3A85] hover:underline cursor-pointer"
                                    >
                                        Kelola Akun & Kredensial →
                                    </button>
                                </div>

                                @if($detailRole->users->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($detailRole->users as $u)
                                            <div class="p-2.5 rounded-xl border border-slate-200 bg-white flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-[#0B3A85] text-white font-bold flex items-center justify-center text-xs shrink-0">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <div class="truncate">
                                                    <div class="font-bold text-slate-800 truncate">{{ $u->name }}</div>
                                                    <div class="text-[10.5px] text-slate-400 font-mono truncate">{{ $u->email }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-3 rounded-xl border border-dashed border-slate-200 text-center text-slate-400 text-xs">
                                        Belum ada akun pengguna yang ditugaskan peran ini.
                                    </div>
                                @endif
                            </div>

                            {{-- Matriks Hak Akses per Modul --}}
                            <div>
                                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Matriks Hak Akses per Modul</span>
                                </h5>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($detailPermissionsByModule as $mKey => $mod)
                                        @php
                                            $activeCount = count($mod['active_permissions']);
                                            $totalCount = $mod['total_in_module'];
                                        @endphp
                                        <div class="p-3.5 rounded-xl border {{ $activeCount > 0 ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50/50 opacity-60' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-bold text-slate-800 text-xs">{{ $mod['title'] }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $activeCount > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                                    {{ $activeCount }} / {{ $totalCount }}
                                                </span>
                                            </div>

                                            @if($activeCount > 0)
                                                <ul class="space-y-1">
                                                    @foreach($mod['active_permissions'] as $pName => $pLabel)
                                                        <li class="flex items-start gap-1.5 text-[11px] text-slate-600">
                                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            <span>{{ $pLabel }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-[10.5px] text-slate-400 italic">Tidak memiliki hak akses di modul ini.</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Modal --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end shrink-0">
                        <button 
                            type="button" 
                            @click="detailModalOpen = false; $wire.closeDetail()" 
                            class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                        >
                            Tutup Rincian
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL FORM: TAMBAH / UBAH ROLE & CHECKBOX PERMISSIONS
             ==================================================================== --}}
        <div 
            x-show="formModalOpen" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-form-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="formModalOpen = false; $wire.closeForm()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    x-show="formModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-2xl border border-slate-200 max-h-[90vh] flex flex-col" 
                    @click.stop
                >
                    {{-- Header Modal Deep Navy --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">
                                    {{ $isEditing ? 'Ubah Konfigurasi Hak Akses Role' : 'Tambah Role Struktural Baru' }}
                                </h3>
                                <p class="text-[11px] text-white/70">
                                    {{ $isEditing ? 'Sesuaikan wewenang modul untuk role ' . $form_name : 'Definisikan identitas peran baru dan centang hak akses yang diizinkan' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="formModalOpen = false; $wire.closeForm()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Content Scrollable --}}
                    <form wire:submit="saveRole" class="flex flex-col flex-1 overflow-hidden">
                        <div class="p-5 overflow-y-auto space-y-4 text-xs text-slate-700">
                            {{-- Input Nama Role (Slug) --}}
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                                <div>
                                    <label class="block font-bold text-slate-800 text-xs mb-1">
                                        Identifier Nama Role (Slug) <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_name" 
                                        placeholder="Contoh: koordinator_humas, staf_posyandu"
                                        {{ $isEditing && in_array($form_name, \App\Filament\Resources\Roles\Pages\ListRoles::CORE_ROLES) ? 'readonly' : '' }}
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-mono {{ $isEditing && in_array($form_name, \App\Filament\Resources\Roles\Pages\ListRoles::CORE_ROLES) ? 'bg-slate-100 text-slate-500 cursor-not-allowed' : 'bg-white' }}"
                                    >
                                    @error('form_name') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                                    <p class="text-[10.5px] text-slate-400 mt-1">
                                        Gunakan huruf kecil, angka, dan garis bawah (_). Identifier ini digunakan sistem untuk pengecekan wewenang.
                                    </p>
                                </div>
                            </div>

                            {{-- Toolbar Cepat Pemilihan Hak Akses --}}
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-1 border-t border-slate-100">
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-sm">Pilih Hak Akses (Permissions)</h4>
                                    <p class="text-[11px] text-slate-400">
                                        {{ count($selectedPermissions) }} hak akses dipilih dari total 36
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button 
                                        type="button" 
                                        wire:click="selectAllPermissions" 
                                        class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-bold transition-all cursor-pointer"
                                    >
                                        ✓ Centang Semua
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="deselectAllPermissions" 
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs font-bold transition-all cursor-pointer"
                                    >
                                        ✕ Kosongkan Semua
                                    </button>
                                </div>
                            </div>

                            {{-- Grid Permissions per Modul --}}
                            <div class="space-y-3">
                                @foreach($permissionModules as $mKey => $mod)
                                    @php
                                        $modKeys = array_keys($mod['permissions']);
                                        $selectedInMod = count(array_intersect($modKeys, $selectedPermissions));
                                        $isAllModSelected = $selectedInMod === count($modKeys);
                                    @endphp
                                    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                                        {{-- Header Modul --}}
                                        <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full {{ $selectedInMod > 0 ? 'bg-[#0B3A85]' : 'bg-slate-300' }}"></span>
                                                <span class="font-extrabold text-slate-800 text-xs">{{ $mod['title'] }}</span>
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-white text-slate-600 border border-slate-200">
                                                    {{ $selectedInMod }} / {{ count($modKeys) }}
                                                </span>
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="toggleAllInModule('{{ $mKey }}')" 
                                                class="text-[11px] font-bold {{ $isAllModSelected ? 'text-rose-600 hover:text-rose-700' : 'text-[#0B3A85] hover:text-[#07275E]' }} cursor-pointer"
                                            >
                                                {{ $isAllModSelected ? 'Batalkan Modul Ini' : 'Pilih Semua Modul Ini' }}
                                            </button>
                                        </div>

                                        {{-- List Checkbox Permissions --}}
                                        <div class="p-3.5 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                            @foreach($mod['permissions'] as $pName => $pLabel)
                                                <label class="flex items-start gap-2.5 p-2 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                                                    <input 
                                                        type="checkbox" 
                                                        wire:model="selectedPermissions" 
                                                        value="{{ $pName }}" 
                                                        class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85] mt-0.5 shrink-0"
                                                    >
                                                    <div>
                                                        <div class="font-bold text-slate-800 text-xs leading-tight">
                                                            {{ $pLabel }}
                                                        </div>
                                                        <div class="text-[10px] font-mono text-slate-400 mt-0.5">
                                                            {{ $pName }}
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer Form --}}
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                            <button 
                                type="button" 
                                @click="formModalOpen = false; $wire.closeForm()" 
                                class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-sm cursor-pointer"
                            >
                                {{ $isEditing ? 'Simpan Perubahan Hak Akses' : 'Simpan Role Baru' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             8. MODAL KONFIRMASI HAPUS ROLE (ALPINE JS)
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-delete-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="closeDeleteConfirm()"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200 p-6 text-center"
                    @click.stop
                >
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <h4 class="text-base font-black text-slate-800">Hapus Peran Kustom?</h4>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Apakah Anda yakin ingin menghapus role <span class="font-bold text-slate-800 font-mono" x-text="deleteRoleName"></span>? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex items-center justify-center gap-2 mt-5">
                        <button 
                            type="button" 
                            @click="closeDeleteConfirm()" 
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="executeDelete()" 
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors shadow-xs cursor-pointer"
                        >
                            Ya, Hapus Role
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
