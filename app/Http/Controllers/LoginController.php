<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\CreatingTokenService;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Str;

class LoginController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        if(!$user) {
            return response()->json(['message' => 'Failed to sign up!'], 500);
        }
        else{
           $token = CreatingTokenService::CreateToken($user);
            return response()->json(['message'=>'Successfully signed up!','token' => $token] , 201);
        }

    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email' , $request->input('email'))->first();
        if(!$user || !Hash::check($request->input('password') , $user->password))
        {
            return response()->json(['message' => 'Invalid credentials!'], 401);
        }
        else
        {
            $personalaccesstoken = $user->tokens()->first();

            if (!$personalaccesstoken){
                $token = CreatingTokenService::CreateToken($user);
                return response()->json(['message'=>'Successfully logged in!','token' => $token] , 200);

            }
            else{
                $personalaccesstoken->delete();
                $token = CreatingTokenService::CreateToken($user);
                return response()->json(['message'=>'Successfully logged in!','token' => $token] , 200);
            }

        }
    }

    public function logout()
    {
     Auth::user()->tokens()->delete();
     return response()->json('Bye 🤞🤞🤞' ,200);
    }
}
