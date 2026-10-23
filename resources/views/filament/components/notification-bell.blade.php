@php
    $user = auth()->user();
    if (!$user || !$user->canAccessPanel()) {
        return;
    }
    $initialUnreadCount = \App\Models\Notification::where('user_id', $user->id)->unread()->count();
    $notificationUrl = \App\Filament\Pages\Notifications::getUrl();
@endphp

<div class="relative inline-flex items-center my-auto mr-2"
     x-data="{
         unreadCount: {{ $initialUnreadCount }},
         pollTimer: null,
         init() {
             // Polling setiap 20 detik untuk memperbarui angka counter badge unread
             this.pollTimer = setInterval(() => {
                 fetch('{{ route('admin.api.notifications.index') }}', {
                     headers: {
                         'Accept': 'application/json',
                         'X-Requested-With': 'XMLHttpRequest'
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     if (data && typeof data.unread_count !== 'undefined') {
                         this.unreadCount = data.unread_count;
                     }
                 })
                 .catch(() => {});
             }, 20000);
         }
     }">
    {{-- Tombol Lonceng: Langsung mengarah ke Halaman Notifikasi (Tanpa Popup) --}}
    <a href="{{ $notificationUrl }}"
       class="relative flex items-center justify-center w-9 h-9 text-slate-500 hover:text-[#0B3A85] hover:bg-slate-100 rounded-xl transition duration-150 focus:outline-none cursor-pointer group"
       title="Buka Pusat Notifikasi"
       aria-label="Buka Pusat Notifikasi">
        <svg class="w-5 h-5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge Counter Unread --}}
        <span x-show="unreadCount > 0"
              style="{{ $initialUnreadCount > 0 ? '' : 'display: none;' }}"
              class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[1.2rem] h-[1.2rem] px-1 text-[10px] font-black text-white bg-rose-600 rounded-full ring-2 ring-white shadow-xs"
              x-text="unreadCount > 99 ? '99+' : unreadCount">
            {{ $initialUnreadCount > 0 ? ($initialUnreadCount > 99 ? '99+' : $initialUnreadCount) : '' }}
        </span>
    </a>
</div>
