@php
    use App\Support\TableOfContents;
    use App\Enums\Analytics;
@endphp
<x-app
    :description="$post->description"
    :image="$post->image"
    :title="$post->title"
    data-pan="{{ Analytics::POSTS->value }}-{{ $post->slug }}"
>
    <div class="container">
        <div class="max-w-4xl mx-auto">
            <article>
                @if ($post->image_url)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title  }}"
                         class="object-cover w-full rounded-xl ring-1 shadow-xl ring-black/5 aspect-video"/>
                @endif

                <h1 class="mt-12 font-heading font-medium tracking-tight text-center text-black dark:text-white text-balance md:mt-16 text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                    {{ $post->title }}
                </h1>

                <div class="mt-12 md:mt-16">
                    <div class="grid grid-cols-2 gap-4 text-sm leading-tight md:grid-cols-4">
                        <div class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <x-heroicon-o-calendar class="mx-auto mb-2 opacity-75 size-6"/>

                            @if($post->updated_at) {{ __('Modified') }}
                            @elseif($post->published_at) {{ __('Published') }}
                            @else {{ __('Drafted') }}
                            @endif

                            <br/>

                            {{ ($post->updated_at ?? $post->published_at ?? $post->created_at)->isoFormat('ll') }}
                        </div>

                        <div
                            class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <img src="{{ $post->author->getFilamentAvatarUrl() }}" class="mx-auto mb-2 rounded-full size-6"/>
                            {{ __('Written by') }}<br/>
                            {{ $post->author->name }}
                        </div>

                        <a href="#comments" class="group">
                            <div @class([
                                'flex-1 p-3 text-center transition-colors rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-950 group-hover:text-primary-900 dark:group-hover:text-primary-100',
                                'text-primary-600 dark:text-primary-500' => $post->comments_count > 0,
                            ])>
                                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 opacity-75 size-6"/>
                                {{ $post->comments_count }}<br/>
                                {{ trans_choice('comment|comments', $post->comments_count) }}
                            </div>
                        </a>

                        <div class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <x-heroicon-o-clock class="mx-auto mb-2 opacity-75 size-6" />
                            {{ $post->read_time }}<br/>
                            {{ trans_choice('minute read|minutes read', $post->read_time) }}
                        </div>
                    </div>

                    @if (! empty($post->categories))
                        <div class="flex gap-2 mt-6 justify-center flex-wrap place-self-center">
                            @foreach ($post->categories->take(2) as $category)
                                <a wire:navigate href="{{ route('categories.show', ['category' => $category]) }}" class="px-2 py-1 text-xs font-medium uppercase rounded-sm border border-gray-200 dark:border-gray-700 transition-colors hover:border-primary-300 dark:hover:border-primary-700 hover:text-primary-600 dark:hover:text-primary-400">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if (! empty($post->tags))
                        <div class="flex gap-2 mt-6 justify-center flex-wrap place-self-center">
                            @foreach ($post->tags->take(10) as $tag)
                                <a wire:navigate href="{{ route('tags.show', ['tag' => $tag]) }}" class="px-2 py-1 text-sm rounded-sm bg-gray-50 dark:bg-gray-800 transition-colors hover:bg-primary-100 hover:text-primary-600 dark:hover:bg-primary-950 dark:hover:text-primary-300">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @php
                        $tableOfContents = TableOfContents::generate($post->body);
                    @endphp

                    @if(!blank($tableOfContents))
                        <div class="mt-12">
                            {!! $tableOfContents->render() !!}
                        </div>
                        <hr class="mt-12 text-gray-100 dark:text-gray-800"/>
                    @endif

                    <div class="beautiful-content mt-8">
                        {!! $post->formattedContent !!}
                    </div>

                    @if (! empty($relatedPosts))
                        <hr class="mt-12 text-gray-100 dark:text-gray-800"/>

                        <h3 class="text-xl font-medium text-gray-900 dark:text-gray-200 mt-8 mb-2">
                            {{ __('Continue reading') }}
                        </h3>
                        <p class="tracking-tight text-gray-500 text-lg/tight mb-8">
                            {{ __('Here are some other articles you may like') }}
                        </p>

                        <ul class="grid gap-10 gap-y-16 xl:gap-x-16 sm:grid-cols-2">
                            @foreach ($relatedPosts as $relatedPost)
                                <li>
                                    <x-post :post="$relatedPost"/>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </article>

            @if ($post->comments_count)
                <div class="mt-24">
                    <livewire:comments :post-id="$post->id" />
                </div>
            @endif
        </div>

    </div>

    @if ($post->published_at)
        @php
            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'author' => [
                    '@type' => 'Person',
                    'name' => $post->author->name,
                    'url' => route('home') . '#about',
                ],
                'headline' => $post->title,
                'description' => $post->description,
                'image' => $post->image_url,
                'datePublished' => $post->published_at->toIso8601String(),
                'dateModified' => ($post->updated_at ?? $post->published_at)->toIso8601String(),
            ];
        @endphp

        <script type="application/ld+json">
            {!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
</x-app>
