<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property string color
 * @property string icon
 * @property string content
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Post> posts
 */
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }
}
