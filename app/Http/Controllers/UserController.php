<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserInformationResource;
use App\Models\User;
use App\Services\SearchUsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Log;
use OpenApi\Annotations as OA;
use Storage;
use Str;
use Symfony\Component\Uid\Ulid;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'Fuck u babe <3';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd('store user');
    }
    /**
     * @OA\Get(
     *     path="/api/user/{user}",
     *     tags={"User"},
     *     summary="Get a specific user by its ID",
     *     operationId="showUser",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="The slug of the user to retrieve",
     *         required=true,
     *         @OA\Schema(type="string" , example="missouri-robel-dietrich")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *         )
     *     )
     * )
     */
    public function show(User $user)
    {
        return new UserInformationResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/user/{user}",
     *     tags={"User"},
     *     summary="Update a specific user by its ID",
     *     operationId="updateUser",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="The slug of the user to update",
     *         required=true,
     *         @OA\Schema(type="string" , example="missouri-robel-dietrich")
     *     ),
     *     @OA\RequestBody(
     *         request="UpdateUserRequest",
     *         description="User update details",
     *         required=true,
     *         @OA\MediaType(
     *         mediaType="multipart/form-data",
     *           @OA\Schema(
     *             type="object",
     *             @OA\Property(property="_method",example="put"),
     *             @OA\Property(property="fname",example="John", description="fname field" , type="string"),
     *             @OA\Property(property="lname", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *              required={"fname", "lname", "email"}
     *         ),
     *     ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=304,
     *         description="User not updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User didn`t update :(")
     *         )
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $image = $request->file('image');
        $imageurl = $image->hashName();
        if ($image->storeAs('users' ,$imageurl)) {
            $user->updateOrFail([
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'email' => $request->input('email'),
                'image_url' => Storage::url('app/local/users/') . $imageurl,
            ]);
            return [response()->json(['message' => 'User updateddddddd !!!'] , 200) , UserInformationResource::make($user) ];
        }
        else{
            return response()->json(['message' => 'User didn`t update :('] , 304);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/user/{user}",
     *     tags={"User"},
     *     summary="Delete a specific user by its ID",
     *     operationId="deleteUser",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="The slug of the user to delete",
     *         required=true,
     *         @OA\Schema(type="string" , example="john-doe")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=304,
     *         description="User not deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not deleted!")
     *         )
     *     )
     * )
     */
    public function destroy(User $user)
    {

        if ($user->delete()){
            return response()->json(['message' =>'deleted'],200);
        }
        else{
            return response()->json(['message' =>'not deleted'],304);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/search-users",
     *     tags={"User"},
     *     summary="Search users by a text",
     *     operationId="searchUser",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="text",
     *         in="query",
     *         description="The text to search users",
     *         required=true,
     *         @OA\Schema(type="string" , example="john")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No users found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No users found!")
     *         )
     *     )
     * )
     */
    public function searchUser()
    {
        $text = \request()->query('text');
        $users = SearchUsersService::SearchUsers($text);
        return UserInformationResource::collection($users);
    }
}
