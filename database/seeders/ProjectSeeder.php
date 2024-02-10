<?php

namespace Database\Seeders;

use App\Enums\TaskStatusEnum;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Log;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(3)
            ->has(Task::factory()->count(3))
            ->create();
    }
}
