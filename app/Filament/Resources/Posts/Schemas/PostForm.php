<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Actions\Action;
use App\Support\SlugGenerator;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Group;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components\Section;
use App\Filament\CustomBlocks\CodeBlock;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\Tags\Schemas\TagForm;
use App\Filament\Resources\Categories\Schemas\CategoryForm;

class PostForm
{
    /**
     * @throws \Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([

                    // Main content
                    Group::make([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function (?string $state, Set $set, ?Model $record, $operation) {
                                if (in_array($operation, ['create', 'createOption'])) {
                                    $slug = SlugGenerator::unique(modelClass: Post::class, title: $state,
                                        ignoreRecord: $record);
                                    $set('slug', $slug);
                                }
                            }),

                        TextInput::make('slug')
                            ->partiallyRenderAfterStateUpdated(),

                        RichEditor::make('body')
                            ->json()
                            ->customBlocks([
                                CodeBlock::class,
                            ])
                            ->hiddenLabel(),
                    ]),

                    // Sidebar
                    Group::make([

                        Section::make('Publish')->schema([
                            DateTimePicker::make('published_at')->label('Publish on'),
                            Action::make('unpublish')
                        ])->compact(),

                        Section::make('Post Settings')->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor(),

                            Select::make('author_id')
                                ->relationship('author', 'name'),

                            Select::make('categories')
                                ->multiple()
                                ->relationship('categories', 'name')
                                ->createOptionForm(fn() => CategoryForm::getForm())
                                ->createOptionModalHeading('Add a new category')
                                ->preload()
                                ->allowHtml(),

                            Select::make('tags')
                                ->multiple()
                                ->relationship('tags', 'name')
                                ->createOptionForm(fn() => TagForm::getForm())
                                ->createOptionModalHeading('Add a new tag')
                                ->preload(),

                            Textarea::make('excerpt')->rows(4),

                        ])->compact(),

                    ])->grow(false)->extraAttributes(['style' => 'max-width:300px;']),

                ])->from('md')

            ])->columns(1);
    }
}
