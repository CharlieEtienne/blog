<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Post> posts
 */
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
