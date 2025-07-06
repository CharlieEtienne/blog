<?php

namespace App\Filament\CustomBlocks;

use Phiki\Phiki;
use Phiki\Theme\Theme;
use Phiki\Grammar\Grammar;
use App\Enums\SiteSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CodeEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;

class CodeBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'code';
    }

    public static function getLabel(): string
    {
        return 'Code Block';
    }

    /**
     * @throws \Exception
     */
    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Configure the hero section')
            ->schema([
                CodeEditor::make('code')
                    ->language(fn(Get $get) => Language::tryFrom($get('language') ?? Language::Php->value)),

                Select::make('language')
                    ->live()
                    ->default(Language::Php->value)
                    ->formatStateUsing(fn($state) => empty($state) ? Language::Php->value : $state)
                    ->options(collect(Language::cases())->mapWithKeys(fn(Language $language) => [
                        $language->value => $language->name,
                    ])),
            ]);
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function getPreviewLabel(array $config): string
    {
        return "{$config['language']} code block";
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function toPreviewHtml(array $config): string
    {
        return self::codeToHtml(
            code: $config['code'] ?? '',
            grammar: Grammar::tryFrom($config['language'] ?? Grammar::Php),
            theme: self::getCodeTheme(),
        );
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $data
     */
    public static function toHtml(array $config, array $data): string
    {
        return self::codeToHtml(
            code: $config['code'] ?? '',
            grammar: Grammar::tryFrom($config['language'] ?? Grammar::Php),
            theme: self::getCodeTheme(),
        );
    }

    public static function getCodeTheme()
    {
        return filled(SiteSettings::CODE_THEME->get()) ? Theme::tryFrom(SiteSettings::CODE_THEME->get()) : Theme::CatppuccinMacchiato;
    }

    public static function codeToHtml($code, Grammar $grammar, Theme $theme): string
    {
        $phiki = new Phiki();

        return '<div class="not-prose">' . $phiki->codeToHtml($code, $grammar, $theme) . '</div>';
    }
}
