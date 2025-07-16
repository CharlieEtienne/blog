# Translation Manager

The Translation Manager is a Filament page that allows you to manage translations for your application. It automatically detects translation strings in your code and provides an interface to translate them into different languages.

## Features

- Automatically detects translation strings in your code (using `__()`, `trans()`, `trans_choice()`, and `@lang()` functions)
- Provides an interface to translate strings into different languages
- Saves translations to language files in the `resources/lang` directory
- Supports all languages configured in the `SiteSettings::LANGUAGES` setting

## How to Use

1. Navigate to the Translation Manager page in the admin panel (Settings > Translation Manager)
2. Select a language from the dropdown menu
3. The page will display all detected translation strings in your code
4. Enter translations for each string
5. Click the "Save translations" button to save your changes

## How It Works

### Detection of Translation Strings

The Translation Manager scans your application code for translation strings using the following patterns:

- `__('string')`
- `trans('string')`
- `trans_choice('string|strings', $arg)`
- `@lang('string')`

It scans the following directories:
- `app/Enums`
- `app/Livewire`
- `app/Models`
- `resources/views/`

You can exclude some subdirectories like so:

```php
// Exclusions
$this->exclusions = [
    resource_path('views/filament'),
];
```

### Storage of Translations

Translations are stored in JSON files in the `resources/lang` directory. Each language has its own JSON file, e.g., `resources/lang/en.json`, `resources/lang/fr.json`, etc.

The JSON files have the following format:

```json
{
    "Original string": "Translated string",
    "Another original string": "Another translated string"
}
```

### Configuration

The available languages are configured in the `SiteSettings::LANGUAGES` setting. You can add or remove languages from this setting to change the available languages in the Translation Manager.

## Troubleshooting

If you encounter any issues with the Translation Manager, try the following:

- Make sure the `resources/lang` directory is writable by the web server
- Check that the language you're trying to translate is enabled in the `SiteSettings::LANGUAGES` setting
- If a translation string is not detected, make sure it's using one of the supported functions (`__()`, `trans()`, `trans_choice()`, or `@lang()`)
- If changes don't appear immediately, try clearing the application cache (`php artisan cache:clear`)
