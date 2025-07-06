<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Support\TipTapFaker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 6));
        $slug  = str($title)->slug();

        return [
            'title'        => $title,
            'body'         => TipTapFaker::content(
                TipTapFaker::h2(),
                TipTapFaker::paragraph(),
                TipTapFaker::paragraph(),
                TipTapFaker::h3(),
                TipTapFaker::paragraph(),
                TipTapFaker::h3(),
                TipTapFaker::paragraph(),
                TipTapFaker::h2(),
                TipTapFaker::paragraph(),
                TipTapFaker::h3(),
                TipTapFaker::paragraph(),
                TipTapFaker::h3(),
                TipTapFaker::paragraph(),
            ),
            'slug'         => $slug,
            'excerpt'      => null,
            'image'        => fake()->image(),
            'published_at' => now(),
        ];
    }

    public function withCategories(Collection $categories = null) : self
    {
        return $this->afterCreating(
            fn (Post $post) => $post->categories()->sync(
                ! empty($categories)
                    ? $categories
                    : Category::factory(random_int(1, 3))->create()
            )
        );
    }

    public function withTags(Collection $tags = null) : self
    {
        return $this->afterCreating(
            fn (Post $post) => $post->tags()->sync(
                ! empty($tags)
                    ? $tags
                    : Tag::factory(random_int(1, 3))->create()
            )
        );
    }

    public function withComments(?int $count = null, ?Collection $recycledUsers = null) : self
    {
        return $this->afterCreating(
            fn (Post $post) => Comment::factory($count ?? random_int(1, 10))
                ->recycle($recycledUsers ?? User::factory(10)->create(), 'author')
                ->create(['post_id' => $post->id])
        );
    }
}
