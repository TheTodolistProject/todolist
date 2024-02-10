<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Log;
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
//        Log::info('in task factory');
        $project = Project::
            inRandomOrder()
            ->first();
        $title =fake()->text(10);
        return [
            'title' => $title,
            'project_id' => $project->id,
            'status' => array_rand(TaskStatusEnum::getValues()),
            'detail' => fake()->text(200),
            'start_date' => fake()->dateTimeThisYear(),
        ];
    }
}
