<?php

namespace App\Providers\Filament;

use App\Filament\Opd\Pages\Dashboard;
use App\Filament\Opd\Pages\IsiEvaluasi;
use App\Filament\Opd\Pages\RiwayatSubmission;
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

class OpdPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('opd')
            ->path('opd')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                
            ])
            ->font('DM Sans')
            ->brandName('Smart City — Portal OPD')
            ->brandLogo(null)
            ->favicon(null)
            // ->discoverResources(in: app_path('Filament/Opd/Pages'), for: 'App\\Filament\\Opd\\Pages')
            ->discoverPages(in: app_path('Filament/Opd/Pages'), for: 'App\\Filament\\Opd\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                
                \App\Filament\Opd\Pages\Dashboard::class,
                \App\Filament\Opd\Pages\IsiEvaluasi::class,
                \App\Filament\Opd\Pages\RiwayatSubmission::class,

                // \App\Filament\Opd\Pages\IsiEvaluasi::class,
                // \App\Filament\Opd\Pages\RiwayatSubmission::class,
                // IsiEvaluasi::class,
                // RiwayatSubmission::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Opd/Widgets'), for: 'App\\Filament\\Opd\\Widgets')
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
            ])
            ->login()
            ->homeUrl(function () {
                return route('filament.opd.pages.dashboard');
            })
            ;
            
    }
}
