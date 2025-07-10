<div
    {{ $attributes->merge([
        'x-data' => '{ open: false }',
    ]) }}
>
    <button {{ $btn->attributes->merge(['@click' => 'open = !open']) }}>
        {{ $btn }}
    </button>

    <div
        {{
            $items
                ->attributes
                ->class('z-10 py-2 text-base bg-white/85 dark:bg-gray-800/85 backdrop-blur-md rounded-lg shadow-lg ring-1 ring-black/10 dark:ring-white/10 min-w-[240px]')
                ->merge([
                    'x-anchor.bottom' => '$el.previousElementSibling',
                    'x-cloak' => true,
                    'x-show' => 'open',
                    '@click.away' => 'open = false',
                ])
        }}
        x-transition
    >
        {{ $items }}
    </div>
</div>
