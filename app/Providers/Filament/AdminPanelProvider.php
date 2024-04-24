<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantGradeChart;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantRegistrationChart;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantStatsOverview;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantTypeChart;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BladeUI\Icons\Components\Icon;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
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
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('BSC Admin')
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                ParticipantStatsOverview::make(),
                ParticipantRegistrationChart::make(),
                ParticipantTypeChart::make(),
                ParticipantGradeChart::make(),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Data Pokok')
                    ->icon('heroicon-o-circle-stack'),
                NavigationGroup::make()
                    ->label('Data Peserta')
                    ->icon('heroicon-o-user-group')
            ])
            ->userMenuItems([
                // 'logout' => MenuItem::make()->label('Log out'),
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
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentUsersPlugin::make(),
            ]);
    }
}
