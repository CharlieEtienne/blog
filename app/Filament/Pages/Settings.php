<?php

namespace App\Filament\Pages;

use App\Enums\Font;
use Phiki\Theme\Theme;
use App\Enums\Iconoir;
use App\Enums\MainPages;
use App\Enums\SiteSettings;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Flex;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Awcodes\Palette\Forms\Components\ColorPicker as PaletteColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Repeater\TableColumn;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static string|null|\BackedEnum $navigationIcon  = 'heroicon-o-adjustments-vertical';
    protected static string|null|\UnitEnum $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'General Settings';
    protected static ?string $slug = 'settings';
    protected static ?string $title = 'General Settings';
    protected static ?int $navigationSort  = 0;
    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $formData = [];

        foreach (SiteSettings::cases() as $setting) {
            $formData[ $setting->value ] = $setting->get();
        }

        $this->form->fill($formData);
    }

    /**
     * @throws \Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make()
                    ->heading(__('Identity'))
                    ->description(__("Who's this blog for?"))
                    ->icon(Heroicon::OutlinedIdentification)
                    ->aside()
                    ->schema([
                        FileUpload::make(SiteSettings::SITE_LOGO->value)
                            ->label(__('Site logo'))
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->imageEditor(),

                        FileUpload::make(SiteSettings::FAVICON->value)
                            ->label(__('Site favicon'))
                            ->avatar()
                            ->panelLayout('compact square')
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->imageEditor(),

                        TextInput::make(SiteSettings::SITE_NAME->value)
                            ->label(__('Site name')),

                        Toggle::make(SiteSettings::DISPLAY_SITE_NAME->value)
                            ->label(__('Display site name?')),

                    ])->columns(1),

                Section::make()
                    ->heading(__('Contact'))
                    ->description(__("A bit more about you."))
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->aside()
                    ->schema([

                        TextInput::make(SiteSettings::CONTACT_EMAIL->value)
                            ->label(__('Contact email'))
                            ->email()
                            ->prefixIcon(Heroicon::OutlinedEnvelope),

                        // TODO: Add github, x, bluesky, linkedin

                    ])->columns(1),

                Section::make()
                    ->heading(__('Design'))
                    ->description(__("Give it a fresh coat of paint."))
                    ->icon(Heroicon::OutlinedPaintBrush)
                    ->aside()
                    ->schema([

                        ColorPicker::make(SiteSettings::PRIMARY_COLOR->value)
                            ->helperText(__('Primary accent color used for links, buttons, etc.')),

                        PaletteColorPicker::make(SiteSettings::NEUTRAL_COLOR->value)
                            ->label(__('Neutral color'))
                            ->colors([
                                'stone' => Color::Stone,
                                'neutral' => Color::Neutral,
                                'zinc' => Color::Zinc,
                                'gray' => Color::Gray,
                                'slate' => Color::Slate,
                            ])->storeAsKey()
                            ->helperText(__('What shade of gray would you use? This color is used for backgrounds, borders.')),

                        Select::make(SiteSettings::HEADING_FONT->value)
                            ->options(Font::class)
                            ->searchable(),

                        Select::make(SiteSettings::BODY_FONT->value)
                            ->options(Font::class)
                            ->searchable(),

                        Select::make(SiteSettings::CODE_FONT->value)
                            ->options(Font::class)
                            ->searchable(),

                        Select::make(SiteSettings::CODE_THEME->value)
                            ->options(collect(Theme::cases())->mapWithKeys(fn(Theme $theme) => [
                                $theme->value => $theme->name,
                            ]))
                            ->searchable(),

                    ])->columns(1),

                Section::make()
                    ->heading(__('Hero'))
                    ->description(__('Details for the hero section on home page.'))
                    ->icon(Heroicon::OutlinedStar)
                    ->aside()
                    ->schema([
                        TextInput::make(SiteSettings::HERO_TITLE->value)
                            ->label(__('Hero title'))
                            ->helperText(__('Wrap a word between two pipes to make it colored. E.g. "Welcome to my |personal| blog"')),

                        TextInput::make(SiteSettings::HERO_SUBTITLE->value)
                            ->label(__('Hero subtitle')),

                        FileUpload::make(SiteSettings::HERO_IMAGE->value)
                            ->label(__('Hero image'))
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->imageEditor(),

                        TextInput::make(SiteSettings::HERO_IMAGE_HEIGHT->value)
                            ->label(__('Image height'))
                            ->suffix('px')
                            ->numeric()
                            ->helperText(__('Default: ') . SiteSettings::HERO_IMAGE_HEIGHT->getDefaultValue() . 'px'),

                        Toggle::make(SiteSettings::HERO_IMAGE_FULL_WIDTH->value)
                            ->label(__('Full width?')),
                    ])->columns(1),

                Section::make()
                    ->heading(__('Post default image'))
                    ->description(__('The default image to use for posts that do not have an image set.'))
                    ->icon(Heroicon::OutlinedPhoto)
                    ->aside()
                    ->schema([
                        FileUpload::make(SiteSettings::POST_DEFAULT_IMAGE->value)
                            ->hiddenLabel()
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                    ])->columns(1),

                Section::make()
                    ->heading(__('About'))
                    ->description(__('Details for the about section.'))
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->aside()
                    ->schema([
                        FileUpload::make(SiteSettings::ABOUT_IMAGE->value)
                            ->label(__('About image'))
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->imageEditor(),

                        TextInput::make(SiteSettings::ABOUT_IMAGE_WIDTH->value)
                            ->label(__('Image width'))
                            ->suffix('px')
                            ->numeric()
                            ->helperText(__('Default: ') . SiteSettings::ABOUT_IMAGE_WIDTH->getDefaultValue() . 'px'),

                        TextInput::make(SiteSettings::ABOUT_IMAGE_HEIGHT->value)
                            ->label(__('Image height'))
                            ->suffix('px')
                            ->numeric()
                            ->helperText(__('Default: ') . SiteSettings::ABOUT_IMAGE_HEIGHT->getDefaultValue() . 'px'),

                        Toggle::make(SiteSettings::ABOUT_IMAGE_CIRCULAR->value)
                            ->label(__('Circular image?')),

                        TextInput::make(SiteSettings::ABOUT_TITLE->value)
                            ->label(__('About title')),

                        RichEditor::make(SiteSettings::ABOUT_TEXT->value)
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3'],
                                ['blockquote', 'bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ])
                            ->label(__('About text')),
                    ])->columns(1),

                Section::make()
                    ->heading(__('Footer'))
                    ->description(__('Fill details for footer and copyright.'))
                    ->icon(Heroicon::ArrowDown)
                    ->aside()
                    ->schema([
                        RichEditor::make(SiteSettings::FOOTER_TEXT->value)
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['undo', 'redo'],
                            ])
                            ->label(__('Footer text')),

                        TextInput::make(SiteSettings::COPYRIGHT_TEXT->value)
                            ->label(__('Copyright text'))
                            ->helperText(__('Use {year} to display the current year. E.g. ©{year} My Company.')),

                    ])->columns(1),

                Section::make()
                    ->heading(__('Permalinks'))
                    ->description(__('Customize the default slugs for main pages.'))
                    ->icon(Heroicon::OutlinedLink)
                    ->aside()
                    ->schema(function(){
                        $schema = [];
                        foreach (MainPages::cases() as $page) {
                            $schema[] = TextInput::make(SiteSettings::PERMALINKS->value.'.'.$page->value)
                                ->label(__($page->getTitle()));
                        }
                        return $schema;
                    })->columns(1),

                Section::make()
                    ->heading(__('Main Menu'))
                    ->description(__('Your website needs a main menu, right?'))
                    ->icon(Heroicon::OutlinedBars3)
                    ->aside()
                    ->schema([

                        Repeater::make(SiteSettings::MAIN_MENU->value)
                            ->schema([
                                Flex::make([
                                    Select::make('icon')
                                        ->label(__('Icon'))
                                        ->options(
                                            collect(Heroicon::cases())
                                                ->filter(fn (Heroicon $heroicon) => str_starts_with($heroicon->value, 'o-'))
                                                ->mapWithKeys(function (Heroicon $heroicon) {
                                                    $iconName = $heroicon->value;
                                                    $iconHtml = \Filament\Support\generate_icon_html($heroicon)->toHtml();
                                                    $label = "<div class='flex gap-2'>$iconHtml<span style='display: none;'>$iconName</span></div>";
                                                    return [$iconName => $label];
                                                })
                                                ->toArray()
                                        )
                                        ->dehydrateStateUsing(fn ($state) => $state instanceof Heroicon ? $state->value : $state)
                                        ->default(Heroicon::OutlinedHome->value)
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->required()
                                        ->grow(false),

                                    Select::make('page')
                                        ->label(__('Page'))
                                        ->live()
                                        ->options([
                                            ...collect(MainPages::cases())->mapWithKeys(fn (MainPages $page) => [$page->value => $page->getTitle()]),
                                            'custom' => __('Custom URL'),
                                        ]),
                                ]),

                                Group::make([
                                    TextInput::make('name')
                                        ->label(__('Name'))
                                        ->grow()
                                        ->live(onBlur: true),

                                    TextInput::make('url')
                                        ->label(__('URL'))
                                        ->grow(),

                                    Toggle::make('open_in_new_tab')->label(__('Open in new tab?'))->inline(false)->grow(false),
                                ])
                                ->visibleJs(<<<'JS'
                                    $get('page') === 'custom'
                                    JS),
                            ])
                            ->addActionAlignment(Alignment::Start)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => MainPages::tryFrom($state['page'] ?? '')?->getTitle() ?? $state['name'] ?? null),

                        Builder::make(SiteSettings::MAIN_MENU_MORE->value)
                            ->label(__('Dropdown Menu'))
                            ->blocks([
                                Builder\Block::make('divider')
                                    ->label(__('Divider'))
                                    ->schema([
                                        TextInput::make('label')->label(__('Label')),
                                    ]),
                                Builder\Block::make('item')
                                    ->label(__('Dropdown Link'))
                                    ->schema([
                                        Flex::make([
                                            Select::make('icon')
                                                ->label(__('Icon'))
                                                ->options([
                                                    'heroicons' =>
                                                    collect(Heroicon::cases())
                                                        ->mapWithKeys(function (Heroicon $heroicon) {
                                                            $iconName = $heroicon->value;
                                                            $iconHtml = \Filament\Support\generate_icon_html($heroicon)->toHtml();
                                                            $label = "<div class='flex gap-2'>$iconHtml<span style='display: none;'>$iconName</span></div>";
                                                            return ["heroicon-" . $iconName => $label];
                                                        })
                                                        ->toArray(),
                                                    'iconoir' =>
                                                    collect(Iconoir::cases())
                                                        ->mapWithKeys(function (Iconoir $iconoir) {
                                                            $iconName = "iconoir-" . $iconoir->value;
                                                            $iconHtml = svg($iconName, ['class' => 'w-6 h-6'])->toHtml();
                                                            $label = "<div class='flex gap-2'>$iconHtml<span style='display: none;'>$iconName</span></div>";
                                                            return [$iconName => $label];
                                                        })
                                                        ->toArray()
                                                    ],
                                                )
                                                ->dehydrateStateUsing(fn ($state) => $state instanceof Heroicon ? $state->value : $state)
                                                ->default(Heroicon::OutlinedHome->value)
                                                ->searchable()
                                                ->preload()
                                                ->allowHtml()
                                                ->required()
                                                ->grow(false),

                                            Select::make('page')
                                                ->label(__('Page'))
                                                ->live()
                                                ->options([
                                                    ...collect(MainPages::cases())->mapWithKeys(fn (MainPages $page) => [$page->value => $page->getTitle()]),
                                                    'custom' => __('Custom URL'),
                                                ]),
                                        ]),

                                        Group::make([
                                            TextInput::make('name')
                                                ->label(__('Name'))
                                                ->grow()
                                                ->live(onBlur: true),

                                            TextInput::make('url')
                                                ->label(__('URL'))
                                                ->grow(),

                                            Toggle::make('open_in_new_tab')->label(__('Open in new tab?'))->inline(false)->grow(false),
                                        ])
                                            ->visibleJs(<<<'JS'
                                            $get('page') === 'custom'
                                            JS),
                                    ]),
                            ])
                            ->addActionAlignment(Alignment::Start)
                            ->collapsible()
                            ->collapsed(),
                    ]),

                    Section::make()
                        ->heading(__('Footer Menu'))
                        ->description(__('Display some links in the footer.'))
                        ->icon(Heroicon::OutlinedBars3)
                        ->aside()
                        ->schema([

                            Repeater::make(SiteSettings::FOOTER_MENU->value)
                                ->schema([
                                    Select::make('page')
                                        ->label(__('Page'))
                                        ->live()
                                        ->options([
                                            ...collect(MainPages::cases())->mapWithKeys(fn (MainPages $page) => [$page->value => $page->getTitle()]),
                                            'custom' => __('Custom URL'),
                                        ]),

                                    Group::make([
                                        TextInput::make('name')
                                            ->label(__('Name'))
                                            ->grow()
                                            ->live(onBlur: true),

                                        TextInput::make('url')
                                            ->label(__('URL'))
                                            ->grow(),

                                        Toggle::make('open_in_new_tab')->label(__('Open in new tab?'))->inline(false)->grow(false),
                                    ])
                                        ->visibleJs(<<<'JS'
                                        $get('page') === 'custom'
                                        JS),
                                ])
                                ->addActionAlignment(Alignment::Start)
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => MainPages::tryFrom($state['page'] ?? '')?->getTitle() ?? $state['name'] ?? null),

                    ])->columns(1),

            ])->statePath('data');
    }

    public function create(): void
    {
        $state = $this->form->getState();

        // Invalidate palette caches if primary color changed
        $oldPrimaryColor = SiteSettings::PRIMARY_COLOR->get();
        $newPrimaryColor = $state[SiteSettings::PRIMARY_COLOR->value] ?? null;
        if ($oldPrimaryColor !== $newPrimaryColor) {
            cache()->forget("primary_palette_generated");
        }

        foreach (SiteSettings::cases() as $setting) {
            $setting->set($state[ $setting->value ] ?? null);
        }

        Notification::make()->success()->title(__('Settings saved!'))->send();
    }
}
