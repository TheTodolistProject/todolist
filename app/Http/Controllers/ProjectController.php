<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return ProjectResource::collection($user->projects()->get());
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
        return ProjectResource::make($project);
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
}
