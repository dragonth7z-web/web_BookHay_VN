<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . strtolower(Str::random(3)),
            'country' => fake()->country(),
            'biography' => fake()->paragraph(),
            'avatar' => null,
        ];
    }
}
