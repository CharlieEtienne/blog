<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Charlie Etienne',
            'email' => 'hello@charlieetienne.com',
        ]);

        $users = User::factory(10)->create();

        $categories = Category::factory(10)->create();

        $tags = Tag::factory(20)->create();

        Post::factory(30)
            ->for($admin, 'author')
            ->withCategories($categories)
            ->withTags($tags)
            ->withComments(recycledUsers: $users)
            ->create();
    }
}
