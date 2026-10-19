@props([
    'position' => null,
])

@php
    use Filament\Actions\Action;
    use Filament\Enums\UserMenuPosition;
    use Filament\Support\Facades\FilamentView;
    use Filament\Support\Icons\Heroicon;
    use Filament\Support\View\ComponentAttributeBag;
    use Filament\View\PanelsIconAlias;
    use Filament\View\PanelsRenderHook;
    use Illuminate\Support\Arr;

    $user = filament()->auth()->user();

    $userName = filament()->getUserName($user);

    $items = $this->getUserMenuItems();

    $itemsBeforeAndAfterThemeSwitcher = collect($items)
        ->groupBy(fn (Action $item): bool => $item->getSort() < 0, preserveKeys: true)
        ->all();
    $itemsBeforeThemeSwitcher = $itemsBeforeAndAfterThemeSwitcher[true] ?? collect();
    $itemsAfterThemeSwitcher = $itemsBeforeAndAfterThemeSwitcher[false] ?? collect();

    $hasProfileHeader = $itemsBeforeThemeSwitcher->has('profile') &&
        blank(($item = Arr::first($itemsBeforeThemeSwitcher))->getUrl()) &&
        (! $item->hasAction());

    if ($itemsBeforeThemeSwitcher->has('profile')) {
        $itemsBeforeThemeSwitcher = $itemsBeforeThemeSwitcher->prepend($itemsBeforeThemeSwitcher->pull('profile'), 'profile');
    }

    $multiGroupAfterTheme = $this->hasMultipleUserMenuItemGroups();
    $afterThemeItemGroups = $multiGroupAfterTheme ? $this->getUserMenuItemGroupsAfterTheme() : [];

    $position ??= filament()->getUserMenuPosition();

    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();

    // Hitung inisial nama pengguna (2 huruf)
    $initials = str($userName)
        ->trim()
        ->explode(' ')
        ->map(fn (string $segment) => mb_substr(preg_replace('/^[^\p{L}\p{N}]+/u', '', $segment) ?? '', 0, 1))
        ->filter()
        ->take(2)
        ->join('');
    if (blank($initials)) {
        $initials = 'U';
    }
@endphp

{{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_BEFORE) }}

<x-filament::dropdown
    :placement="($position === UserMenuPosition::Topbar) ? 'bottom-end' : 'top-end'"
    :teleport="$position === UserMenuPosition::Topbar"
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-user-menu'])
    "
