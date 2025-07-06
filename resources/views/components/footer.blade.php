@php
    use App\Enums\SiteSettings;
@endphp

<div {{ $attributes->class('bg-gray-100') }}>
    <footer class="container py-8 lg:max-w-(--breakpoint-md) *:[&_a]:underline *:[&_a]:font-medium">
        <div class="flex flex-row justify-center items-center w-full">
            <div>
                <nav class="grid sm:auto-cols-[minmax(50px,100px)] sm:grid-flow-col gap-y-2 gap-x-6 place-items-center mx-auto w-full">
                    <a wire:navigate href="{{ route('home') }}">Home</a>
                    <a wire:navigate href="{{ route('posts.index') }}">Blog</a>
                    <a href="{{ route('home') }}#about">About</a>
                    <a href="{{ blank(SiteSettings::CONTACT_EMAIL->get()) ? '#' : 'mailto:' . SiteSettings::CONTACT_EMAIL->get() }}">Contact</a>
                </nav>
            </div>
        </div>

        <div class="mt-8 text-center">
            {!! SiteSettings::FOOTER_TEXT->get() !!}
        </div>

        <p class="mt-8 text-center text-gray-400">{{ str(SiteSettings::COPYRIGHT_TEXT->get())->replace('{year}', date('Y')) }}</p>
    </footer>
</div>
