<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(rand(2, 4), true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => 'menu_book',
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(0, 20),
            'is_visible' => true,
        ];
    }

    public function child(int $parentId): static
    {
        return $this->state(fn () => ['parent_id' => $parentId]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['is_visible' => false]);
    }
}
