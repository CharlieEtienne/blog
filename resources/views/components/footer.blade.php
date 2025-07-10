@php
    use App\Enums\MainPages;
    use App\Enums\SiteSettings;
@endphp

<div {{ $attributes->class('bg-gray-100 dark:bg-gray-800') }}>
    <footer class="container py-8 lg:max-w-(--breakpoint-md) *:[&_a]:hover:underline *:[&_a]:font-medium">
        <div class="flex flex-row justify-center items-center w-full">
            <div>
                <nav class="grid sm:auto-cols-[minmax(50px,100px)] sm:grid-flow-col gap-y-2 gap-x-6 place-items-center mx-auto w-full">
                    @foreach(SiteSettings::FOOTER_MENU->get() ?? [] as $footerMenuItem)
                        @php
                            if(filled(data_get($footerMenuItem, 'page')) && data_get($footerMenuItem, 'page') !== 'custom'){
                                $url = url(data_get(SiteSettings::PERMALINKS->get(), data_get($footerMenuItem, 'page')));
                                $name = MainPages::tryFrom(data_get($footerMenuItem, 'page'))->getTitle();
                            }
                            else{
                                $url = url(data_get($footerMenuItem, 'url'));
                                $name = data_get($footerMenuItem, 'name');
                            }
                        @endphp

                        <a
                            href="{{ $url }}"
                            target="{{ data_get($footerMenuItem, 'open_in_new_tab') ? '_blank' : '' }}"
                            @if(!data_get($footerMenuItem, 'open_in_new_tab') && !str_contains($url,'#')) wire:navigate.hover @endif
                        >
                            {{ $name }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="mt-8 text-center">
            {!! SiteSettings::FOOTER_TEXT->get() !!}
        </div>

        <p class="mt-8 text-center text-gray-400">{{ str(SiteSettings::COPYRIGHT_TEXT->get())->replace('{year}', date('Y')) }}</p>
    </footer>
</div>
