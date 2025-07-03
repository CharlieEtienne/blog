<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $with = [
        'author',
        'children',
        'children.author',
    ];

    protected function casts() : array
    {
        return [
            'modified_at' => 'datetime',
        ];
    }

    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
