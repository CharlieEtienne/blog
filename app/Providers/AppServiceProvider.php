<?php

namespace App\Providers;

use Pan\PanConfiguration;
use App\Enums\SiteSettings;
use Illuminate\Support\Carbon;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\DB;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Mixins\RichContentRendererMixin;
use Illuminate\Validation\Rules\Password;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentAsset;
use CharlieEtienne\PaletteGenerator\PaletteGenerator;
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
     *
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
        URL::forceScheme('https');
        Vite::useAggressivePrefetching();
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();
        Carbon::setLocale(config('app.locale'));
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->uncompromised();
        });

        PanConfiguration::maxAnalytics(10000);

        FilamentColor::register([
            'primary' => $this->cachedGeneratedPalette(
                SiteSettings::PRIMARY_COLOR->get()
            ),
            'gray' => Color::{ucfirst(SiteSettings::NEUTRAL_COLOR->get())},
        ]);

        FilamentAsset::register([
            Js::make('rich-content-plugins/IdExtension', __DIR__ . '/../../resources/js/dist/filament/rich-content-plugins/IdExtension.js')->loadedOnRequest(),
        ]);

        RichContentRenderer::mixin(new RichContentRendererMixin());
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
