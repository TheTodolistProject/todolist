<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $task = Task::first();
        dd($task->project()->count());
//        dd($request);
//        dd($request->file('image'));

//        dd(Carbon::now()->addDay(env('API_EXPIRATION_DAY')));
//        $user = User::find($request->user_id);
//        Auth::login($user);
//        dump(auth()->user());
//        Auth::logout($user);
//        dd(auth()->user());
    }
}
