<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return ProjectResource::collection($user->projects()->with(['tasks' , 'users'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $user = auth()->user();
        $project = Project::create($request->all());
        $user->projects()->attach($project);
        return response()->json(['message' => 'project created!'] , 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return ProjectResource::make($project->load(['tasks','users']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->updateOrFail($request->all());
        return response()->json(['message' => 'project updated'] , 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->deleteOrFail();
        return response()->json(['message' => 'project deleted.... :( '] , 200);
    }

    public function assignTask(Project $project , Request $request)
    {
        $foundtasks = collect();
        foreach ($request->tasks as $task){
            $foundtasks->push(Task::where('id' , $task)->get());
        }
        $collapsed = $foundtasks->collapse();

        if ($collapsed->isEmpty())
        {
            return response()->json(['message' => 'tasks not found! :('] , 404);
        }
        else
        {
            $done = $project->tasks()->saveMany($collapsed);
            if ($done){
                   return response()->json(['message' => 'tasks assigned!'] , 200);
            }
            else{
                return response()->json(['message' => 'something was wrong :('] ,500);
            }
        }
    }

    public function unassignTask(Project $project , Task $task)
    {
        $done = $task->project()->dissociate($project)->save();
        if ($done){
            return response()->json(['message' => 'detached!'] , 200);
        }
        else{
            return response()->json(['message' => 'error detaching!'] ,500);
        }
    }
}
