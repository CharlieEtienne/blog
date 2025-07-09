@props([
    'classes' => $attributes->class([
        'flex items-center w-full gap-2 px-4 py-2 transition-colors hover:bg-primary-600/85 dark:hover:bg-primary-500/85 hover:text-white'
    ])
])

@if ($attributes->has('href'))
    <a {{ $classes }}>
        {{ $slot }}
    </a>
@else
    <button {{ $classes }}>
        {{ $slot }}
    </button>
@endif
