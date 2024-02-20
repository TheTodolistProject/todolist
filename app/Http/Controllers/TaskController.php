<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use App\Services\SearchTaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return TaskResource::collection($user->tasks()->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $user = auth()->user();
        $task = Task::create($request->all());
        $user->tasks()->save($task);
        return response()->json(['message' => 'task created!'] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return TaskResource::make($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->updateOrFail($request->all());
        return response()->json(['message' => 'task updated!'] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->deleteOrFail();
        return response()->json(['message' => 'task deleted!'] , 200);

    }

    public function searchTask($text)
    {
     $tasks = SearchTaskService::searchTasks($text);
      return TaskResource::collection($tasks);
    }

    public function assignUser(Task $task , Request $request)
    {
        $foundusers = collect();

        foreach ($request->users as $slug){
           $foundusers->push(User::where('slug' , $slug)->first()->id);
        }

        if ($foundusers->isEmpty()) {
            return response()->json(['message' => 'users not found'] , 404);
        }
        else{
            $done = $task->users()->syncWithoutDetaching($foundusers);
            if ($done){
                return response()->json(['message' => 'attached!'] , 200);
            }
            else{
                return response()->json(['message' => 'error attaching!'] ,500);
            }
        }
    }

    public function unassignUser(Task $task , User $user)
    {
        $done = $task->users()->detach($user);
        if ($done){
            return response()->json(['message' => 'detached!'] , 200);
        }
        else{
            return response()->json(['message' => 'error detaching!'] ,500);
        }
    }
}
