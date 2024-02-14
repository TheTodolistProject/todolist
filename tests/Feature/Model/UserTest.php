<?php

namespace Tests\Feature\Model;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_User_Is_Created(): void
    {
        $user = User::factory()->create();
        $this->assertModelExists($user);
    }

    public function test_User_Is_Deleted(): void
    {
        $user = User::factory()->create();
        $user->delete();
        $this->assertModelMissing($user);
    }

    public function test_User_Is_Updated(): void
    {
        $user = User::factory()->create();
        $user->update(['fname'=>'kasldhaiuun']);
        $this->assertDatabaseHas('users',['fname'=>'kasldhaiuun']);
    }

    public function test_User_BelongsToMany_Projects()
    {
        $user = User::factory()->hasProjects(3)->create();
        $this->assertInstanceOf(Collection::class, $user->projects()->get());
    }

    public function test_User_BelongsToMany_Tasks()
    {
        $user = User::factory()->hasTasks(3)->create();
        $this->assertInstanceOf(Collection::class, $user->tasks()->get());
    }

}
