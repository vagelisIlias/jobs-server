<?php

declare(strict_types=1);

namespace Tests\Feature\Tokens;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\Tokens\UserNotFoundException;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_generate_new_token()
    {
        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('commit')->never();

        $email = 'test@example.com';
        $password = '123';
        $token = 'Test Token';

        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $data = [
            'email' => $email,
            'password' => $password,
            'token_name' => $token,
        ];

        $response = $this->actingAs($user)->postJson('api/v1/token/generate', $data);
        $response->assertStatus(200);

    }

    public function test_it_can_not_find_the_user()
    {
        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('commit')->never();
        $this->withoutExceptionHandling();

        $data = [
            'email' => 'nonexistent@example.com',
            'password' => '123',
            'token_name' => 'Api Token',
        ];

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('No user found with this email');

        $this->postJson('/api/v1/token/generate', $data);
    }
}
