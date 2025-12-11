<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use App\Filament\Pages\RequestPasswordReset;
use App\Http\Middleware\CheckForDashboardMaintenanceMiddleware;
use App\Http\Middleware\UpdateUserLastSeenAtMiddleware;
use App\Models\Setting;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
//        if (app()->isLocal()) {
//            URL::forceScheme('https');
//        }

        return $panel
            ->default()
            ->id('dashboard')
            ->path('')
            ->login()
            ->passwordReset(RequestPasswordReset::class)
            ->emailChangeVerification()
            ->profile(EditProfile::class)

            ->colors([
                'primary' => Color::{Setting::get('primary_color')},
                'danger' => Color::{Setting::get('danger_color')},
                'gray' => Color::{Setting::get('gray_color')},
                'info' => Color::{Setting::get('info_color')},
                'success' => Color::{Setting::get('success_color')},
                'warning' => Color::{Setting::get('warning_color')},
            ])

            ->maxContentWidth(Width::Full)

//            ->brandLogo(asset('images/logo.svg'))
//            ->darkModeBrandLogo(asset('images/logo_dark.png'))
//            ->favicon('images/logo.svg')
            ->topNavigation(Setting::get('top_navigation'))
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => view('filament.hooks.top-bar'),
            )
            ->renderHook(PanelsRenderHook::BODY_START, function () {
                return Blade::render('filament.alert-notification', [
                    'text' => Setting::get('notification_text'),
                    'enabled' => Setting::get('notification_enabled'),
                    'type' => Setting::get('notification_type'),
                ]);
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                //
            ])->font('Poppins')

            ->assets([
                Css::make('custom-stylesheet', resource_path('css/custom.css')),
            ])

            //->strictAuthorization()
            ->defaultThemeMode(ThemeMode::Light)

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
                UpdateUserLastSeenAtMiddleware::class,
                CheckForDashboardMaintenanceMiddleware::class,
            ]);
    }
}
