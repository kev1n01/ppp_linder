<?php

namespace App\Providers\Filament;

use App\Filament\Pages\DashboardAdmin;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
            $settings = Setting::first();
            $logo = $settings?->set_logo
                ? asset('storage/' . $settings->set_logo)
                : asset('images/tiburon.jpg'); 

            return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#2f6ed4',
            ])
            ->brandName('GRIFO TIBURON 555')
            ->brandLogoHeight('4rem')
            ->brandLogo($logo)
            ->darkModeBrandLogo($logo)
            ->favicon($logo)
            ->sidebarCollapsibleOnDesktop()
            ->profile() 
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                DashboardAdmin::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
              \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                ->gridColumns([
                    'default' => 1,
                    'sm' => 2,
                    'lg' => 3
                ])
                ->sectionColumnSpan(1)
                ->checkboxListColumns([
                    'default' => 1,
                    'sm' => 2,
                    'lg' => 4,
                ])
                ->resourceCheckboxListColumns([
                    'default' => 1,
                    'sm' => 2,
                ]),
              \Hasnayeen\Themes\ThemesPlugin::make()
                ->canViewThemesPage(fn () => auth()->user()?->email === 'admin@tiburon.com')
            ]);
    }
}
