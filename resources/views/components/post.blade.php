@props(['post'])

<div {{ $attributes->class('flex flex-col h-full') }}>
    <a wire:navigate href="{{ route('posts.show', ['post' => $post]) }}">
        <img src="{{ $post->image_url }}" alt="{{ $post->title  }}" class="object-cover rounded-xl ring-1 shadow-md transition-opacity shadow-black/5 dark:shadow-white/5 aspect-video hover:opacity-50 ring-black/5 dark:ring-white/5" />
    </a>

    @if (! empty($post->categories))
        <div class="flex gap-2 mt-6">
            @foreach ($post->categories->take(3) as $category)
                <a wire:navigate href="{{ route('categories.show', ['category' => $category]) }}" class="px-2 py-1 text-xs font-medium uppercase rounded-sm border border-gray-200 dark:border-gray-700 transition-colors hover:border-primary-300 dark:hover:border-primary-700 hover:text-primary-600 dark:hover:text-primary-400">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="flex gap-6 justify-between items-center mt-5">
        <a wire:navigate href="{{ route('posts.show', ['post' => $post]) }}" class="font-bold transition-colors text-xl/tight hover:text-primary-600 dark:hover:text-primary-400">
            {{ $post->title }}
        </a>

        <a
            wire:navigate
            href="#"
            class="flex-none"
        >
            <img
                src="{{ $post->author->getFilamentAvatarUrl() }}"
                alt="{{ $post->author->name }}"
                class="rounded-full ring-1 ring-black/5 dark:ring-white/5 size-10"
            />
        </a>
    </div>

    <div class="mt-4 grow">
        {!! $post->description ?? '' !!}
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6 text-sm/tight">
        <div class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg">
            <x-heroicon-o-calendar class="mx-auto mb-1 opacity-75 size-5" />
            {{ ($post->updated_at ?? $post->published_at)->isoFormat('ll') }}
        </div>

        <a href="{{ route('posts.show', ['post' => $post]) }}#comments" class="group">
            <div class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg transition-colors hover:bg-primary-50 dark:hover:bg-primary-950 group-hover:text-primary-900 dark:group-hover:text-primary-100">
                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-1 opacity-75 size-5" />
                {{ $post->comments_count }} {{ trans_choice('comment|comments', $post->comments_count) }}
            </div>
        </a>

        <div class="flex-1 p-3 text-center bg-gray-50 dark:bg-gray-800 rounded-lg">
            <x-heroicon-o-clock class="mx-auto mb-1 opacity-75 size-5" />
            {{ trans_choice(':count minute|:count minutes', $post->read_time) }}
        </div>
    </div>
</div>
