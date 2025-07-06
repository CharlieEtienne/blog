<?php

namespace App\Models;

use App\Enums\SiteSettings;
use Illuminate\Support\Facades\Storage;
use App\Filament\CustomBlocks\CodeBlock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms\Components\RichEditor\RichContentRenderer;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property ?array body
 * @property ?string excerpt
 * @property ?string image
 * @property ?string image_url
 * @property float|int readTime
 * @property ?string formattedContent
 * @property ?string textContent
 * @property ?string description
 * @property ?\Illuminate\Support\Carbon published_at
 * @property ?int author_id
 * @property-read ?\App\Models\User author
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Category> categories
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Tag> tags
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Comment> comments
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $withCount = ['comments'];

    protected $casts = [
        'published_at' => 'datetime',
        'body' => 'array',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_post');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function readTime() : Attribute
    {
        return Attribute::make(
            fn () => ceil(str_word_count($this->textContent) / 200),
        )->shouldCache();
    }

    public function formattedContent(): Attribute
    {
        return Attribute::make(
            fn () => $this->body ?
                RichContentRenderer::make($this->body)
                    ->customBlocks([
                        CodeBlock::class,
                    ])
                    ->toHtml()
                : '',
        )->shouldCache();
    }

    public function textContent(): Attribute
    {
        return Attribute::make(
            fn () => strip_tags($this->formattedContent)
        )->shouldCache();
    }

    public function description() : Attribute
    {
        return Attribute::make(
            fn () => str($this->excerpt ?? $this->textContent ?? '')->limit(160),
        )->shouldCache();
    }

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query->whereNotNull('published_at');
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            function () {
                if($this->image && Storage::disk('public')->exists($this->image)) {
                    return Storage::disk('public')->url($this->image);
                }
                elseif(SiteSettings::POST_DEFAULT_IMAGE->get() && Storage::disk('public')->exists(SiteSettings::POST_DEFAULT_IMAGE->get())) {
                    return Storage::disk('public')->url(SiteSettings::POST_DEFAULT_IMAGE->get());
                }
                return asset('img/placeholders/post.png');
            }
        )->shouldCache();
    }
}
