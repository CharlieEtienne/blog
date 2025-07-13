<?php

namespace App\Filament\Resources\PanAnalytics\Pages;

use App\Enums\Analytics;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\PanAnalytics\PanAnalyticsResource;
use Filament\Resources\Pages\ListRecords;

class ListPanAnalytics extends ListRecords
{
    protected static string $resource = PanAnalyticsResource::class;

    public function getTabs(): array
    {
        $tabs = [];
        foreach (Analytics::cases() as $analytics) {
            $tabs[] = Tab::make($analytics->value)
                ->label($analytics->getTitle())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('name', 'LIKE', $analytics->value . '-%'));
        }
        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Flush')
                ->action(fn() => DB::table('pan_analytics')->truncate())
                ->label(__('Flush analytics'))
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }
}
