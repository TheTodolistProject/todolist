<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;

class CreatingTokenService
{
    public static function CreateToken(User $user)
    {
        $expiresAt = Carbon::now()->addDay(env('API_EXPIRATION_DAY'));
        $token = $user->createToken('auth-token' , ['*'],$expiresAt)->plainTextToken;
        return $token;
    }
}
