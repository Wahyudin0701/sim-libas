@if(request()->routeIs('filament.admin.auth.login'))
<div class="sim-auth-after-wrapper">
    <div class="sim-auth-after-divider">
        <span>Akses Portal Lainnya</span>
    </div>

    <div class="sim-auth-links-grid">
        <a href="{{ route('citizen.login') }}" class="sim-auth-link-btn sim-auth-link-citizen">
            <svg style="width: 15px; height: 15px; min-width: 15px; max-width: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Portal Warga</span>
        </a>

        <a href="{{ route('public.home') }}" class="sim-auth-link-btn sim-auth-link-public">
            <svg style="width: 15px; height: 15px; min-width: 15px; max-width: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Ke Beranda</span>
        </a>
    </div>

    <div class="sim-auth-help">
        <p>
            Kendala akses akun pengurus? 
            <a href="https://wa.me/6281274001515?text={{ urlencode('Halo Ketua RT 15, saya pengurus mengalami kendala login ke Portal Admin SIM-LiBas.') }}" target="_blank" rel="noopener noreferrer">
                Hubungi Ketua RT
            </a>
        </p>
    </div>
</div>
@endif
