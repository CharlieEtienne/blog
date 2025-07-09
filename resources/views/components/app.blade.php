@php
    use Illuminate\Support\Facades\Storage;
    use App\Enums\SiteSettings;
    use Filament\Support\Facades\FilamentColor;
@endphp

@props([
    'description' => '',
    'image' => '',
    'title' => config('app.name'),
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>

    <title>{{ $title }}</title>

    <meta name="title" content="{{ $title }}"/>
    <meta name="description" content="{{ $description }}"/>

    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:title" content="{{ $title }}"/>
    <meta property="og:description" content="{{ $description }}"/>
    <meta property="og:image" content="{{ $image }}"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:url" content="{{ url()->current() }}"/>
    <meta name="twitter:title" content="{{ $title }}"/>
    <meta name="twitter:description" content="{{ $description }}"/>
    <meta name="twitter:image" content="{{ $image }}"/>

    <livewire:styles/>

    @vite('resources/css/app.css')

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin/>
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family={{ SiteSettings::BODY_FONT->get() }}:wght@100..900&display=swap"/>
    @if(SiteSettings::HEADING_FONT->get() !== SiteSettings::BODY_FONT->get())
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family={{ SiteSettings::HEADING_FONT->get() }}:wght@100..900&display=swap"/>
    @endif
    <style>
        :root {
            --font-heading: {{ SiteSettings::HEADING_FONT->get() }}, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --font-sans: {{ SiteSettings::BODY_FONT->get() }}, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --font-mono: {{ SiteSettings::CODE_FONT->get() }}, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            {{
                implode("", array_map(fn ($key, $value) => "--color-primary-{$key}: {$value};",
                array_keys(FilamentColor::getColor('primary')),
                array_values(FilamentColor::getColor('primary'))
                ))
            }}
        }
    </style>

    {{-- Favicon --}}
    @if(SiteSettings::FAVICON->get() && Storage::disk('public')->exists(SiteSettings::FAVICON->get()))
         <link rel="icon" href="{{ Storage::disk('public')->url(SiteSettings::FAVICON->get()) }}" />
         <link rel="shortcut icon" href="{{ Storage::disk('public')->url(SiteSettings::FAVICON->get()) }}" />
         <link rel="apple-touch-icon" href="{{ Storage::disk('public')->url(SiteSettings::FAVICON->get()) }}" />
    @endif

</head>

<body {{ $attributes->class('font-light text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900') }}>
    <div class="flex flex-col min-h-screen">

        <header class="container mt-4 xl:max-w-(--breakpoint-lg)">
            <x-nav/>
        </header>

        <main class="grow py-12 md:py-16">
            {{ $slot }}
        </main>

        <x-footer/>
    </div>

    @livewireScriptConfig

    @vite('resources/js/app.js')
</body>

</html>
