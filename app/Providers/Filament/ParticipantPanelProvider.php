<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\BoringAvatarsProvider;
use App\Filament\Participant\Pages\Auth\Register;
use App\Filament\Participant\Widgets\CountdownWidget;
use App\Filament\Participant\Widgets\DownloadAnswerExplanationWidget;
use App\Filament\Participant\Widgets\DownloadCertificateWidget;
use App\Filament\Participant\Widgets\ParticipantWidget;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ParticipantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('participant')
            ->path('')
            ->login()
            // ->registration(Register::class)
            ->passwordReset()
            ->topNavigation()
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(asset('assets/images/logo.png'))
            ->brandLogoHeight('100px')
            ->defaultAvatarProvider(BoringAvatarsProvider::class)
            ->font('Raleway')
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->viteTheme('resources/css/filament/participant/theme.css')
            ->discoverResources(in: app_path('Filament/Participant/Resources'), for: 'App\\Filament\\Participant\\Resources')
            ->discoverPages(in: app_path('Filament/Participant/Pages'), for: 'App\\Filament\\Participant\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Participant/Widgets'), for: 'App\\Filament\\Participant\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                ParticipantWidget::class,
                DownloadCertificateWidget::class,
                DownloadAnswerExplanationWidget::class,
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
