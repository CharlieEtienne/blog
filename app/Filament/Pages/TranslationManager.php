<?php

namespace App\Filament\Pages;

use App\Enums\SiteSettings;
use App\Support\AvailableLanguages;
use App\Services\TranslationService;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class TranslationManager extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected $translationService;

    protected $translationStrings = [];

    protected $translations = [];

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-language';
    protected static string|null|\UnitEnum $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Translation Manager';
    protected static ?string $slug = 'translation-manager';
    protected static ?string $title = 'Translation Manager';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.translation-manager';

    public function mount(): void
    {
        $this->translationService = new TranslationService();
        $this->translationStrings = $this->translationService->scanTranslationStrings();

        $defaultLanguage = SiteSettings::DEFAULT_LANGUAGE->get();

        $this->form->fill([
            'selected_language' => $defaultLanguage,
            'translations' => $this->getTranslationsForLanguage($defaultLanguage),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading(__('Translation Manager'))
                    ->description(__('Manage translations for your application'))
                    ->schema([
                        Select::make('selected_language')
                            ->label(__('Select Language'))
                            ->options(fn() => AvailableLanguages::availableOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function () {
                                $this->loadTranslations();
                            }),

                        Repeater::make('translations')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('key')
                                            ->label(__('Translation Key'))
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(1),

                                        TextInput::make('value')
                                            ->label(__('Translation Value'))
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['key'] ?? null)
                            ->collapsible()
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false)
                    ]),
            ])->statePath('data');
    }

    public function loadTranslations(): void
    {
        $selectedLanguage = $this->data['selected_language'] ?? SiteSettings::DEFAULT_LANGUAGE->get();

        $this->form->fill([
            'selected_language' => $selectedLanguage,
            'translations' => $this->getTranslationsForLanguage($selectedLanguage),
        ]);
    }

    protected function getTranslationsForLanguage(string $language): array
    {
        // Ensure translationService is initialized
        if ($this->translationService === null) {
            $this->translationService = new TranslationService();
        }

        // Ensure translationStrings are loaded
        if (empty($this->translationStrings)) {
            $this->translationStrings = $this->translationService->scanTranslationStrings();
        }

        $translations = $this->translationService->getTranslations($this->translationStrings, $language);

        $formattedTranslations = [];
        foreach ($translations as $key => $value) {
            $formattedTranslations[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return $formattedTranslations;
    }

    public function create(): void
    {
        $formData = $this->form->getState();
        $selectedLanguage = $formData['selected_language'];

        $translations = [];
        foreach ($formData['translations'] as $translation) {
            if (isset($translation['key']) && isset($translation['value'])) {
                $translations[$translation['key']] = $translation['value'];
            }
        }

        // Ensure translationService is initialized
        if ($this->translationService === null) {
            $this->translationService = new TranslationService();
        }

        $this->translationService->saveTranslations($translations, $selectedLanguage);

        Notification::make()->success()->title(__('Translations saved!'))->send();
    }
}
