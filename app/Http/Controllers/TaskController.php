<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use App\Services\SearchTaskService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/task",
     *      operationId="getUserTasks",
     *      tags={"Tasks"},
     *      summary="Get User Tasks",
     *      description="Get User Tasks",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
    public function index()
    {
        $user = auth()->user();
        return TaskResource::collection($user->tasks()->with(['users','project'])->get());
    }
    /**
     * @OA\Post(
     *      path="/api/task",
     *      operationId="storeUserTask",
     *      tags={"Tasks"},
     *      summary="Store a Task",
     *      description="Store a Task",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *         mediaType="multipart/form-data",
     *     @OA\Schema(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Daytask Project"),
     *             @OA\Property(property="detail", type="text", example="Lorem ipsum dolor sit amet, consectetur adipiscing elit,
     *             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
     *             Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
     *             Duis aute irure dolor datat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."),
     *             @OA\Property (property="start_date" , type="date" , example="2025-02-10 12:00:00"),
     *             required={"title" , "detail"}
     *         ),
     *     ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
    public function store(StoreTaskRequest $request)
    {
        $user = auth()->user();
        $task = Task::create($request->all());
       $saved =  $user->tasks()->save($task);
       if ($saved){
           return response()->json(['message' => 'task created!'] , 201);
       }
         else{
              return response()->json(['message' => 'error creating task!'] ,500);
         }
    }

    /**
     * @OA\Get(
     *      path="/api/task/{task}",
     *      operationId="showTask",
     *      tags={"Tasks"},
     *      summary="Show a Task",
     *      description="Show a Task",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *          name="task",
     *          description="A Task id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
    public function show(Task $task)
    {
        return TaskResource::make($task->load(['users' , 'project']));
    }

    /**
     * @OA\Post(
     *      path="/api/task/{task}",
     *      operationId="updateUserTask",
     *      tags={"Tasks"},
     *      summary="Update a Task",
     *      description="Update a Task",
     *     security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="task",
     *          description="A Task id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *         mediaType="multipart/form-data",
     *     @OA\Schema(
     *             type="object",
     *             @OA\Property(property="_method", type="string", default="put"),
     *             @OA\Property(property="title", type="string", example="Daytask Project edit"),
     *             @OA\Property(property="detail", type="text", example="Editting the detals"),
     *             @OA\Property (property="start_date" , type="date" , example="2026-02-10 12:00:00"),
     *             required={"title" , "detail" , "_method"}
     *         ),
     *     ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
       $updated = $task->updateOrFail($request->all());
        if ($updated){
            return response()->json(['message' => 'task updated!'] , 200);
        }
        else{
            return response()->json(['message' => 'error updating task!'] ,500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/task/{task}",
     *      operationId="deleteUserTask",
     *      tags={"Tasks"},
     *      summary="Delete a Task",
     *      description="Delete a Task",
     *     security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="task",
     *          description="A Task id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
    public function destroy(Task $task)
    {
        $deleted = $task->deleteOrFail();
        if ($deleted){
            return response()->json(['message' => 'task deleted!'] , 200);
        }
        else{
            return response()->json(['message' => 'error deleting task!'] ,500);
        }

    }
    /**
     * @OA\Get(
     *     path="/api/search-tasks",
     *     operationId="searchTask",
     *      tags={"Tasks"},
     *      summary="search a Task",
     *      description="Search a Task",
     *     security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="search",
     *          description="Something...",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     * )
     */
    public function searchTask(Request $request)
    {
     $tasks = SearchTaskService::searchTasks($request->query('search'));
      return TaskResource::collection($tasks);
    }
    /**
     * @OA\Post(
     *      path="/api/assign-user-to-task/{task}",
     *      operationId="assignUserToTask",
     *      tags={"Tasks"},
     *      summary="Assign a User To a Task",
     *      description="Assign a User To a Task",
     *     security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="task",
     *          description="A Task id",
     *          required=true,
     *          in="path",),
     *          @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="users[0]",
     *                      type="string",
     *                      example="mr-jillian-morissette-iv-reilly"
     *                  ),
     *                  @OA\Property(
     *                      property="users[1]",
     *                      type="string",
     *                      example="maximo-wolff-hickle"
     *                  ),
     *              ),
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
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
    /**
     * @OA\Delete(
     *      path="/api/unassign-user-from-task/{task}/{user}",
     *      operationId="unassignUserToTask",
     *      tags={"Tasks"},
     *      summary="Unassign a User To a Task",
     *      description="Unassign a User To a Task",
     *     security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="task",
     *          description="A Task id",
     *          required=true,
     *          in="path",
     *          example="2"),
     *     @OA\Parameter(
     *          name="user",
     *          description="A user slug",
     *          required=true,
     *          in="path",
     *          example="mr-jillian-morissette-iv-reilly"),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(),
     *      ),
     *     )
     */
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

    /**
     * @OA\Post(
     *     path="/api/create-global-task",
     *     tags={"Tasks"},
     *     summary="Create a global task",
     *     operationId="createGlobalTask",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *          request="StoreTaskRequest",
     *         description="Task creation details",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Global Task"),
     *                 @OA\Property(property="detail", type="text", example="This is a global task"),
     *                 @OA\Property(property="start_date", type="date", example="2025-02-10 12:00:00"),
     *                 required={"title", "detail"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task created!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error creating task",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error creating task!")
     *         )
     *     )
     * )
     */
    public function createGlobalTask(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        if ($task){
            return response()->json(['message' => 'task created!'] , 201);
        }
        else{
            return response()->json(['message' => 'error creating task!'] ,500);
        }

    }
}
