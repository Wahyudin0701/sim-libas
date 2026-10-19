<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Pages\Auth\Login;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->darkMode(false)
            ->brandName('SIM-LiBas RT 15')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->profile(\App\Filament\Pages\Auth\EditProfile::class, isSimple: false)
            ->defaultAvatarProvider(\App\Filament\AvatarProviders\SimLibasAvatarProvider::class)
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Profil Saya')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn (): string => route('filament.admin.auth.profile')),
                'setting' => \Filament\Navigation\MenuItem::make()
                    ->label('Pengaturan Sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (): string => route('filament.admin.resources.rt-settings.index'))
                    ->visible(fn (): bool => (bool) auth()->user()?->hasRole('super_admin')),
                'logout' => \Filament\Navigation\MenuItem::make()
                    ->label('Keluar')
                    ->color('danger'),
            ])
            ->icons([
                PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => view('filament.icons.sidebar-toggle'),
                PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL => view('filament.icons.sidebar-toggle'),
                PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => view('filament.icons.sidebar-toggle'),
                PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL => view('filament.icons.sidebar-toggle'),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): View => view('filament.hooks.admin-head'),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.hooks.login-head'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): View => view('filament.hooks.login-before'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): View => view('filament.hooks.login-after'),
            )
            ->renderHook(
                PanelsRenderHook::SIMPLE_LAYOUT_END,
                fn (): View => view('filament.hooks.login-footer'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): View => view('filament.components.notification-bell'),
            )
            ->colors([
                'primary' => Color::hex('#0B3A85'),
                'info' => Color::hex('#19A7E8'),
                'success' => Color::hex('#1B7A3A'),
                'warning' => Color::hex('#F2A900'),
                'danger' => Color::hex('#D64545'),
                'gray' => Color::Slate,
            ])
            ->font('Plus Jakarta Sans')
            ->maxContentWidth('full')
            ->sidebarWidth('15rem')
            ->collapsedSidebarWidth('4rem')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Kependudukan')
                    ->icon('heroicon-o-user-group')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Layanan Administrasi')
                    ->icon('heroicon-o-document-text')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Komunikasi Warga')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Keamanan Lingkungan')
                    ->icon('heroicon-o-shield-check')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Keuangan RT')
                    ->icon('heroicon-o-banknotes')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Kampung Digital')
                    ->icon('heroicon-o-building-storefront')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Pengaturan Sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make('Akun Pengurus')
                    ->icon('heroicon-o-user-circle')
                    ->collapsible(),
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Profil Saya')
                    ->url(fn (): string => route('filament.admin.auth.profile'))
                    ->icon('heroicon-o-user-circle')
                    ->group('Akun Pengurus')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.auth.profile'))
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
