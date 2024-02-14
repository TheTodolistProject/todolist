<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use App\Services\CreatingTokenService;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Unauthenticated_User_Cant_Logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/logout');
        $response->assertStatus(200);
    }

    public function test_Authenticated_User_Can_Logout(): void
    {
        $response = $this->postJson('/api/logout');
        $response->assertUnauthorized();
        $response->assertSeeText('Unauthenticated');
    }
}
