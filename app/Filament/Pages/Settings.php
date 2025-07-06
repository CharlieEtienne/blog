<?php

namespace App\Filament\Pages;

use App\Enums\Font;
use App\Enums\SiteSettings;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;

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

                        ColorPicker::make(SiteSettings::PRIMARY_COLOR->value),

                        Select::make(SiteSettings::HEADING_FONT->value)
                            ->options(Font::class)
                            ->searchable(),

                        Select::make(SiteSettings::BODY_FONT->value)
                            ->options(Font::class)
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

            ])->statePath('data');
    }

    public function create(): void
    {
        $state = $this->form->getState();

        foreach (SiteSettings::cases() as $setting) {
            $setting->set($state[ $setting->value ] ?? null);
        }

        Notification::make()->success()->title(__('Settings saved!'))->send();
    }
}
