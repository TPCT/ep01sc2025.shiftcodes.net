<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Middlewares\AdminPanelVisitor;
use App\Filament\Middlewares\Banned;
use App\Settings\Site;
use Awcodes\Curator\CuratorPlugin;
use Awcodes\Curator\Models\Media;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\TranslationManager\Http\Middleware\SetLanguage;
use Kenepa\TranslationManager\TranslationManagerPlugin;
use Saade\FilamentLaravelLog\Facades\FilamentLaravelLog;
use Saade\FilamentLaravelLog\FilamentLaravelLogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(false)
            ->authGuard('admin')
            ->favicon(function (Site $Site) {
                return asset("/storage/" . Media::find($Site->fav_icon)?->path);
            })
            ->colors([
                'primary' => Color::Amber,
            ])
            ->collapsibleNavigationGroups()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->plugins([
                FilamentAstrotomicTranslatablePlugin::make(),
                TranslationManagerPlugin::make(),
                CuratorPlugin::make()
                    ->label('Media')
                    ->navigationGroup("CMS")
                    ->navigationCountBadge()
                    ->defaultListView('grid'),

                FilamentShieldPlugin::make()->gridColumns([
                    'default' => 1,
                    'sm' => 2,
                    'lg' => 3
                ])
                ->sectionColumnSpan(1)
                ->checkboxListColumns([
                    'default' => 1,
                    'sm' => 2,
                    'lg' => 1,
                ]),
                FilamentLaravelLogPlugin::make(),
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
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
                AdminPanelVisitor::class,
                SetLanguage::class
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->authMiddleware([
                Authenticate::class
            ]);
    }
}
