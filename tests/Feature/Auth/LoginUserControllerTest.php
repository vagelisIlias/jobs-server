<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class LoginUserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_successfully_login_user()
    {
        User::factory()->create([
            "email" => 'test@gmail.com',
            'password' => Hash::make('1234567')
        ]);

        $data = [
            "email" => 'test@gmail.com',
            "password" => '1234567',
        ];

        $response = $this->postJson('api/v1/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'login_token', 'token_type', 'expires_at'
            ]
        ]);
    }

    public function test_it_can_create_a_new_login_token()
    {
        $token = 'Test Token';
        $user = User::factory()->create([
            "email" => 'test@gmail.com',
            "password" => Hash::make('1234567'),
        ]);

        $data = [
            "email" => 'test@gmail.com',
            "password" => '1234567',
            "token" => $token
        ];

        $response = $this->actingAs($user)->postJson('api/v1/login', $data);
        $response->assertStatus(200);
    }

    public function test_it_throws_validation_exception_when_email_is_incorrect()
    {
        User::factory()->create([
            'email' => 'nonexistingemail@gmail.com',
            'password' => Hash::make('1234567'),
        ]);

        $data = [
            'email' => 'test1@gmail.com',
            'password' => '1234567',
        ];

        $response = $this->postJson('api/v1/login', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_it_throws_unauthorized_exception_when_password_is_incorrect()
    {
        User::factory()->create([
            'email' => 'test1@gmail.com',
            'password' => Hash::make('correct-password'),
        ]);

        $data = [
            'email' => 'test1@gmail.com',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson('api/v1/login', $data);
        $response->assertStatus(401);
    }

    public function test_it_throws_exception_when_token_creation_is_null(): void
    {
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('1234567'),
        ]);

        $data = [
            'email' => 'test@gmail.com',
            'password' => '1234567',
            'token_name' => null,
        ];

        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(409);
    }
}
