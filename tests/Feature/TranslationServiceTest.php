<?php

use Illuminate\Support\Str;
use App\Services\TranslationService;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->fakeLangPath = storage_path('framework/testing/lang_' . Str::random(8));
    File::makeDirectory($this->fakeLangPath, 0755, true, true);

    $this->translationService = new TranslationService(pathToLangDirectory: $this->fakeLangPath);
});

it('can scan translation strings', function () {
    // Create a temporary test file with translation strings
    $testDir = storage_path('app/test');
    $testFile = $testDir . '/test.php';

    if (!File::exists($testDir)) {
        File::makeDirectory($testDir, 0755, true);
    }

    $content = <<<'PHP'
        <?php

        echo __('Test translation string');
        echo __("Another test string");
        echo trans('Yet another test');
        echo trans_choice('string|strings', 2);
        echo @lang('Blade directive test');

    PHP;

    File::put($testFile, $content);

    $strings = $this->translationService->extractTranslationStrings($testFile);

    // Clean up
    File::delete($testFile);
    File::deleteDirectory($testDir);

    // Assert
    expect($strings)->toContain('Test translation string')
        ->and($strings)->toContain('Another test string')
        ->and($strings)->toContain('Yet another test')
        ->and($strings)->toContain('string|strings')
        ->and($strings)->toContain('Blade directive test');
});

it('can get and save translations', function () {
    $testLocale = 'en';
    $testStrings = ['Test string', 'Another test string'];
    $testTranslations = [
        'Test string' => 'Test string translation',
        'Another test string' => 'Another test string translation',
    ];

    // Get translations (should be empty initially)
    $translations = $this->translationService->getTranslations($testStrings, $testLocale);
    foreach ($testStrings as $string) {
        expect($translations)->toHaveKey($string)
            ->and($translations[$string])->toBe('');
    }

    // Save translations
    $this->translationService->saveTranslations($testTranslations, $testLocale);

    // Verify saved translations
    $translations = $this->translationService->getTranslations($testStrings, $testLocale);
    foreach ($testStrings as $string) {
        expect($translations)->toHaveKey($string)
            ->and($translations[$string])->toBe($testTranslations[$string]);
    }

    // Clean up
    File::deleteDirectory($this->fakeLangPath);
});
