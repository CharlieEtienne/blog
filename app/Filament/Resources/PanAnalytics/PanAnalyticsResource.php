<?php

namespace App\Filament\Resources\PanAnalytics;

use App\Filament\Resources\PanAnalytics\Pages\CreatePanAnalytics;
use App\Filament\Resources\PanAnalytics\Pages\EditPanAnalytics;
use App\Filament\Resources\PanAnalytics\Pages\ListPanAnalytics;
use App\Filament\Resources\PanAnalytics\Pages\ViewPanAnalytics;
use App\Filament\Resources\PanAnalytics\Schemas\PanAnalyticsForm;
use App\Filament\Resources\PanAnalytics\Schemas\PanAnalyticsInfolist;
use App\Filament\Resources\PanAnalytics\Tables\PanAnalyticsTable;
use App\Models\PanAnalytics;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PanAnalyticsResource extends Resource
{
    protected static ?string $model = PanAnalytics::class;

    protected static string|null|\BackedEnum $navigationIcon  = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Stats';
    protected static ?string $slug = 'stats';
    protected static ?string $modelLabel = 'Stats';
    protected static ?int $navigationSort  = 100;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return PanAnalyticsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPanAnalytics::route('/'),
        ];
    }
}
