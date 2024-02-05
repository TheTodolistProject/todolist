<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
//      dd($request);
        dd($request->file('image'));

//        dd(Carbon::now()->addDay(env('API_EXPIRATION_DAY')));
//        $user = User::find($request->user_id);
//        Auth::login($user);
//        dump(auth()->user());
//        Auth::logout($user);
//        dd(auth()->user());
    }
}
