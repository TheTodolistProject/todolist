<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::
            inRandomOrder()
            ->first();
        $title =fake()->text(10);
        return [
            'title' => $title,
            'project_id' => $project->id,
            'status' => fake()->boolean(),
            'detail' => fake()->text(200),
            'start_date' => fake()->dateTimeThisYear(),
        ];
    }
}
