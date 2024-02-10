<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Log;
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
//        Log::info('in project factory');
        $title = fake()->text(10);
        return [
            'title' => $title,
            'detail' => fake()->text(200),
            'start_date' => fake()->dateTime(),
        ];
    }
}
