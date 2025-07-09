
@props(['comment'])

<div {{ $attributes }}>
    <div class="flex gap-4">
        <img
            src="{{ $comment->author->getFilamentAvatarUrl() }}"
            alt="{{ $comment->author->name }}"
            class="flex-none mt-1 rounded-full shadow-sm shadow-black/5 dark:shadow-white/5 ring-1 ring-black/10 dark:ring-white/10 size-7 md:size-8"
        />

        <div class="grow">
            <div class="flex items-center gap-2">
                <div class="font-medium">
                    {{ $comment->author->name }}
                </div>

                <span class="text-gray-500">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="beautiful-content px-4 py-3 mt-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                {!! $comment->content !!}
            </div>
        </div>
    </div>

    @if ($comment->children->isNotEmpty())
        <div class="grid gap-8 mt-8 ml-11 md:ml-12">
            @foreach ($comment->children as $child)
                <x-comment :comment="$child" />
            @endforeach
        </div>
    @endif
</div>
