<?php

namespace Database\Factories;

use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->word());
        $slug = str($name)->slug();

        return [
            'name' => $name,
            'slug' => $slug,
            'content' => fake()->paragraphs(rand(1, 3), true),
            'color' => fake()->hexColor(),
            'icon' => Heroicon::cases()[array_rand(Heroicon::cases())]->value,
        ];
    }
}
