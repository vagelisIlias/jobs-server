<?php

declare(strict_types=1);

namespace Tests\Feature\JobPosts;

use Exception;
use Tests\TestCase;
use App\Models\User;
use App\Models\JobPost;
use Illuminate\Support\Facades\Artisan;
use App\Services\JobPostsSimilarityChecker;
use App\Http\Requests\JobPostsRequest\StoreJobPostRequest;

final class JobPostControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_it_can_return_all_job_posts_successfully()
    {
        $user = User::factory()->create();
        JobPost::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/jobs');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'title',
                    'slug',
                    'description',
                    'requirements',
                    'location',
                    'department',
                    'employment_type',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function test_it_stores_a_new_job_post_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $payload = [
            'title' => 'Unique Title ' . uniqid(),
            'description' => 'Looking for backend developer',
            'requirements' => '3+ years Laravel',
            'location' => 'Berlin',
            'department' => 'Engineering',
        ];

        $response = $this->postJson('/api/v1/job/create', $payload);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Job post created successfully',
                ]);

        $this->assertDatabaseHas('job_posts', [
            'title' => $payload['title'],
            'user_id' => $user->id,
        ]);
    }


    public function test_it_fails_when_not_authenticated()
    {
        $response = $this->postJson('/api/v1/job/create', [
            'title' => 'Backend Developer',
            'description' => 'Looking for a skilled backend developer',
            'requirements' => '3+ years Laravel experience',
            'location' => 'New York',
            'department' => 'Engineering'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Unauthenticated.',
                ]);
    }

    public function test_it_can_not_store_a_new_job_post_if_similar_job_post_exists()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        JobPost::factory()->create([
            'user_id' => $user->id,
            'title' => 'Backend Developer',
            'location' => 'New York',
            'department' => 'Engineering'
        ]);

        $mockChecker = $this->mock(JobPostsSimilarityChecker::class);
        $mockChecker->shouldReceive('isSimilar')
                    ->once()
                    ->andReturn(true);

        $response = $this->postJson('/api/v1/job/create', [
            'title' => 'Backend Developers',
            'description' => 'Looking for backend developer',
            'requirements' => '3+ years Laravel',
            'location' => 'New York',
            'department' => 'Engineering'
        ]);

        $response->assertStatus(409)
                 ->assertJson([
                     'message' => 'Similar job has already been created, make sure all fields are different'
                 ]);
    }

    public function test_it_can_throw_a_throwable_exception(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        JobPost::factory()->create([
            'user_id' => $user->id,
            'title' => 'Backend Developer',
            'location' => 'New York',
            'department' => 'Engineering'
        ]);

        $this->mock(StoreJobPostRequest::class, function ($mock) {
            $mock->shouldReceive('store')->andThrow(new Exception('Test errors'));
        });

        $data = [
            'title' => 'Backend Developers',
            'description' => 'Looking for backend developer',
            'requirements' => '3+ years Laravel',
            'location' => 'New York',
            'department' => 'Engineering'
        ];

        $response = $this->postJson('api/v1/job/create', $data);
        $response->assertStatus(409);
    }
}
