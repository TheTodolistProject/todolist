<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use function PHPUnit\Framework\assertJson;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Registered_User_Can_Login(): void
    {
        $user = User::first();
        $params = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $params);
        $response->assertOk();
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll(['message','token'])
        );
    }

    public function test_Unregistered_User_Cant_Login()
    {
        $params = [
            'email' => 'asdfnasdf@kjsdabiuvba.com',
            'password' => 'sdkkfovnrovm',
            ];

        $response = $this->postJson('/api/login', $params);
        $response->assertUnauthorized();

    }
}
