<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property ?string body
 * @property ?string excerpt
 * @property ?string image
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

    protected $casts = [
        'published_at' => 'datetime',
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

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query->whereNotNull('published_at');
    }
}
