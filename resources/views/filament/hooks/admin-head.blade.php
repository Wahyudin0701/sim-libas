<script>
    (function () {
        try {
            // Bersihkan class usang yang mungkin tersimpan & pastikan dark mode nonaktif
            document.documentElement.classList.remove('fi-sidebar-collapsed', 'fi-sidebar-expanded', 'dark');
            localStorage.setItem('theme', 'light');

            // Matikan transisi sesaat saat reload agar elemen tidak berkedip/meluncur dari kiri ke kanan
            document.documentElement.classList.add('preload-no-transition');

            // Cek state collapsed sidebar dari localStorage sebelum render awal browser
            var isDesktop = window.innerWidth >= 1024;
            var isDesktopOpen = localStorage.getItem('filament:sidebar:desktop_open');
            var isOpen = localStorage.getItem('filament:sidebar:open');
            var isClosed = isDesktop ? (isDesktopOpen === 'false') : (isOpen === 'false');

            if (isClosed) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            } else {
                document.documentElement.classList.remove('sidebar-is-collapsed');
            }

            // Buka grup navigasi sidebar secara default jika belum pernah diatur pengguna
            if (localStorage.getItem('filament:sidebar:collapsed_groups') === null) {
                localStorage.setItem('filament:sidebar:collapsed_groups', JSON.stringify([]));
            }
        } catch (e) {}

        // Respons klik instan (Capture Phase): langsung pasang class di frame 0 tanpa menunggu event loop selesai
        document.addEventListener('click', function (e) {
            var closeBtn = e.target.closest('.fi-topbar-close-collapse-sidebar-btn, .fi-sidebar-close-collapse-sidebar-btn');
            if (closeBtn) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
            var openBtn = e.target.closest('.fi-topbar-open-collapse-sidebar-btn, .fi-sidebar-open-collapse-sidebar-btn');
            if (openBtn) {
                document.documentElement.classList.remove('sidebar-is-collapsed');
            }
        }, true);

        // Sinkronisasi reaktif dengan Alpine Store untuk menjaga keutuhan state
        document.addEventListener('alpine:init', function () {
            if (window.Alpine) {
                window.Alpine.effect(function () {
                    var store = window.Alpine.store('sidebar');
                    if (store) {
                        if (store.isOpen === false && window.innerWidth >= 1024) {
                            document.documentElement.classList.add('sidebar-is-collapsed');
                        } else if (store.isOpen === true && window.innerWidth >= 1024) {
                            document.documentElement.classList.remove('sidebar-is-collapsed');
                        }
                    }
                });
            }
        });

        // Aktifkan kembali transisi halus setelah render awal browser selesai
        window.addEventListener('DOMContentLoaded', function () {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.documentElement.classList.remove('preload-no-transition');
                });
            });
        });
    })();
</script>
