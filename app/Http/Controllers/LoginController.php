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
use OpenApi\Annotations as OA;
use Str;

class LoginController extends Controller
{

/**
*
* @OA\Post(
*     path="/api/register",
*     tags={"Auth"},
*     summary="Register a new user",
 *     operationId="registerUser",
 *     @OA\RequestBody(
 *         description="User registration details",
 *         required=true,
 *         @OA\MediaType(
 *         mediaType="multipart/form-data",
 *           @OA\Schema(
 *             type="object",
 *             @OA\Property(property="fname",example="John", description="fname field" , type="string"),
 *             @OA\Property(property="lname", type="string", example="Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password" ,example="@Landatrip123"),
 *             required={"fname" , "lname" , "email" , "password"}
 *         ),
 *     ),
 *     ),
 *     @OA\Response(
 *         @OA\JsonContent(),
 *         response=201,
 *         description="User registered successfully",
 *         ),
 * )
 */
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
    /**
     * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Login user",
 *     operationId="loginUser",
 *     @OA\RequestBody(
 *        @OA\MediaType(
 *         mediaType="multipart/form-data",
 *     @OA\Schema(
 *             type="object",
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password" ,example=""),
 *     required={"email" , "password"}
 *         ),
 *     ),
 *     ),
 *     @OA\Response(
 *         @OA\JsonContent(),
 *         response=200,
 *         description="User logged in successfully",
 *         ),
 * )
 */
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

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout an authorized user",
     *     operationId="logout",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *     )
     * )
     * )
     */
    public function logout()
    {
     Auth::user()->tokens()->delete();
     return response()->json('Bye 🤞🤞🤞' ,200);
    }
}
