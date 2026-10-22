@extends('layouts.public')

@section('title', 'Agenda Kegiatan Warga RT 15 | SIM-LiBas Pematang Sulur')

@section('content')
    <!-- 1. HERO HEADER DIREKTORI AGENDA -->
    <section class="sim-gradient text-white relative overflow-hidden py-10 sm:py-20">
        <!-- Subtle background shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs [Beranda / Agenda] -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-3 sm:mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-bold">Agenda</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-3 sm:mb-4 border border-white/20">
                Kalender Kegiatan Lingkungan
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-2 sm:mb-4">
                Agenda Kegiatan Warga RT 15
            </h1>
            <p class="hidden sm:block text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Jadwal kegiatan gotong royong, posyandu, musyawarah RT, senam sehat, serta agenda perayaan bersama warga RT 15 Pematang Sulur.
            </p>
        </div>
    </section>

    <!-- 2. DAFTAR AGENDA & PENCARIAN REAL-TIME -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 relative z-20 pb-16 sm:pb-24">
        
        <!-- Toolbar Pencarian & Filter Real-Time (Dibungkus Card) -->
        <div class="bg-white border border-border-soft rounded-2xl shadow-sm p-4 sm:p-5 mb-6">
            <form action="{{ route('public.agenda.index') }}" method="GET" id="search-form" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           id="search-input"
                           name="search"
                           value="{{ request('search', request('q')) }}"
                           placeholder="Cari nama agenda, lokasi, atau kegiatan..."
                           autocomplete="off"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-sm text-text-main shadow-2xs focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 placeholder:text-gray-400 transition">
                </div>

                <!-- Dropdown Kategori Konsisten -->
                <div class="sm:w-56 shrink-0">
                    <select id="kategori-select" name="kategori" class="w-full py-2.5 px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-sm text-text-main shadow-2xs focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-medium transition cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="kerja_bakti" {{ request('kategori') == 'kerja_bakti' ? 'selected' : '' }}>Gotong Royong</option>
                        <option value="rapat" {{ request('kategori') == 'rapat' ? 'selected' : '' }}>Rapat</option>
                        <option value="pengajian" {{ request('kategori') == 'pengajian' ? 'selected' : '' }}>Keagamaan</option>
                        <option value="perayaan" {{ request('kategori') == 'perayaan' ? 'selected' : '' }}>Perayaan</option>
                        <option value="arisan" {{ request('kategori') == 'arisan' ? 'selected' : '' }}>Arisan</option>
                        <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Tombol Reset Filter Konsisten -->
                <button type="button"
                        id="search-reset-btn"
                        title="Reset Filter"
                        style="{{ (request('search') || request('q') || (request('kategori') && request('kategori') !== 'semua')) ? '' : 'display: none;' }}"
                        class="items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition shadow-2xs shrink-0 {{ (request('search') || request('q') || (request('kategori') && request('kategori') !== 'semua')) ? 'inline-flex' : 'hidden' }}">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="text-red-600 font-bold">Reset Filter</span>
                </button>
            </form>
        </div>

        <!-- Ringkasan Hasil Pencarian Real-Time -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-text-muted mb-6 gap-3 px-1">
            <div>
                Menampilkan <span id="agenda-count" class="font-bold text-primary-900">{{ $events->total() }}</span> agenda kegiatan
                <span id="search-keyword-wrapper" class="{{ (request('search') || request('q')) ? '' : 'hidden' }}">
                    untuk kata kunci "<span id="search-keyword-display" class="font-semibold text-primary-900">{{ request('search', request('q')) }}</span>"
                </span>
                <span id="category-filter-display" class="{{ request('kategori') ? '' : 'hidden' }}">
                    dalam kategori <span id="category-name-display" class="font-semibold text-primary-900">{{ request('kategori') ? ucwords(str_replace('_', ' ', request('kategori'))) : '' }}</span>
                </span>
            </div>
        </div>

        <!-- Grid Kartu Agenda -->
        @if($events->isEmpty())
            <div class="bg-white rounded-3xl border border-border-soft p-12 text-center shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-primary-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-primary-900 mb-1">
                    {{ (!empty($search) || ($kategori && $kategori !== 'semua')) ? 'Agenda Tidak Ditemukan' : 'Belum Ada Agenda' }}
                </h3>
                <p class="text-xs sm:text-sm text-text-muted max-w-md mx-auto mb-6 leading-relaxed">
                    {{ (!empty($search) || ($kategori && $kategori !== 'semua')) ? 'Tidak ada kegiatan yang sesuai dengan kriteria filter pencarian Anda.' : 'Saat ini belum ada jadwal agenda kegiatan yang tercatat di sistem SIM-LiBas RT 15.' }}
                </p>
                @if(!empty($search) || ($kategori && $kategori !== 'semua'))
                    <a href="{{ route('public.agenda.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-700 text-white text-xs font-semibold hover:bg-primary-900 transition">
                        <span>Lihat Semua Agenda</span>
                    </a>
                @endif
            </div>
        @else
            <!-- Container Grid Kartu Agenda (Mobile 2 Kolom Selaras Katalog UMKM) -->
            <div id="agenda-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6">
                @foreach($events as $event)
                    <a href="{{ route('public.agenda.detail', $event->id) }}"
                       data-kategori="{{ strtolower($event->kategori) }}"
                       class="agenda-card sim-card p-3 sm:p-5 flex flex-col justify-between hover:border-primary-500 hover:shadow-xl hover:-translate-y-1 transition duration-200 group bg-white rounded-2xl cursor-pointer">
                        <div>
                            <!-- Header Card: Tanggal Kalender & Kategori -->
                            <div class="flex items-start gap-2 sm:gap-4 mb-2.5 sm:mb-4">
                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl sim-gradient text-white flex flex-col items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200 shadow-2xs">
                                    <span class="text-[8px] sm:text-[11px] font-bold leading-none uppercase">{{ $event->tanggal_mulai->format('M') }}</span>
                                    <span class="text-sm sm:text-xl font-extrabold leading-none mt-0.5 sm:mt-1">{{ $event->tanggal_mulai->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1 sm:gap-1.5 flex-wrap mb-0.5 sm:mb-1">
                                        <span class="inline-block px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded text-[8px] sm:text-[10px] font-bold bg-blue-50 text-primary-700 border border-blue-100 truncate max-w-full">
                                            {{ ucwords(str_replace('_', ' ', $event->kategori)) }}
                                        </span>
                                    </div>
                                    <span class="text-[9px] sm:text-xs text-text-muted font-medium block truncate">
                                        {{ $event->tanggal_mulai->format('H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <!-- Judul Agenda -->
                            <h2 class="text-xs sm:text-lg font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug mb-1 sm:mb-2 line-clamp-2">
                                {{ $event->judul }}
                            </h2>

                            <!-- Lokasi & Penyelenggara -->
                            <div class="space-y-0.5 sm:space-y-1 text-[10px] sm:text-xs text-slate-600 mb-2 sm:mb-4">
                                <p class="flex items-center gap-1 sm:gap-1.5 truncate">
                                    <span class="shrink-0 text-[10px] sm:text-xs">📍</span>
                                    <span class="truncate">{{ $event->lokasi ?? 'RT 15 Pematang Sulur' }}</span>
                                </p>
                                @if($event->penyelenggara)
                                    <p class="flex items-center gap-1 sm:gap-1.5 truncate text-text-muted">
                                        <span class="shrink-0 text-[10px] sm:text-xs">👥</span>
                                        <span class="truncate">Oleh: <strong class="text-slate-700 font-semibold">{{ $event->penyelenggara }}</strong></span>
                                    </p>
                                @endif
                            </div>

                            <!-- Deskripsi Singkat -->
                            @if($event->deskripsi)
                                <p class="text-[10px] sm:text-xs text-text-muted line-clamp-2 leading-relaxed mb-2.5 sm:mb-6 font-medium">
                                    {{ $event->deskripsi }}
                                </p>
                            @endif
                        </div>

                        <!-- Footer Card: Lihat Detail Agenda -->
                        <div class="pt-2 sm:pt-4 border-t border-border-soft flex items-center justify-between">
                            <span class="text-[10px] sm:text-xs font-semibold text-primary-700 group-hover:text-primary-900 group-hover:translate-x-0.5 transition duration-200 inline-flex items-center gap-1 sm:gap-1.5">
                                <span>Lihat Detail Agenda</span>
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Empty State Real-Time (Muncul jika pencarian real-time tidak menemukan hasil) -->
            <div id="no-search-results" class="hidden bg-white border border-border-soft rounded-2xl p-8 sm:p-12 text-center w-full my-6 sm:my-8 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-primary-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-primary-900">Belum Ada Agenda yang Cocok</h3>
                <p class="text-xs sm:text-sm text-text-muted mt-1 leading-relaxed max-w-md mx-auto">
                    Tidak ditemukan agenda warga dengan kata kunci atau filter pencarian tersebut secara real-time.
                </p>
                <div class="mt-4">
                    <button type="button" id="clear-search-btn" class="inline-flex items-center text-xs font-semibold text-primary-700 hover:text-primary-900 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition">
                        Bersihkan Pencarian
                    </button>
                </div>
            </div>

            <!-- Navigasi Paginasi -->
            <div id="pagination-wrapper" class="mt-10">
                {{ $events->links() }}
            </div>
        @endif

        <!-- Banner Kontak Sekretariat RT -->
        <div class="mt-16 bg-white border border-border-soft rounded-3xl p-8 sm:p-12 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-wider text-gold-700 mb-2 block">Partisipasi Warga</span>
                <h3 class="text-xl sm:text-2xl font-bold text-primary-900 mb-2">
                    Ingin Mengadakan Kegiatan Bersama di Lingkungan RT 15?
                </h3>
                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Warga atau kelompok dasawisma/pemuda yang ingin berkolaborasi menyelenggarakan kegiatan positif dapat berkoordinasi langsung dengan Pengurus RT 15.
                </p>
            </div>
            <a href="{{ route('public.contact') }}" class="bg-primary-700 hover:bg-primary-900 text-white font-bold text-xs sm:text-sm px-6 py-3.5 rounded-xl transition shadow-sm hover:shadow shrink-0 flex items-center gap-2">
                <span>Hubungi Pengurus RT</span>
            </a>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const kategoriSelect = document.getElementById('kategori-select');
    const resetBtn = document.getElementById('search-reset-btn');
    const clearBtn = document.getElementById('clear-search-btn');
    const cards = document.querySelectorAll('.agenda-card');
    const grid = document.getElementById('agenda-grid');
    const noResultsEl = document.getElementById('no-search-results');
    const countEl = document.getElementById('agenda-count');
    const keywordWrapper = document.getElementById('search-keyword-wrapper');
    const keywordDisplay = document.getElementById('search-keyword-display');
    const categoryFilterDisplay = document.getElementById('category-filter-display');
    const categoryNameDisplay = document.getElementById('category-name-display');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    const totalCards = cards.length;

    function applySearch() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const selectedKategori = (kategoriSelect ? kategoriSelect.value : '').toLowerCase().trim();

        // Tampilkan/sembunyikan reset button
        if (resetBtn) {
            if (query !== '' || selectedKategori !== '') {
                resetBtn.classList.remove('hidden');
                resetBtn.classList.add('inline-flex');
                resetBtn.style.display = '';
            } else {
                resetBtn.classList.remove('inline-flex');
                resetBtn.classList.add('hidden');
                resetBtn.style.display = 'none';
            }
        }

        // Tampilkan/sembunyikan keyword indicator
        if (keywordWrapper && keywordDisplay) {
            if (query !== '') {
                keywordDisplay.textContent = searchInput.value.trim();
                keywordWrapper.classList.remove('hidden');
            } else {
                keywordWrapper.classList.add('hidden');
            }
        }

        // Tampilkan/sembunyikan category indicator
        if (categoryFilterDisplay && categoryNameDisplay) {
            if (selectedKategori !== '' && kategoriSelect) {
                const optText = kategoriSelect.options[kategoriSelect.selectedIndex].text;
                categoryNameDisplay.textContent = optText;
                categoryFilterDisplay.classList.remove('hidden');
            } else {
                categoryFilterDisplay.classList.add('hidden');
            }
        }

        let visibleCount = 0;
        cards.forEach(function (card) {
            const text = card.textContent.toLowerCase();
            const cardCategory = (card.getAttribute('data-kategori') || '').toLowerCase();

            const matchQuery = query === '' || text.includes(query);
            const matchCategory = selectedKategori === '' || cardCategory === selectedKategori;

            if (matchQuery && matchCategory) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update counter angka
        if (countEl) {
            countEl.textContent = visibleCount;
        }

        // Tampilkan placeholder jika hasil 0
        if (noResultsEl && grid) {
            if (visibleCount === 0 && totalCards > 0) {
                noResultsEl.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                noResultsEl.classList.add('hidden');
                grid.classList.remove('hidden');
            }
        }

        // Sembunyikan paginasi ketika pencarian / filter aktif
        if (paginationWrapper) {
            paginationWrapper.style.display = (query !== '' || selectedKategori !== '') ? 'none' : '';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applySearch);
    }

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', applySearch);
    }

    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applySearch();
        });
    }

    function resetSearch() {
        if (searchInput) searchInput.value = '';
        if (kategoriSelect) kategoriSelect.value = '';
        if (window.history.replaceState) {
            window.history.replaceState({}, '', window.location.pathname);
        }
        applySearch();
        if (searchInput) searchInput.focus();
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', resetSearch);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', resetSearch);
    }

    // Jalankan pencarian saat load agar status reset button dan counter selalu sinkron
    applySearch();
});
</script>
@endpush
