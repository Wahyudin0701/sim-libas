@php
    $user = auth()->user();
    if (!$user || !$user->canAccessPanel()) {
        return;
    }
    $initialUnreadCount = \App\Models\Notification::where('user_id', $user->id)->unread()->count();
@endphp

<div x-data="adminNotification({
        initialCount: {{ $initialUnreadCount }},
        apiUrl: '{{ route('admin.api.notifications.index') }}',
        readAllUrl: '{{ route('admin.api.notifications.read-all') }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    class="relative inline-flex items-center my-auto mr-2"
    x-cloak>
    
    <!-- Tombol Lonceng Notifikasi Pengurus -->
    <button type="button"
            @click="toggle()"
            :aria-expanded="open.toString()"
            class="relative flex items-center justify-center w-9 h-9 text-slate-500 hover:text-[#0B3A85] hover:bg-slate-100 rounded-xl transition duration-150 focus:outline-none cursor-pointer"
            title="Notifikasi Pengurus"
            aria-label="Notifikasi Pengurus">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <!-- Badge Counter Unread -->
        <span x-show="unreadCount > 0"
              style="display: none;"
              class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[1.2rem] h-[1.2rem] px-1 text-[10px] font-black text-white bg-rose-600 rounded-full ring-2 ring-white shadow-xs"
              x-text="unreadCount > 99 ? '99+' : unreadCount">
            {{ $initialUnreadCount > 0 ? ($initialUnreadCount > 99 ? '99+' : $initialUnreadCount) : '' }}
        </span>
    </button>

    <!-- Dropdown Panel Notifikasi Pengurus -->
    <div x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         style="display: none;"
         class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200/90 z-50 overflow-hidden origin-top-right">
        
        <!-- Dropdown Header -->
        <div class="px-4 py-3 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold text-[#0B3A85] uppercase tracking-wider">Notifikasi Pengurus</span>
                <span x-show="unreadCount > 0"
                      class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700"
                      x-text="unreadCount + ' baru'">
                </span>
            </div>
            <button x-show="unreadCount > 0"
                    type="button"
                    @click="markAllAsRead()"
                    class="text-[11px] font-semibold text-[#0B3A85] hover:underline cursor-pointer focus:outline-none">
                Tandai Semua Dibaca
            </button>
        </div>

        <!-- Daftar Notifikasi -->
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
            <!-- Loading state -->
            <template x-if="loading && notifications.length === 0">
                <div class="p-6 text-center text-xs text-slate-400">
                    <svg class="animate-spin w-5 h-5 mx-auto mb-2 text-[#0B3A85]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Memuat notifikasi aktivitas...
                </div>
            </template>

            <!-- Empty state -->
            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center text-slate-400">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Belum Ada Notifikasi Baru</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Semua pengajuan dan aktivitas warga telah dipantau.</p>
                </div>
            </template>

            <!-- Daftar Item Notifikasi -->
            <template x-for="item in notifications" :key="item.id">
                <div @click="openNotification(item)"
                     class="p-3.5 transition flex items-start gap-3 cursor-pointer group"
                     :class="item.is_read ? 'bg-white hover:bg-slate-50' : 'bg-sky-50/60 hover:bg-sky-50/90'">
                    
                    <!-- Icon Kategori Aktivitas -->
                    <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center mt-0.5 shadow-xs"
                         :class="{
                             'bg-blue-100 text-blue-700': item.category === 'letter',
                             'bg-amber-100 text-amber-700': item.category === 'complaint',
                             'bg-emerald-100 text-emerald-700': item.category === 'payment',
                             'bg-indigo-100 text-indigo-700': item.category === 'guest',
                             'bg-slate-100 text-slate-600': item.category === 'general'
                         }">
                        <!-- Icon Surat -->
                        <template x-if="item.category === 'letter'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </template>
                        <!-- Icon Pengaduan -->
                        <template x-if="item.category === 'complaint'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </template>
                        <!-- Icon Pembayaran Iuran -->
                        <template x-if="item.category === 'payment'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </template>
                        <!-- Icon Lapor Tamu -->
                        <template x-if="item.category === 'guest'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </template>
                        <!-- Icon General -->
                        <template x-if="item.category === 'general'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                    </div>

                    <!-- Isi Notifikasi -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-[#0B3A85] truncate leading-tight"
                                x-text="item.judul"></h4>
                            <span x-show="!item.is_read" class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                        </div>
                        <p class="text-[11px] text-slate-600 line-clamp-2 mt-1 leading-snug"
                           x-text="item.isi"></p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-[10px] text-slate-400 font-medium" x-text="item.time_ago"></span>
                            <span class="text-[10px] text-primary-700 font-semibold group-hover:underline">Buka &rarr;</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Dropdown Footer -->
        <div class="p-2.5 bg-slate-50/90 border-t border-slate-100 text-center">
            <span class="text-[10px] text-slate-400">Pusat Notifikasi Aktivitas Warga RT 15</span>
        </div>
    </div>
</div>

<script>
    function adminNotification(config) {
        return {
            open: false,
            loading: false,
            unreadCount: config.initialCount,
            notifications: [],
            pollTimer: null,

            init() {
                // Polling berkala setiap 20 detik untuk memeriksa pengajuan baru dari warga
                this.pollTimer = setInterval(() => {
                    this.fetchNotifications(true);
                }, 20000);
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.fetchNotifications(false);
                }
            },

            fetchNotifications(isBackground = false) {
                if (!isBackground) {
                    this.loading = true;
                }
                fetch(config.apiUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.unreadCount = data.unread_count ?? 0;
                    this.notifications = data.notifications ?? [];
                })
                .catch(err => {
                    console.error('Gagal memuat notifikasi pengurus:', err);
                })
                .finally(() => {
                    if (!isBackground) {
                        this.loading = false;
                    }
                });
            },

            openNotification(item) {
                if (!item.is_read) {
                    fetch('/admin/api/notifikasi/' + item.id + '/baca', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(() => {
                        item.is_read = true;
                        if (this.unreadCount > 0) this.unreadCount--;
                    })
                    .catch(e => console.error(e))
                    .finally(() => {
                        window.location.href = item.url;
                    });
                } else {
                    window.location.href = item.url;
                }
            },

            markAllAsRead() {
                fetch(config.readAllUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.unreadCount = 0;
                        this.notifications.forEach(n => n.is_read = true);
                    }
                })
                .catch(err => console.error(err));
            }
        };
    }
</script>
