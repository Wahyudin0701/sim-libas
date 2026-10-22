@props(['item', 'aspect' => 'aspect-[16/10]'])

@php
    $category = strtolower($item->kategori ?? 'lainnya');
    $categoryGradients = [
        'kuliner' => 'from-amber-600 via-orange-600 to-rose-700',
        'sembako' => 'from-emerald-700 via-teal-700 to-cyan-800',
        'jasa' => 'from-blue-700 via-indigo-700 to-slate-800',
        'kerajinan' => 'from-purple-700 via-fuchsia-700 to-pink-800',
        'fashion' => 'from-pink-600 via-rose-600 to-purple-800',
        'pertanian' => 'from-lime-700 via-emerald-700 to-teal-800',
        'lainnya' => 'from-slate-700 via-blue-800 to-slate-900',
    ];
    $gradient = $categoryGradients[$category] ?? $categoryGradients['lainnya'];
@endphp

<div class="{{ $aspect }} w-full relative overflow-hidden bg-slate-100 group-hover:brightness-[1.02] transition">
    @if($item->hasPhoto())
        <img 
            src="{{ $item->getPhotoUrl() }}" 
            alt="{{ $item->nama_usaha }}" 
            loading="lazy" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
        >
    @else
        {{-- Placeholder Ilustrasi Modern Berdasarkan Kategori Usaha --}}
        <div class="w-full h-full bg-gradient-to-br {{ $gradient }} relative flex flex-col items-center justify-center p-4 text-white overflow-hidden select-none">
            {{-- Background Pattern Subtle --}}
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" width="80" height="80">
                    <path d="M14 16H9v-2h5V9.87a4 4 0 1 1 2 0V14h5v2h-5v15.95A10 10 0 0 0 23.66 27l1.66.73a12 12 0 0 1-9.32 14.19v2.15a14 14 0 0 0 10.92-12.7l1.7.77a16 16 0 0 1-12.62 14.7v5.16h-2v-5.16A16 16 0 0 1 1.38 32.84l1.7-.77a14 14 0 0 0 10.92 12.7v-2.15A12 12 0 0 1 4.68 28.43l1.66-.73A10 10 0 0 0 14 31.95V16z" fill="currentColor"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center text-white mb-2 shadow-inner group-hover:scale-110 transition-transform duration-300">
                    @if($category === 'kuliner')
                        {{-- Icon Makanan / Sendok Garpu --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    @elseif($category === 'sembako')
                        {{-- Icon Toko / Keranjang --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    @elseif($category === 'jasa')
                        {{-- Icon Jasa / Perkakas --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    @elseif($category === 'kerajinan')
                        {{-- Icon Kerajinan / Kesenian --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21a4 4 0 01-4-4 5 5 0 0110 0 4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    @else
                        {{-- Icon Storefront Default --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    @endif
                </div>
                <span class="text-xs font-bold text-white/95 tracking-wide line-clamp-1 max-w-[200px]">
                    {{ $item->nama_usaha }}
                </span>
                <span class="text-[10px] font-semibold text-white/75 mt-0.5">
                    Katalog Usaha Warga RT 15
                </span>
            </div>
        </div>
    @endif
</div>
