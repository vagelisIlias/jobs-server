<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\JobPost;
use App\Http\Requests\JobPostsRequest\StoreJobPostRequest;
use App\Services\JobPostsSimilarityChecker;
use Illuminate\Support\Facades\Artisan;

class JobPostsSimilarityCheckerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_it_detects_similar_job_posts(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        JobPost::factory()->create([
            'user_id'     => $user->id,
            'title'       => 'Backend Developer',
            'description' => 'Looking for a skilled dev',
            'requirements'=> '3+ years Laravel',
            'location'    => 'Berlin',
            'department'  => 'Engineering',
        ]);

        $request = new StoreJobPostRequest();
        $request->replace([
            'title'       => 'Backend Developers',
            'description' => 'Looking for a skilled dev',
            'requirements'=> '3+ years Laravel',
            'location'    => 'Berlin',
            'department'  => 'Engineering',
        ]);

        $checker = new JobPostsSimilarityChecker($request);

        $this->assertTrue($checker->isSimilar());
    }


    public function test_it_does_not_detect_non_similar_job_posts(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        JobPost::factory()->create([
            'user_id'    => $user->id,
            'title'      => 'Backend Developer',
            'location'   => 'Berlin',
            'department' => 'Engineering',
        ]);

        $request = new StoreJobPostRequest([], [], [], [], [], [
            'title'       => 'Frontend React Engineer',
            'description' => 'Frontend role',
            'requirements'=> 'React + TypeScript',
            'location'    => 'Berlin',
            'department'  => 'Engineering',
        ]);

        $checker = new JobPostsSimilarityChecker($request);
        $this->assertFalse($checker->isSimilar());
    }
}
