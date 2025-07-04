<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;

class CategoriesTable
{
    /**
     * @throws \Exception
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon')
                    ->label('')
                    ->icon(fn($state) => Heroicon::tryFrom($state))
                    ->color(fn($record) => Color::hex($record->color)),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn($record) => str($record->content)->limit(40))
                    ->grow(),
                TextColumn::make('slug')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->fontFamily(FontFamily::Mono)
                    ->grow(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->toggleable()
                    ->grow(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->toggleable()
                    ->grow(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