>
    <x-slot name="trigger">
        @if ($position === UserMenuPosition::Topbar)
            <button
                aria-label="{{ filled($userName) ? $userName : __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger flex items-center gap-2.5 text-start hover:opacity-95 transition rounded-full sm:rounded-xl sm:px-2 sm:py-1 hover:bg-slate-50 focus:outline-none"
            >
                {{-- Avatar Pengguna: Hijau Segar (#166534), Tidak Hitam --}}
                @if ($user?->avatar_url)
                    <img src="{{ asset($user->avatar_url) }}" alt="{{ $userName }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-200/80 shrink-0" />
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#166534] to-[#1B7A3A] text-white font-extrabold flex items-center justify-center text-xs tracking-wider shrink-0 ring-2 ring-emerald-200/80 shadow-xs">
                        {{ $initials }}
                    </div>
                @endif

                @php
                    $userRoles = $user ? $user->getRoleNames() : collect();
                    $userRoleLabel = match (true) {
                        $userRoles->contains('super_admin') => 'Super Admin',
                        $userRoles->contains('ketua_rt') => 'Ketua RT 15',
                        $userRoles->contains('sekretaris') => 'Sekretaris RT 15',
                        $userRoles->contains('bendahara') => 'Bendahara RT 15',
                        $userRoles->contains('koordinator_bank_sampah') => 'Koord. Bank Sampah',
                        $userRoles->contains('koordinator_keamanan') => 'Koord. Keamanan',
                        $userRoles->contains('rw_kelurahan') => 'RW / Kelurahan',
                        default => 'Pengurus RT 15',
                    };
                @endphp

                <div class="hidden sm:flex flex-col text-start">
                    <span class="text-xs font-bold text-[#0F1F3D] leading-tight">
                        {{ $userName }}
                    </span>
                    <span class="text-[10px] font-semibold text-[#5B6B82] tracking-wider uppercase leading-none mt-0.5">
                        {{ $userRoleLabel }}
                    </span>
                </div>

                {{
                    \Filament\Support\generate_icon_html(Heroicon::ChevronDown, alias: PanelsIconAlias::USER_MENU_TOGGLE_BUTTON, attributes: new ComponentAttributeBag([
                        'class' => 'w-3.5 h-3.5 text-[#5B6B82] hidden sm:block ms-0.5',
                    ]))
                }}
            </button>
        @else
            <button
                aria-label="{{ filled($userName) ? $userName : __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger"
            >
                @if ($user?->avatar_url)
                    <img src="{{ asset($user->avatar_url) }}" alt="{{ $userName }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-200/80 shrink-0" />
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#166534] to-[#1B7A3A] text-white font-extrabold flex items-center justify-center text-xs tracking-wider shrink-0 ring-2 ring-emerald-200/80 shadow-xs">
                        {{ $initials }}
                    </div>
                @endif

                <span
                    @if ($isSidebarCollapsibleOnDesktop)
                        x-show="$store.sidebar.isOpen"
                    @endif
                    class="fi-user-menu-trigger-text"
                >
                    {{ $userName }}
                </span>

                {{
                    \Filament\Support\generate_icon_html(Heroicon::ChevronUp, alias: PanelsIconAlias::USER_MENU_TOGGLE_BUTTON, attributes: new ComponentAttributeBag([
                        'x-show' => $isSidebarCollapsibleOnDesktop ? '$store.sidebar.isOpen' : null,
                    ]))
                }}
            </button>
        @endif
    </x-slot>

    @if ($hasProfileHeader)
        @php
            $item = $itemsBeforeThemeSwitcher['profile'];
            $itemColor = $item->getColor();
            $itemIcon = $item->getIcon();

            unset($itemsBeforeThemeSwitcher['profile']);
        @endphp

        {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

        <x-filament::dropdown.header :color="$itemColor" :icon="$itemIcon">
            {{ $item->getLabel() }}
        </x-filament::dropdown.header>

        {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
    @endif

    @if ($itemsBeforeThemeSwitcher->isNotEmpty())
        <x-filament::dropdown.list>
            @foreach ($itemsBeforeThemeSwitcher as $key => $item)
                @if ($key === 'profile')
                    {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

                    {{ $item }}

                    {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
                @elseif ($key === 'logout')
                    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="w-full m-0">
                        @csrf
                        <button
                            type="submit"
                            class="fi-dropdown-list-item flex items-center gap-x-2.5 w-full px-3 py-2 text-xs sm:text-sm font-semibold rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50 active:bg-red-100 transition duration-150 text-start group cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-red-500 group-hover:text-red-600 shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-red-600 group-hover:text-red-700 font-bold">{{ __('filament-panels::layout.actions.logout.label') }}</span>
                        </button>
                    </form>
                @else
                    {{ $item }}
                @endif
            @endforeach
        </x-filament::dropdown.list>
    @endif

    @if ($multiGroupAfterTheme && $afterThemeItemGroups !== [])
        @foreach ($afterThemeItemGroups as $afterThemeGroup)
            <x-filament::dropdown.list>
                @foreach ($afterThemeGroup as $key => $item)
                    @if ($key === 'profile')
                        {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

                        {{ $item }}

                        {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
                    @elseif ($key === 'logout')
                        <form action="{{ filament()->getLogoutUrl() }}" method="post" class="w-full m-0">
                            @csrf
                            <button
                                type="submit"
                                class="fi-dropdown-list-item flex items-center gap-x-2.5 w-full px-3 py-2 text-xs sm:text-sm font-semibold rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50 active:bg-red-100 transition duration-150 text-start group cursor-pointer"
                            >
                                <svg class="w-4 h-4 text-red-500 group-hover:text-red-600 shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-red-600 group-hover:text-red-700 font-bold">{{ __('filament-panels::layout.actions.logout.label') }}</span>
                            </button>
                        </form>
                    @else
                        {{ $item }}
                    @endif
                @endforeach
            </x-filament::dropdown.list>
        @endforeach
    @elseif ($itemsAfterThemeSwitcher->isNotEmpty())
        <x-filament::dropdown.list>
            @foreach ($itemsAfterThemeSwitcher as $key => $item)
                @if ($key === 'profile')
                    {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

                    {{ $item }}

                    {{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
                @elseif ($key === 'logout')
                    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="w-full m-0">
                        @csrf
                        <button
                            type="submit"
                            class="fi-dropdown-list-item flex items-center gap-x-2.5 w-full px-3 py-2 text-xs sm:text-sm font-semibold rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50 active:bg-red-100 transition duration-150 text-start group cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-red-500 group-hover:text-red-600 shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-red-600 group-hover:text-red-700 font-bold">{{ __('filament-panels::layout.actions.logout.label') }}</span>
                        </button>
                    </form>
                @else
                    {{ $item }}
                @endif
            @endforeach
        </x-filament::dropdown.list>
    @endif
</x-filament::dropdown>

{{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_AFTER) }}
