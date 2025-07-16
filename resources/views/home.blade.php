@php
    use App\Enums\SiteSettings;
    use App\Enums\Analytics;
    use App\Support\ColoredText;
    use Illuminate\Support\Facades\Storage;
    use Filament\Forms\Components\RichEditor\RichContentRenderer;
@endphp
<x-app>

    @if(SiteSettings::HERO_IMAGE->get() && Storage::disk('public')->exists(SiteSettings::HERO_IMAGE->get()))
        <div class="flex justify-center mb-16 -mt-12">
            <img
                src="{{ Storage::disk('public')->url(SiteSettings::HERO_IMAGE->get()) }}"
                alt=""
                class="hero-image w-full {{ SiteSettings::HERO_IMAGE_FULL_WIDTH->get() ? 'object-cover' : 'object-contain' }}"
                style="height: {{ SiteSettings::HERO_IMAGE_HEIGHT->get() }}px;"
            >
        </div>
    @endif

    <div class="container text-center">

        <div class="font-heading font-medium tracking-tight text-black dark:text-white text-3xl/none md:text-5xl lg:text-6xl text-balance">
            {!! ColoredText::get(SiteSettings::HERO_TITLE->get()) !!}
        </div>

        <div class="mt-5 tracking-tight text-black/75 dark:text-white/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight md:mt-8">
            {{ SiteSettings::HERO_SUBTITLE->get() }}
        </div>

        @if(filled(SiteSettings::ABOUT_TEXT->get()) && SiteSettings::ABOUT_TEXT->get() !== "<p></p>")
            <div class="flex gap-2 justify-center items-center mt-7 text-center md:mt-11">
                <x-btn size="md" href="#about" data-pan="{{ Analytics::HOME->value }}-button-about">
                    {{ __('About') }}
                </x-btn>

                <x-btn primary size="md" href="#latest" data-pan="{{ Analytics::HOME->value }}-button-latest">
                    {{ __('Blog') }}
                </x-btn>
            </div>
        @endif

    </div>

    @if ($posts->isNotEmpty())
        <x-section :title="__('Latest posts')" id="latest" class="mt-24 md:mt-32">
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts->take(6) as $post)
                    <li>
                        <x-post :$post/>
                    </li>
                @endforeach
            </ul>

            <div class="mt-16 text-center">
                <x-btn primary wire:navigate href="{{ route('posts.index') }}" data-pan="{{ Analytics::HOME->value }}-button-browse-all-posts">
                    {{ __('Browse all posts') }}
                </x-btn>
            </div>
        </x-section>
    @endif

    @if(filled(SiteSettings::ABOUT_TEXT->get()) && SiteSettings::ABOUT_TEXT->get() !== "<p></p>")
        <x-section
            title="{{ SiteSettings::ABOUT_TITLE->get() }}"
            id="about"
            class="mt-24 lg:max-w-(--breakpoint-md) md:mt-32"
        >
            @if(SiteSettings::ABOUT_IMAGE->get() && Storage::disk('public')->exists(SiteSettings::ABOUT_IMAGE->get()))
                <div class="flex justify-center">
                    <img
                        src="{{ Storage::disk('public')->url(SiteSettings::ABOUT_IMAGE->get()) }}"
                        alt="{{ SiteSettings::ABOUT_TITLE->get() }}"
                        class="{{ SiteSettings::ABOUT_IMAGE_CIRCULAR->get() ? 'rounded-full object-cover' : 'object-contain' }}"
                        style="width: {{ SiteSettings::ABOUT_IMAGE_WIDTH->get() }}px; height: {{ SiteSettings::ABOUT_IMAGE_HEIGHT->get() }}px;"
                    >
                </div>
            @endif
            <div class="max-w-4xl mx-auto beautiful-content">
                {!! RichContentRenderer::make(SiteSettings::ABOUT_TEXT->get() ?? '')->toHtml() !!}
            </div>
        </x-section>
    @endif
</x-app>
