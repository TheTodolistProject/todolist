<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class Calendartask extends Controller
{/**
 * @OA\Get(
 *     path="/api/calendar-tasks",
 *     tags={"Tasks"},
 *     summary="Get tasks for a specific date",
 *     operationId="getTasks",
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="The date to retrieve tasks for",
 *         required=true,
 *         @OA\Schema(type="string", format="date" , example="2024-02-07")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No tasks found",
 *         @OA\JsonContent(
 *         )
 *     ),
 *     security={{"sanctum":{}}}
 * )
 */
    public function list(Request $request)
    {
        $from = Carbon::create($request->query('date'));
        $to = Carbon::create($from)->addDay();
        $tasks = Task::whereBetween('start_date', [$from, $to])->get();
        if ($tasks){
            return TaskResource::collection($tasks);
        }
        else{
            return null;
        }
    }
}
