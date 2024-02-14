<?php

namespace Tests\Feature\Model;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_Task_Is_Created(): void
    {
        $task = Task::factory()->create();
        $this->assertModelExists($task);
    }

    public function test_Task_Is_SoftDeleted()
    {
        $task = Task::factory()->create();
        $task->delete();
        $this->assertSoftDeleted($task);
    }

    public function test_Task_Is_Deleted(): void
    {
        $task = Task::factory()->create();
        $task->forceDelete();
        $this->assertModelMissing($task);
    }

    public function test_Task_Is_Updated(): void
    {
        $task = Task::factory()->create();
        $task->update(['title'=>'kasldhaiuun']);
        $this->assertDatabaseHas('tasks',['title'=>'kasldhaiuun']);
    }

    public function test_Task_BelongsToMany_Users()
    {
        $task = Task::factory()->hasUsers(3)->create();
        $this->assertInstanceOf(Collection::class , $task->users()->get());
    }

    public function test_Task_BelongsTo_A_Project()
    {
        $task = Task::factory()->for(Project::factory())->create();
        $this->assertEquals(1 , $task->project()->count());
    }
}
