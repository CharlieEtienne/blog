<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Models\Tag;
use App\Support\SlugGenerator;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;

class TagForm
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
                    if( in_array($operation, ['create', 'createOption']) ){
                        $slug = SlugGenerator::unique(modelClass: Tag::class, title: $state, ignoreRecord: $record);
                        $set('slug', $slug);
                    }
                }),

            TextInput::make('slug'),
        ];
    }

}
