@php
    use App\Support\Icons;
    use App\Enums\SiteSettings;
@endphp

<nav {{ $attributes->class('flex items-center gap-6 md:gap-8 justify-between') }}>

    <x-logo/>

    <div class="menu flex items-center gap-6 md:gap-8 font-normal text-sm">
        <a
            wire:navigate
            href="{{ route('home') }}"
            class="transition-colors hover:text-primary-600 @if(request()->routeIs('home')) text-primary-600 @endif }}"
        >
            {!! Icons::getHeroicon(name: 'home', isOutlined: !request()->routeIs('home'), class: 'mx-auto size-6') !!}
            Home
        </a>

        <a
            wire:navigate
            href="{{ route('posts.index') }}"
            class="transition-colors hover:text-primary-600 @if(request()->routeIs('posts.index')) text-primary-600 @endif }}"
        >
            {!! Icons::getHeroicon(name: 'newspaper', isOutlined: !request()->routeIs('posts.index'), class: 'mx-auto size-6') !!}
            Blog
        </a>

        <x-dropdown>
            <x-slot:btn
                class="transition-colors hover:text-primary-600 cursor-pointer"
            >
                <div class="menu-icon" x-bind:class="{ 'active': open }">
                    <input class="menu-icon__checkbox" type="checkbox" name="more"/>
                    <div>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                {{ __('More') }}
            </x-slot>

            <x-slot:items class="mt-4">

                <x-dropdown.item
                    href="{{ route('home') }}#about"
                >
                    <x-heroicon-o-question-mark-circle class="size-4"/>
                    About
                </x-dropdown.item>

                <x-dropdown.item
                    href="{{ blank(SiteSettings::CONTACT_EMAIL->get()) ? '#' : 'mailto:' . SiteSettings::CONTACT_EMAIL->get() }}"
                >
                    <x-heroicon-o-envelope class="size-4"/>
                    Contact me
                </x-dropdown.item>

                <x-dropdown.divider>
                    Code and free tools
                </x-dropdown.divider>

                <x-dropdown.item
                    href="https://github.com/charlieetienne/charlieetienne.com"
                    target="_blank"
                >
                    <x-iconoir-git-fork class="size-4"/>
                    Source code
                </x-dropdown.item>

                <x-dropdown.divider>
                    Follow me
                </x-dropdown.divider>

                <x-dropdown.item
                    href="https://github.com/charlieetienne"
                    target="_blank"
                >
                    <x-iconoir-github class="size-4"/>
                    GitHub
                </x-dropdown.item>

                <x-dropdown.item
                    href="https://x.com/charlieetienne"
                    target="_blank"
                >
                    <x-iconoir-x class="size-4"/>
                    X
                </x-dropdown.item>
            </x-slot>
        </x-dropdown>
    </div>
</nav>
