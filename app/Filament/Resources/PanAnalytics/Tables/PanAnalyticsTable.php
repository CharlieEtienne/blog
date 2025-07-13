<?php

namespace App\Filament\Resources\PanAnalytics\Tables;

use App\Enums\Analytics;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PanAnalyticsTable
{
    /**
     * @throws \Exception
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(function ($state) {
                        $analyticsFound = false;
                        foreach (Analytics::cases() as $analytics) {
                            if( str($state)->startsWith($analytics->value . '-') && !$analyticsFound ){
                                $state = str($state)->ltrim($analytics->value . '-');
                                $analyticsFound = true;
                            }
                        }
                        return str($state)->replace('-', ' ')->title();
                    })
                    ->limit(40)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')->label('Type')->badge()->sortable(),
                TextColumn::make('impressions')->sortable(),
                TextColumn::make('hovers')->sortable(),
                TextColumn::make('hover_percentage')->label(__('Hover %'))->suffix('%')->sortable(),
                TextColumn::make('clicks')->sortable(),
                TextColumn::make('click_percentage')->label(__('Click %'))->suffix('%')->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
