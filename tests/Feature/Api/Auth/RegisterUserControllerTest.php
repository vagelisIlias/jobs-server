<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use Exception;
use Tests\TestCase;
use App\Models\User;
use App\Mail\UserRegisteredEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\Api\UserRequest\StoreUserRequest;

class RegisterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_user()
    {
        $data = [
            "first_name" => 'test_first_name',
            "last_name" => 'test_lasts_name',
            "user_name" => 'test_user_name',
            "password" => '1234567',
            "password_confirmation" => '1234567',
            "email" => 'test@gmail.com',
            "email_verified_at" => '2025-09-03 22:46:45',
        ];

        $response = $this->postJson('api/v1/register', $data);
        $response->assertStatus(201);
    }

    public function test_it_can_generate_a_new_token_for_new_registered_user()
    {
        $token = 'Test Token';
        $user = User::factory()->create();

        $data = [
            "first_name" => 'test_first_name',
            "last_name" => 'test_lasts_name',
            "user_name" => 'test_user_name',
            "password" => '1234567',
            "password_confirmation" => '1234567',
            "slug" => 'test-user-name',
            "email" => 'test@gmail.com',
            "email_verified_at" => '2025-09-03 22:46:45',
            "role" => 'user',
            "status" => 'active',
            "created_at" => '2025-09-03 22:46:45',
            "updated_at" => '2025-09-04 22:46:45',
            'token' => $token,
        ];

        $response = $this->actingAs($user)->postJson('api/v1/register', $data);
        $response->assertStatus(201);
    }

    public function test_it_send_an_email_user_registered()
    {
        Mail::fake();

        $token = 'Test Token';
        $data = [
            "first_name" => 'test_first_name',
            "last_name" => 'test_lasts_name',
            "user_name" => 'test_user_name',
            "password" => '1234567',
            "password_confirmation" => '1234567',
            "slug" => 'test-user-name',
            "email" => 'test@gmail.com',
            "email_verified_at" => '2025-09-03 22:46:45',
            "role" => 'user',
            "status" => 'active',
            "created_at" => '2025-09-03 22:46:45',
            "updated_at" => '2025-09-04 22:46:45',
            'token' => $token,
        ];

        $response = $this->postJson('api/v1/register', $data);
        $response->assertStatus(201);

        Mail::assertSent(UserRegisteredEmail::class);
    }

    public function test_it_can_throw_a_throwable_exception(): void
    {
        $this->mock(StoreUserRequest::class, function ($mock) {
            $mock->shouldReceive('storeUser')->andThrow(new Exception('Test error'));
            $mock->shouldReceive('token_name')->andReturn('registration_token');
        });

         $data = [
            "first_name" => 'test_first_name',
            "last_name" => 'test_lasts_name',
            "user_name" => 'test_user_name',
            "password" => '1234567',
            "password_confirmation" => '1234567',
            "slug" => 'test-user-name',
            "email" => 'test@gmail.com',
            "email_verified_at" => '2025-09-03 22:46:45',
            "role" => 'user',
            "status" => 'active',
            "created_at" => '2025-09-03 22:46:45',
            "updated_at" => '2025-09-04 22:46:45',
        ];

        $response = $this->postJson('api/v1/register', $data);
        $response->assertStatus(500);
        $response->assertJson(['error' => 'Test error']);
    }
}
