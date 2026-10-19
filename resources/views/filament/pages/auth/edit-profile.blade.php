<x-filament-panels::page>
    <div class="space-y-4">
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, PANDUAN (!), JUDUL & STATUS AKUN
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>PROFIL PENGURUS RT 15</span>
                    <x-page-guide 
                        title="Profil Pengurus"
                        category="PROFIL PENGURUS RT 15"
                        description="Pusat pengelolaan identitas akun pengurus RT 15, kontak aktif WhatsApp koordinasi pengurus, serta pengaturan kata sandi keamanan akses SIM-LiBas."
                        :steps="[
                            ['title' => 'Periksa Jabatan & Peran', 'desc' => 'Pastikan peran kepengurusan Anda di RT 15 telah terdaftar dengan benar sesuai SK kepengurusan aktif.'],
                            ['title' => 'Perbarui Kontak', 'desc' => 'Pastikan nomor WhatsApp aktif untuk kemudahan koordinasi antarpengurus dan komunikasi warga.'],
                            ['title' => 'Keamanan Kata Sandi', 'desc' => 'Ganti kata sandi secara berkala dengan kombinasi aman untuk melindungi akses panel pengurus RT 15.'],
                            ['title' => 'Simpan Perubahan', 'desc' => 'Klik tombol \'Simpan Perubahan\' di bagian bawah halaman untuk menerapkan seluruh pembaruan identitas.']
                        ]"
                        role="Seluruh Akun Pengurus RT 15"
                        :tips="[
                            'Jika akun Anda terhubung dengan data warga RT 15, pembaruan nama dan kontak akan otomatis diselaraskan ke database kependudukan.',
                            'Kosongkan bagian kata sandi baru jika Anda hanya ingin memperbarui nama atau nomor WhatsApp.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Profil Pengurus
                    </h1>
                </div>
            </div>

            {{-- Badge Status Akun Pengurus --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Akun Pengurus Aktif</span>
                </span>
            </div>
        </div>

        {{-- ====================================================================
             2. KPI OVERVIEW CARDS (4 KARTU IDENTITAS & STATUS AKUN PENGURUS)
             ==================================================================== --}}
        @php
            /** @var \App\Models\User|null $currentUser */
            $currentUser = auth()->user();
            $roleMap = [
                'super_admin' => 'Super Admin (Administrator)',
                'admin' => 'Admin RT',
                'ketua_rt' => 'Ketua RT 15',
                'sekretaris' => 'Sekretaris RT 15',
                'bendahara' => 'Bendahara RT 15',
                'koordinator_bank_sampah' => 'Koordinator Bank Sampah',
                'koordinator_keamanan' => 'Koordinator Keamanan',
                'rw_kelurahan' => 'Perwakilan RW / Kelurahan',
                'warga' => 'Warga RT 15',
            ];
            $currentRole = $currentUser ? ($currentUser->getRoleNames()->map(fn ($r) => $roleMap[$r] ?? ucfirst(str_replace('_', ' ', $r)))->join(', ') ?: 'Pengurus RT 15') : 'Pengurus RT 15';
            
            $cleanWa = $currentUser && $currentUser->phone ? preg_replace('/[^0-9]/', '', (string) $currentUser->phone) : '';
            if ($cleanWa && str_starts_with($cleanWa, '0')) {
                $cleanWa = '62' . substr($cleanWa, 1);
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Kartu 1: Jabatan / Peran Pengurus --}}
            <div class="bg-white rounded-xl p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0B3A85]/10 text-[#0B3A85] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Peran Pengurus</span>
                    <h4 class="text-xs sm:text-sm font-extrabold text-[#0F1F3D] truncate" title="{{ $currentRole }}">{{ $currentRole }}</h4>
                    <p class="text-[10.5px] text-blue-700 font-semibold truncate mt-0.5">Otoritas Panel SIM-LiBas</p>
                </div>
            </div>

            {{-- Kartu 2: Nama Pengurus & Status Warga --}}
            <div class="bg-white rounded-xl p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#1B7A3A]/10 text-[#1B7A3A] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Nama Pengurus</span>
                    <h4 class="text-xs sm:text-sm font-extrabold text-[#0F1F3D] truncate" title="{{ $currentUser?->name }}">{{ $currentUser?->name ?? 'Pengurus RT' }}</h4>
                    <p class="text-[10.5px] text-slate-500 truncate mt-0.5">
                        {{ $currentUser?->resident ? 'Warga Tetap RT 15' : 'Administrator Terdaftar' }}
                    </p>
                </div>
            </div>

            {{-- Kartu 3: Nomor Kontak / WhatsApp --}}
            <div class="bg-white rounded-xl p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#19A7E8]/10 text-[#19A7E8] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">No. Telepon / WA</span>
                    @if($currentUser?->phone)
                        @if($cleanWa)
                            <a 
                                href="https://wa.me/{{ $cleanWa }}" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap text-xs truncate max-w-full"
                                title="Chat WhatsApp ({{ $currentUser->phone }})"
                            >
                                <span class="select-all font-mono">{{ $currentUser->phone }}</span>
                                <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                            </a>
                        @else
                            <h4 class="text-xs font-mono font-bold text-[#0F1F3D] truncate">{{ $currentUser->phone }}</h4>
                        @endif
                        <p class="text-[10px] text-slate-400 truncate mt-0.5">Kontak Aktif Koordinasi</p>
                    @else
                        <h4 class="text-xs font-sans italic text-slate-400">Belum diisi</h4>
                        <p class="text-[10px] text-slate-400 truncate mt-0.5">Lengkapi profil Anda</p>
                    @endif
                </div>
            </div>

            {{-- Kartu 4: Akun & Email Resmi --}}
            <div class="bg-white rounded-xl p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#F2A900]/10 text-[#B45309] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Keamanan Akun</span>
                    <h4 class="text-xs font-bold text-[#0F1F3D] truncate" title="{{ $currentUser?->email }}">{{ $currentUser?->email }}</h4>
                    <p class="text-[10.5px] text-emerald-600 font-semibold truncate mt-0.5">Proteksi Sandi Aktif</p>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             3. FORMULIR EDIT PROFIL (FILAMENT CONTENT SCHEMAS)
             ==================================================================== --}}
        <div>
            {{ $this->content }}
        </div>
    </div>

    <style>
        /* Memastikan tombol aksi formulir (Simpan Perubahan) memiliki teks putih kontras tinggi */
        .fi-btn.fi-color-primary,
        .fi-btn.fi-color-primary *,
        .fi-btn.fi-color-primary .fi-btn-label,
        .fi-btn.fi-color-primary span,
        .fi-btn.fi-color-primary svg {
            color: #ffffff !important;
        }

        .fi-btn.fi-color-primary:hover,
        .fi-btn.fi-color-primary:hover *,
        .fi-btn.fi-color-primary:hover .fi-btn-label,
        .fi-btn.fi-color-primary:hover span,
        .fi-btn.fi-color-primary:hover svg {
            color: #ffffff !important;
        }

        .fi-btn.fi-color-gray {
            border: 1px solid #DDE5F0 !important;
            background-color: #ffffff !important;
            color: #334A6E !important;
        }

        .fi-btn.fi-color-gray:hover {
            background-color: #F8FAFC !important;
            color: #0F1F3D !important;
            border-color: #CBD5E1 !important;
        }
    </style>
</x-filament-panels::page>
