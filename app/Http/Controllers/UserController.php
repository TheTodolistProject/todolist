<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserInformationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserInformationResource($user);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()){
            return response()->json(['message' =>'deleted'],200);
        };
    }
}
