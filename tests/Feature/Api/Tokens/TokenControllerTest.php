<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Tokens;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Mockery\MockInterface;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\Api\Tokens\UserNotFoundException;
use App\Exceptions\Api\Tokens\TokenNotGeneratedException;
use Mockery\LegacyMockInterface;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_generate_new_token()
    {
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

        $response = $this->actingAs($user)->postJson('api/token/generate', $data);
        $response->assertOk();
    }

    public function test_it_can_not_find_the_user()
    {
        $this->withoutExceptionHandling();

        $data = [
            'email' => 'nonexistent@example.com',
            'password' => '123',
            'token_name' => 'Api Token',
        ];

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('No user found with this email');

        $this->postJson('/api/token/generate', $data);
    }
}
