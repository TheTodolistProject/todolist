<?php

namespace Database\Seeders;

use App\Enums\TaskStatusEnum;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Log;
use function Laravel\Prompts\alert;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 users
        $employee = User::factory()->create(['email' => 'example@gmail.com','fname' => 'john','lname' => 'doe',]);
        $ali = User::factory()->create(['email' => 'sdyarash@gmail.com','fname' => 'chinese','lname' => 'beaver',]);
        $matin = User::factory()->create(['email' => 'matinnjt2000@gmail.com','fname' => 'matin','lname' => 'nejatbakhsh',]);

        //Assigning roles
        $ali->assignRole('super_admin');
        $matin->assignRole('manager');
        $employee->assignRole('employee');

        $users = User::all();
        // Create 3 projects
        $projects = Project::factory(10)->create();

        // For each project
        foreach ($projects as $project) {
            // Attach the users to the project
            $project->users()->attach($users);

            // Create 3 tasks for the project
            $tasks = Task::factory(3)->make();
            $project->tasks()->saveMany($tasks);

            // Attach the users to each task
            foreach ($tasks as $task) {
                $task->users()->attach($users);
            }
        }
    }

}
