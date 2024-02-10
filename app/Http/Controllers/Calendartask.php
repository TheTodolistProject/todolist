<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Calendartask extends Controller
{
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
