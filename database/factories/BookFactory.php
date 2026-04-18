<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 6));
        $originalPrice = fake()->numberBetween(50, 500) * 1000;
        $salePrice = round($originalPrice * fake()->randomFloat(2, 0.6, 0.95));

        return [
            'sku' => 'BOOK-' . strtoupper(Str::random(6)),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . strtolower(Str::random(4)),
            'category_id' => Category::whereNotNull('parent_id')->inRandomOrder()->value('id'),
            'publisher_id' => Publisher::inRandomOrder()->value('id'),
            'cost_price' => round($salePrice * 0.6),
            'original_price' => $originalPrice,
            'sale_price' => $salePrice,
            'stock' => fake()->numberBetween(0, 1000),
            'sold_count' => fake()->numberBetween(0, 5000),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(15),
            'cover_image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400&h=600',
            'isbn' => fake()->isbn13(),
            'pages' => fake()->numberBetween(100, 800),
            'weight' => fake()->numberBetween(150, 900),
            'cover_type' => fake()->randomElement(['hardcover', 'paperback']),
            'language' => 'Tiếng Việt',
            'published_year' => fake()->numberBetween(1990, 2026),
            'rating_avg' => fake()->randomFloat(2, 3.0, 5.0),
            'rating_count' => fake()->numberBetween(0, 3000),
            'status' => 'in_stock',
            'is_featured' => fake()->boolean(30),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0, 'status' => 'out_of_stock']);
    }

    public function inStock(): static
    {
        return $this->state(fn () => ['status' => 'in_stock', 'stock' => 100]);
    }
}
