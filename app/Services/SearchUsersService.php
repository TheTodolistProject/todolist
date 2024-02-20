<?php

namespace App\Services;

use App\Models\User;

class SearchUsersService
{
    public static function SearchUsers($text)
    {
        $users = User::where('fname' , 'like' , "%$text%")->orWhere('lname' , 'like' , "%$text%")->get();
        return $users;
    }

}
