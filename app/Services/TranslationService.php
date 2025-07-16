<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class TranslationService
{

    public array $directories = [];
    public array $exclusions = [];
    public array $extensions = [];

    public function __construct(
        public ?string $pathToLangDirectory = null,
    )
    {
        $this->pathToLangDirectory ??= resource_path("lang/");
    }

    /**
     * Scan the project files for translation strings
     *
     * @return array
     */
    public function scanTranslationStrings(): array
    {
        $translationStrings = [];

        // Directories to scan
        $this->directories = [
            app_path('/Enums'),
            app_path('/Livewire'),
            app_path('/Models'),
            resource_path('views'),
        ];

        // Exclusions
        $this->exclusions = [
            resource_path('views/filament'),
        ];

        // File extensions to scan
        $this->extensions = ['php', 'blade.php'];

        foreach ($this->directories as $directory) {
            $translationStrings = array_merge(
                $translationStrings,
                $this->scanDirectory($directory)
            );
        }

        // Remove duplicates and sort
        $translationStrings = array_unique($translationStrings);
        sort($translationStrings);

        return $translationStrings;
    }

    /**
     * Scan a directory for translation strings
     *
     * @param string $directory
     * @return array
     */
    public function scanDirectory(string $directory): array
    {
        $translationStrings = [];

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            if (in_array($file->getExtension(), $this->extensions) && !str($file->getPathname())->contains($this->exclusions)) {
                $translationStrings = array_merge(
                    $translationStrings,
                    $this->extractTranslationStrings($file->getPathname())
                );
            }
        }

        return $translationStrings;
    }

    /**
     * Extract translation strings from a file
     *
     * @param string $filePath
     * @return array
     */
    public function extractTranslationStrings(string $filePath): array
    {
        $content = File::get($filePath);
        $translationStrings = [];

        $patterns = [
            "/__\(\s*['\"](.+?)['\"]\s*\)/", // match __('string')
            "/trans\(\s*['\"](.+?)['\"]\s*\)/", // match trans('string')
            "/trans_choice\(\s*['\"](.*?)['\"]\s*,/s", // match trans_choice('string', $arg)
            "/@lang\(\s*['\"](.+?)['\"]\s*\)/", // match @lang('string')
        ];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $content, $matches);
            if (!empty($matches[1])) {
                $translationStrings = array_merge($translationStrings, $matches[1]);
            }
        }

        return $translationStrings;
    }

    /**
     * Get translations for a specific language
     *
     * @param array $strings
     * @param string $locale
     * @return array
     */
    public function getTranslations(array $strings, string $locale): array
    {
        $translations = [];

        // Create or load the JSON translation file
        $jsonPath = $this->pathToLangDirectory . "/{$locale}.json";
        $existingTranslations = [];

        if (File::exists($jsonPath)) {
            $existingTranslations = json_decode(File::get($jsonPath), true) ?? [];
        }

        foreach ($strings as $string) {
            $translations[$string] = $existingTranslations[$string] ?? '';
        }

        return $translations;
    }

    /**
     * Save translations for a specific language
     *
     * @param array $translations
     * @param string $locale
     * @return bool
     */
    public function saveTranslations(array $translations, string $locale): bool
    {
        // Filter out empty translations
        $translations = array_filter($translations, function ($translation) {
            return !empty($translation);
        });

        // Save the JSON translation file
        $jsonPath = $this->pathToLangDirectory . "/{$locale}.json";

        // Merge with existing translations if the file exists
        $existingTranslations = [];
        if (File::exists($jsonPath)) {
            $existingTranslations = json_decode(File::get($jsonPath), true) ?? [];
        }

        $mergedTranslations = array_merge($existingTranslations, $translations);

        // Sort by keys
        ksort($mergedTranslations);

        // Save the file
        File::put($jsonPath, json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;
    }
}
