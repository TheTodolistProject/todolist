<?php

namespace Tests\Feature\Model;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_Project_Is_Created(): void
    {
        $project = Project::factory()->create();
        $this->assertModelExists($project);
    }

    public function test_Project_Is_SoftDeleted()
    {
        $project = Project::factory()->create();
        $project->delete();
        $this->assertSoftDeleted($project);
    }

    public function test_Project_Is_Deleted(): void
    {
        $project = Project::factory()->create();
        $project->forceDelete();
        $this->assertModelMissing($project);
    }

    public function test_Project_Is_Updated(): void
    {
        $project = Project::factory()->create();
        $project->update(['title'=>'kasldhaiuun']);
        $this->assertDatabaseHas('projects',['title'=>'kasldhaiuun']);
    }

    public function test_Project_Has_Many_Tasks()
    {
        $project = Project::factory()->hasTasks(3)->create();
        $task_project_id = $project->tasks()->first()->project_id;
        $this->assertInstanceOf(Task::class ,$project->tasks()->first());
        $this->assertEquals($project->id , $task_project_id);
    }

    public function test_A_Project_BelongsToMany_Users()
    {
        $project = Project::factory()->hasUsers(3)->create();
        $this->assertInstanceOf(Collection::class , $project->users()->get());
    }

}
