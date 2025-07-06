<x-app
    title="{{ __('Articles tagged :tag_name', ['tag_name' => $tag->name]) }}"
    description="{{ str($tag->content)->limit(160) }}"
>
    <x-section
        :title="__('Articles tagged :tag_name', ['tag_name' => $tag->name])"
    >
        @if ($posts->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500 text-center my-16 text-lg">
                {{ __('No articles for this tag yet!') }}
            </div>
        @endif

        @if ($posts->hasPages())
            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @endif
    </x-section>
</x-app>
