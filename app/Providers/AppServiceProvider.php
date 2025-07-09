<?php

namespace App\Providers;

use App\Enums\SiteSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Filament\Support\Facades\FilamentColor;
use CharlieEtienne\PaletteGenerator\PaletteGenerator;

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

        FilamentColor::register([
            'primary' => self::cachedGeneratedPalette(
                SiteSettings::PRIMARY_COLOR->get()
            ),
        ]);
    }

    public function cachedGeneratedPalette(string $color): array
    {
        return cache()->rememberForever("primary_palette_generated", fn () => PaletteGenerator::generatePalette($color));
    }
}
