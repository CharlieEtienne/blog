<section {{ $attributes->class('container scroll-mt-4') }}>
    @if (! empty($title))
        <h1 @class([
            'font-bold tracking-widest text-center text-black dark:text-white uppercase text-balance mb-8',
        ])>
            {!! $title !!}
        </h1>
    @endif

    {{ $slot }}
</section>
