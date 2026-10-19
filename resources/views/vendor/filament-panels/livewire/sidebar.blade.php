<div>
    @php
        use Filament\Enums\GlobalSearchPosition;
        use Illuminate\Support\Facades\Vite;

        $navigation = filament()->getNavigation();
        $isRtl = __('filament-panels::layout.direction') === 'rtl';
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
        $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
        $hasNavigation = filament()->hasNavigation();
        $hasTopbar = filament()->hasTopbar();
        $hasTenantMenu = filament()->hasTenancy() && filament()->hasTenantMenu();
        $hasGlobalSearchInSidebar = filament()->isGlobalSearchEnabled() && filament()->getGlobalSearchPosition() === GlobalSearchPosition::Sidebar;

        $cspNonce = Vite::cspNonce();
    @endphp

    {{-- format-ignore-start --}}
    <div
        x-data="{}"
        @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
            x-cloak
        @else
            x-cloak="-lg"
        @endif
        x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }"
        id="fi-main-sidebar"
        class="fi-sidebar fi-main-sidebar"
    >
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_START) }}

        <div class="fi-sidebar-header-ctn">
            <header
                class="fi-sidebar-header"
            >
                @if ((! $hasTopbar) && $isSidebarCollapsibleOnDesktop)
                    <x-filament::icon-button
                        color="gray"
                        :icon="$isRtl ? \Filament\Support\Icons\Heroicon::OutlinedChevronLeft : \Filament\Support\Icons\Heroicon::OutlinedChevronRight"
                        {{-- @deprecated Use `PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL` instead of `PanelsIconAlias::SIDEBAR_EXPAND_BUTTON` for RTL. --}}
                        :icon-alias="
                            $isRtl
                            ? [
                                \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL,
                                \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON,
                            ]
                            : \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON
                        "
                        icon-size="lg"
                        :label="__('filament-panels::layout.actions.sidebar.expand.label')"
                        x-cloak
                        x-data="{}"
                        aria-controls="fi-main-sidebar"
                        x-bind:aria-expanded="$store.sidebar.isOpen"
                        x-on:click="$store.sidebar.open()"
                        x-show="! $store.sidebar.isOpen"
                        class="fi-sidebar-open-collapse-sidebar-btn"
                    />
                @endif

                @if ((! $hasTopbar) && ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop))
                    <x-filament::icon-button
                        color="gray"
                        :icon="$isRtl ? \Filament\Support\Icons\Heroicon::OutlinedChevronRight : \Filament\Support\Icons\Heroicon::OutlinedChevronLeft"
                        {{-- @deprecated Use `PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL` instead of `PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON` for RTL. --}}
                        :icon-alias="
                            $isRtl
                            ? [
                                \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL,
                                \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON,
                            ]
                            : \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON
                        "
                        icon-size="lg"
                        :label="__('filament-panels::layout.actions.sidebar.collapse.label')"
                        x-cloak
                        x-data="{}"
                        aria-controls="fi-main-sidebar"
                        x-bind:aria-expanded="$store.sidebar.isOpen"
                        x-on:click="$store.sidebar.close()"
                        x-show="$store.sidebar.isOpen"
                        class="fi-sidebar-close-collapse-sidebar-btn"
                    />
                @endif

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_BEFORE) }}

                <div
                    @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
                        x-show="$store.sidebar.isOpen"
                    @endif
                    class="fi-sidebar-header-logo-ctn flex items-center justify-between w-full"
                >
                    @if ($homeUrl = filament()->getHomeUrl())
                        <a {{ \Filament\Support\generate_href_html($homeUrl) }} class="flex items-center">
                            <x-filament-panels::logo />
                        </a>
                    @else
                        <div class="flex items-center">
                            <x-filament-panels::logo />
                        </div>
                    @endif

                    {{-- Tombol Tutup Sidebar di Tampilan Mobile --}}
                    <button
                        type="button"
                        x-on:click="$store.sidebar.close()"
                        class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        title="Tutup Menu"
                        aria-label="Tutup Menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_AFTER) }}
            </header>
        </div>

        @if ($hasTenantMenu || $hasGlobalSearchInSidebar)
            <div
                @if ((! $hasTenantMenu) && ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop))
                    x-show="$store.sidebar.isOpen"
                @endif
                class="fi-sidebar-header-controls"
            >
                @if ($hasTenantMenu)
                    <x-filament-panels::tenant-menu />
                @endif

                @if ($hasGlobalSearchInSidebar)
                    <div
                        @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
                            x-show="$store.sidebar.isOpen"
                        @endif
                    >
                        @livewire(Filament\Livewire\GlobalSearch::class)
                    </div>
                @endif
            </div>
        @endif

        <nav
            aria-label="{{ __('filament-panels::layout.navigation.label') }}"
            class="fi-sidebar-nav"
        >
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_START) }}

            <ul class="fi-sidebar-nav-groups">
                @foreach ($navigation as $group)
                    @php
                        $isGroupActive = $group->isActive();
                        $isGroupCollapsible = $group->isCollapsible();
                        $groupIcon = $group->getIcon();
                        $groupItems = $group->getItems();
                        $groupLabel = $group->getLabel();
                        $groupExtraSidebarAttributeBag = $group->getExtraSidebarAttributeBag();
                    @endphp

                    <x-filament-panels::sidebar.group
                        :active="$isGroupActive"
                        :collapsible="$isGroupCollapsible"
                        :icon="$groupIcon"
                        :items="$groupItems"
                        :label="$groupLabel"
                        :attributes="\Filament\Support\prepare_inherited_attributes($groupExtraSidebarAttributeBag)"
                    />
                @endforeach
            </ul>

            <script @if (filled($cspNonce)) nonce="{{ $cspNonce }}" @endif>
                var collapsedGroups = JSON.parse(
                    localStorage.getItem('filament:sidebar:collapsed_groups'),
                )

                if (collapsedGroups === null || collapsedGroups === 'null') {
                    localStorage.setItem(
                        'filament:sidebar:collapsed_groups',
                        JSON.stringify(@js(
                        collect($navigation)
                            ->filter(fn (\Filament\Navigation\NavigationGroup $group): bool => $group->isCollapsed())
                            ->map(fn (\Filament\Navigation\NavigationGroup $group): ?string => ($label = $group->getLabel()) instanceof \Illuminate\Contracts\Support\Htmlable ? $label->toHtml() : $label)
                            ->values()
                            ->all()
                    )),
                    )
                }

                collapsedGroups = JSON.parse(
                    localStorage.getItem('filament:sidebar:collapsed_groups'),
                )

                document
                    .querySelectorAll('.fi-sidebar-group')
                    .forEach((group) => {
                        if (
                            !collapsedGroups.includes(group.dataset.groupLabel)
                        ) {
                            return
                        }

                        // Alpine.js loads too slow, so attempt to hide a
                        // collapsed sidebar group earlier.
                        group.querySelector(
                            '.fi-sidebar-group-items',
                        ).style.display = 'none'
                        group.classList.add('fi-collapsed')
                    })
            </script>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_END) }}
        </nav>

        @php
            $isAuthenticated = filament()->auth()->check();
            $hasDatabaseNotificationsInSidebar = filament()->hasDatabaseNotifications() && filament()->getDatabaseNotificationsPosition() === \Filament\Enums\DatabaseNotificationsPosition::Sidebar;
            $hasUserMenuInSidebar = filament()->hasUserMenu() && filament()->getUserMenuPosition() === \Filament\Enums\UserMenuPosition::Sidebar;
        @endphp

        @if ($isAuthenticated)
            @php
                $user = filament()->auth()->user();
                $userName = $user?->name ?? 'Pengurus RT 15';
                $userRoles = $user ? $user->getRoleNames() : collect();
                $userRoleLabel = match (true) {
                    $userRoles->contains('super_admin') => 'Super Admin',
                    $userRoles->contains('ketua_rt') => 'Ketua RT 15',
                    $userRoles->contains('sekretaris') => 'Sekretaris RT 15',
                    $userRoles->contains('bendahara') => 'Bendahara RT 15',
                    $userRoles->contains('koordinator_bank_sampah') => 'Koor. Bank Sampah',
                    $userRoles->contains('koordinator_keamanan') => 'Koor. Keamanan',
                    $userRoles->contains('rw_kelurahan') => 'RW / Kelurahan',
                    default => 'Pengurus RT 15',
                };
                $initials = collect(explode(' ', $userName))
                    ->filter()
                    ->map(fn ($segment) => strtoupper(substr($segment, 0, 1)))
                    ->take(2)
                    ->implode('');
                if (empty($initials)) {
                    $initials = 'RT';
                }
            @endphp

            <div class="fi-sidebar-footer">
                @if ($hasDatabaseNotificationsInSidebar)
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                    ])
                @endif

                @if ($hasUserMenuInSidebar)
                    <x-filament-panels::user-menu />
                @endif

                <div class="fi-sidebar-footer-content flex items-center justify-between w-full gap-2">
                    {{-- Avatar & Identitas Pengguna (Kiri) --}}
                    <div
                        @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
                            x-show="$store.sidebar.isOpen"
                        @endif
                        class="fi-sidebar-footer-user-info flex items-center gap-2.5 min-w-0"
                    >
                        <div class="w-8 h-8 rounded-full bg-[#1B7A3A] text-white font-extrabold flex items-center justify-center shrink-0 text-xs shadow-xs">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0 truncate text-left">
                            <div class="text-xs font-bold text-[#0F1F3D] truncate leading-tight">
                                {{ $userName }}
                            </div>
                            <div class="text-[10px] font-medium text-[#64748B] truncate mt-0.5">
                                {{ $userRoleLabel }}
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Logout (Kanan) --}}
                    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="fi-sidebar-footer-logout-form my-auto shrink-0">
                        @csrf
                        <button
                            type="submit"
                            title="Keluar dari Sistem ({{ $userName }})"
                            aria-label="Keluar dari Sistem"
                            class="fi-sidebar-footer-logout-btn flex items-center justify-center w-8 h-8 rounded-lg text-[#64748B] hover:text-[#DC2626] hover:bg-rose-50 transition duration-150 cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_FOOTER) }}
    </div>
    {{-- format-ignore-end --}}

    <x-filament-actions::modals />
</div>
