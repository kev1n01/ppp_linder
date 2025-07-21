<?php

namespace App\Providers\Filament;

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

class MarketPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $settings = Setting::first();
        $logo = $settings?->set_logo
            ? asset('storage/' . $settings->set_logo)
            : asset('images/tiburon.jpg'); 

        return $panel
            ->id('market')
            ->path('market')
            ->login()
            ->topNavigation()
            ->colors([
              'primary' => '#ff0000',
            ])
            ->brandName('GRIFO TIBURON 555')
            ->brandLogo($logo)
            ->darkModeBrandLogo($logo)
            ->favicon($logo)
            ->brandLogoHeight('4rem')
            ->discoverResources(in: app_path('Filament/Market/Resources'), for: 'App\\Filament\\Market\\Resources')
            ->discoverPages(in: app_path('Filament/Market/Pages'), for: 'App\\Filament\\Market\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Market/Widgets'), for: 'App\\Filament\\Market\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
