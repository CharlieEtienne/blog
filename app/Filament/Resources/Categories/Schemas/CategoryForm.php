<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use App\Support\SlugGenerator;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Utilities\Set;

class CategoryForm
{
    /**
     * @throws \Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm())->columns(1);
    }

    /**
     * @throws \Exception
     */
    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->live(onBlur: true)
                ->required()
                ->afterStateUpdated(function (?string $state, Set $set, ?Model $record, string $operation) {
                    if (in_array($operation, ['create', 'createOption'])) {
                        $slug = SlugGenerator::unique(modelClass: Category::class, title: $state,
                            ignoreRecord: $record);
                        $set('slug', $slug);
                    }
                }),

            TextInput::make('slug'),

            ColorPicker::make('color'),

            Select::make('icon')
                ->options(
                    collect(Heroicon::cases())->mapWithKeys(function (Heroicon $heroicon) {
                        $iconName = $heroicon->value;
                        $iconHtml = \Filament\Support\generate_icon_html($heroicon)->toHtml();
                        $label = "<div class='flex gap-2'>$iconHtml<span>$iconName</span></div>";
                        return [$iconName => $label];
                    })->toArray()
                )
                ->searchable()
                ->preload()
                ->allowHtml(),

            RichEditor::make('content')
                ->columnSpanFull(),
        ];
    }
}
