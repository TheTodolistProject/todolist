<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_User_Can_Register(): void
    {
        $params =  [
            'fname' => 'Sally',
            'lname' => 'SallyZadeh',
            'email' => 'Sally@gmail.com',
            'password' => '@Sally80'
        ];
        $response = $this->postJson('/api/register',$params);
        $existance = $this->assertDatabaseHas('users', [
            'fname' => $params['fname'],
            'lname' => $params['lname'],
            'email' => $params['email'],
        ]);
        if (!$existance) {
            $response->assertInternalServerError();
            $response->assertJson('message', 'Failed to sign up!');
        }
        else{
            $response->assertCreated();
            $this->assertDatabaseHas('personal_access_tokens', [
                'tokenable_id' => User::where('email', $params['email'])->first()->id,
                'tokenable_type' => User::class,
            ]);
            $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['message','token'])
            );
        }

    }
}
