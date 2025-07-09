@php
    use App\Enums\SiteSettings;
    use Illuminate\Support\Facades\Storage;

    $logo = SiteSettings::SITE_LOGO->get();
@endphp

<div class="logo">
    <a
        wire:navigate
        href="{{ route('home') }}"
        class="flex gap-3 items-center"
    >
        {{-- Logo --}}
        <div>
            @if($logo && Storage::disk('public')->exists($logo))
                <img
                    class="h-12 w-40 object-contain object-left"
                    src="{{ Storage::disk('public')->url($logo) }}"
                    alt="{{ config('app.name') }}"
                >
            @else
                <div class="bg-gray-950 dark:bg-gray-50 dark:text-black h-10 w-10 flex items-center justify-center rounded-lg text-2xl text-white font-semibold">
                    {{ str(config('app.name'))->take(1)->upper() }}
                </div>
            @endif
        </div>

        {{-- Site name --}}
        <span @class([
            'site-name font-semibold uppercase text-base tracking-widest text-gray-950 dark:text-gray-50 sr-only',
            'sm:not-sr-only' => SiteSettings::DISPLAY_SITE_NAME->get(),
        ])>
            {{ SiteSettings::SITE_NAME->get() }}
        </span>
    </a>
</div>
