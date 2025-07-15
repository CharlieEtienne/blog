<!-- Language Switcher -->
<x-dropdown>
    <x-slot:btn class="transition-colors hover:text-primary-600 dark:hover:text-primary-500 cursor-pointer">
        <div class="flex items-center">
            <x-heroicon-o-language class="h-4 w-4 mr-1 opacity-50" />
            <span class="mr-1">{{ strtoupper(app()->getLocale()) }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </x-slot>

    <x-slot:items class="mt-4">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <x-dropdown.item
                rel="alternate"
                hreflang="{{ $localeCode }}"
                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                @class([
                    'text-primary-700/85! dark:text-primary-400/85! bg-black/5! dark:bg-white/5!' => app()->getLocale() === $localeCode
                ])
            >
                {{ ucfirst($properties['native']) }}
            </x-dropdown.item>
        @endforeach
    </x-slot>
</x-dropdown>
