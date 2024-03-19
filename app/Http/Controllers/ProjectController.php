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
use Log;
use OpenApi\Annotations as OA;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return ProjectResource::collection($user->projects()->with(['tasks' , 'users'])->get());
    }

    /**
     * @OA\Post(
     *     path="/api/project",
     *     tags={"Projects"},
     *     summary="Create a new project",
     *     operationId="storeProject",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *     request="StoreProjectRequest",
     *         description="Project creation details",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="New Project"),
     *                 @OA\Property(property="detail", type="string", example="New Project Detail"),
     *                 @OA\Property(property="start_date", type="string",format="date", example="2025-08-01 00:00:00"),
     *                 required={"title", "detail" , "start_date"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project created!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error creating project",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error creating project!")
     *         )
     *     )
     * )
     */
    public function store(StoreProjectRequest $request)
    {
        $user = auth()->user();
        $project = Project::create($request->all());
        $user->projects()->attach($project);
        if ($user->projects->contains($project)){
            return response()->json(['message' => 'project created!'] , 201);
        }
        else{
            return response()->json(['message' => 'something was wrong :('] ,500);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/project/{project}",
     *     tags={"Projects"},
     *     summary="Get a specific project by its ID",
     *     operationId="showProject",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         description="The ID of the project to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer" , example="50")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project not found")
     *         )
     *     )
     * )
     */
    public function show(Project $project)
    {
        return ProjectResource::make($project->load(['tasks','users']));
    }

    /**
     * @OA\Put(
     *     path="/api/project/{project}",
     *     tags={"Projects"},
     *     summary="Update a specific project by its ID",
     *     operationId="updateProject",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         description="The ID of the project to update",
     *         required=true,
     *         @OA\Schema(type="integer" , example="20")
     *     ),
     *     @OA\RequestBody(
     *     request="UpdateProjectRequest",
     *         description="Project update details",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Updated Project"),
     *                 @OA\Property(property="detail", type="string", example="Updated Project Detail"),
     *                 @OA\Property(property="start_date", type="string",format="date", example="2025-08-01 00:00:00"),
     *                 required={"title", "detail"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project updated!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error updating project",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error updating project!")
     *         )
     *     )
     * )
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $updated = $project->updateOrFail($request->all());
        if ($updated){
            return response()->json(['message' => 'project updated'] , 201);
        }
        else{
            return response()->json(['message' => 'something was wrong :('] ,500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/project/{project}",
     *     tags={"Projects"},
     *     summary="Delete a specific project by its ID",
     *     operationId="deleteProject",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         description="The ID of the project to delete",
     *         required=true,
     *         @OA\Schema(type="integer" , example="20")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project deleted!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting project",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error deleting project!")
     *         )
     *     )
     * )
     */
    public function destroy(Project $project)
    {
        $deleted = $project->deleteOrFail();
        if ($deleted){
            return response()->json(['message' => 'project deleted.... :( '] , 200);
        }
        else{
            return response()->json(['message' => 'something was wrong :('] ,500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/assign-task-to-project/{project}",
     *     tags={"Projects"},
     *     summary="Assign tasks to a specific project by its ID",
     *     operationId="assignTask",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         description="The ID of the project to assign tasks",
     *         required=true,
     *         @OA\Schema(type="integer" , example="21")
     *     ),
     *     @OA\RequestBody(
     *         description="Task assignment details",
     *         required=true,
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="tasks",
     *                     type="object",
     *                     @OA\AdditionalProperties(type="integer"),
     *                     example={"0": "1", "1": "2", "3": "4"}
     *                 ),
     *                 required={"tasks"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tasks assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tasks assigned!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error assigning tasks",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error assigning tasks!")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/unassign-task-from-project/{project}/{task}",
     *     tags={"Projects"},
     *     summary="Unassign a task from a specific project by its ID",
     *     operationId="unassignTask",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         description="The ID of the project to unassign the task from",
     *         required=true,
     *         @OA\Schema(type="integer" , example="21")
     *     ),
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="The ID of the task to unassign from the project",
     *         required=true,
     *         @OA\Schema(type="integer" , example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task unassigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task unassigned!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error unassigning task",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error unassigning task!")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/ongoing-projects",
     *     tags={"Projects"},
     *     summary="Get all ongoing projects for the authenticated user",
     *     operationId="ongoingProjects",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving projects",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error retrieving projects!")
     *         )
     *     )
     * )
     */
    public function ongoingProjects()
    {
        $user = auth()->user();
        return ProjectResource::collection($user->projects()->with('users')->where('progress' , '<' , 100)->get());
    }

    /**
     * @OA\Get(
     *     path="/api/completed-projects",
     *     tags={"Projects"},
     *     summary="Get all completed projects for the authenticated user",
     *     operationId="completedProjects",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving projects",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error retrieving projects!")
     *         )
     *     )
     * )
     */
    public function completedProjects()
    {
        $user = auth()->user();
        return ProjectResource::collection($user->projects()->with('users')->whereProgress(100)->get());
    }
}
