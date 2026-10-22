@props(['item'])

@php
    $platforms = $item->getAvailableSocialPlatforms();
    $count = count($platforms);
@endphp

@if($count === 0)
    {{-- Kondisi tanpa kontak: tampilan ringkas tanpa menyisakan ruang kosong mengganggu --}}
    <div class="px-4 py-2.5 bg-slate-50 border-t border-border-soft text-center text-[11px] text-slate-400 font-medium">
        Kontak belum ditambahkan
    </div>
@elseif($count === 1)
    {{-- Kondisi 1 Platform: Tombol utama penuh (Full Width) dengan branding platform --}}
    @php
        $p = $platforms[0];
        $url = $item->getSocialUrl($p);
        $label = match($p) {
            'whatsapp' => 'Hubungi via WhatsApp',
            'instagram' => 'Kunjungi Instagram',
            'tiktok' => 'Buka TikTok',
            'shopee' => 'Belanja di Shopee',
            default => 'Hubungi Usaha',
        };
        $btnStyle = match($p) {
            'whatsapp' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-600/20',
            'instagram' => 'bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 hover:opacity-95 text-white shadow-rose-500/20',
            'tiktok' => 'bg-slate-900 hover:bg-black text-white shadow-slate-900/20',
            'shopee' => 'bg-[#EE4D2D] hover:bg-[#d83f20] text-white shadow-[#EE4D2D]/20',
            default => 'bg-primary-700 hover:bg-primary-800 text-white',
        };
    @endphp

    <div class="p-4 pt-3 bg-slate-50/70 border-t border-border-soft">
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-full font-bold text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 {{ $btnStyle }}">
            @include('components.umkm-social-icon', ['platform' => $p, 'class' => 'w-4 h-4 fill-current shrink-0'])
            <span>{{ $label }}</span>
        </a>
    </div>
@else
    {{-- Kondisi Multi-Kontak (2, 3, atau 4 platform) --}}
    <div class="p-3.5 bg-slate-50/70 border-t border-border-soft">
        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 flex items-center justify-between">
            <span>Hubungi / Kunjungi:</span>
            <span class="text-slate-400 font-normal">({{ $count }} Saluran)</span>
        </div>
        <div class="{{ $count === 2 ? 'grid grid-cols-2 gap-2' : 'grid grid-cols-2 sm:grid-cols-2 gap-2' }}">
            @foreach($platforms as $p)
                @php
                    $url = $item->getSocialUrl($p);
                    $btnStyle = match($p) {
                        'whatsapp' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-600 hover:text-white border-emerald-200',
                        'instagram' => 'bg-rose-50 text-rose-800 hover:bg-rose-600 hover:text-white border-rose-200',
                        'tiktok' => 'bg-slate-100 text-slate-800 hover:bg-slate-900 hover:text-white border-slate-200',
                        'shopee' => 'bg-orange-50 text-orange-800 hover:bg-[#EE4D2D] hover:text-white border-orange-200',
                        default => 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200',
                    };
                    $shortLabel = match($p) {
                        'whatsapp' => 'WhatsApp',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'shopee' => 'Shopee',
                        default => ucfirst($p),
                    };
                @endphp
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" title="{{ $p === 'whatsapp' ? 'Hubungi via WhatsApp' : $shortLabel . ': ' . $item->getSocialLabel($p) }}" class="py-2 px-2.5 rounded-xl border text-xs font-semibold flex items-center justify-center gap-1.5 transition-all duration-200 shadow-2xs hover:shadow-sm cursor-pointer {{ $btnStyle }}">
                    @include('components.umkm-social-icon', ['platform' => $p, 'class' => 'w-3.5 h-3.5 fill-current shrink-0'])
                    <span class="truncate">{{ $shortLabel }}</span>
                    @if($p === 'whatsapp')
                        <span class="sr-only">Hubungi via WhatsApp</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif
