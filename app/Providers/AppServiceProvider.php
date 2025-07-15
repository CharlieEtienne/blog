<?php

/** @noinspection NonAsciiCharacters */

namespace App\Providers;

use Pan\PanConfiguration;
use App\Enums\SiteSettings;
use Illuminate\Support\Carbon;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\DB;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\URL;
use App\Support\AvailableLanguages;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Mixins\RichContentRendererMixin;
use Illuminate\Validation\Rules\Password;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentAsset;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use CharlieEtienne\PaletteGenerator\PaletteGenerator;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\RichEditor\RichContentRenderer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->laravelGoodies🤘();
        $this->setAppLanguageDefaults();
        $this->setAppPasswordsDefaults();
        $this->setAppAnalyticsDefaults();
        $this->setFilamentDefaults();
        $this->registerFilamentAssets();
    }

    public function laravelGoodies🤘(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
        URL::forceScheme('https');
        Vite::useAggressivePrefetching();
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();
    }

    public function setAppLanguageDefaults(): void
    {
        App::setLocale(SiteSettings::DEFAULT_LANGUAGE->get() ?? 'en');
        App::setFallbackLocale(SiteSettings::FALLBACK_LANGUAGE->get() ?? 'en');
        Carbon::setLocale(SiteSettings::DEFAULT_LANGUAGE->get() ?? 'en');
        LaravelLocalization::setSupportedLocales(AvailableLanguages::get());
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(SiteSettings::LANGUAGES->get());
        });
    }

    public function setAppPasswordsDefaults(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->uncompromised();
        });
    }

    public function setFilamentDefaults(): void
    {
        FilamentColor::register([
            'primary' => $this->cachedGeneratedPalette(SiteSettings::PRIMARY_COLOR->get()),
            'gray' => Color::{ucfirst(SiteSettings::NEUTRAL_COLOR->get())},
        ]);

        RichContentRenderer::mixin(new RichContentRendererMixin());
    }

    public function registerFilamentAssets(): void
    {
        FilamentAsset::register([
            Js::make('rich-content-plugins/IdExtension', __DIR__ . '/../../resources/js/dist/filament/rich-content-plugins/IdExtension.js')->loadedOnRequest(),
        ]);
    }

    public function setAppAnalyticsDefaults(): void
    {
        PanConfiguration::maxAnalytics(10000);
    }

    public function cachedGeneratedPalette(string $color): array
    {
        try {
            return cache()->rememberForever("primary_palette_generated", fn () => PaletteGenerator::generatePalette($color));
        } catch (\Exception $e) {
            // Bypass cache if the database is not available (e.g., during CI or tests)
            return PaletteGenerator::generatePalette($color);
        }
    }
}
