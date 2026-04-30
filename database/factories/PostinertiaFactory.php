<?php

namespace Database\Factories;

use App\Models\Postinertia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Postinertia>
 */
class PostinertiaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'content' => $this->faker->paragraphs(3, true),
        ];
    }
}
