<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->text(10);
        return [
            'title' => $title,
            'detail' => fake()->text(200),
            'slug' => Str::slug($title , '-' ),
            'progress' => fake()->randomFloat(2, 0, 100),
            'start_date' => fake()->dateTime(),
        ];
    }
}
