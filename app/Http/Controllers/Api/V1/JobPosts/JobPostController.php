<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\JobPosts;

use Throwable;
use App\Models\JobPost;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Api\V1\JobResource\JobPostResource;
use App\Http\Requests\Api\V1\JobPostsRequest\StoreJobPostRequest;
use App\Services\Api\V1\JobPostService\JobPostsSimilarityChecker;
use App\Http\Requests\Api\V1\JobPostsRequest\UpdateJobPostRequest;

class JobPostController extends Controller
{
    use AuthorizesRequests;
    use ApiResponseTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JobPostResource::collection(JobPost::with('user')->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPost $job): JobPostResource
    {
        return new JobPostResource($job->load('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostRequest $request, JobPostsSimilarityChecker $jobPostsSimilarityChecker): JsonResponse
    {
        try {
            if ($jobPostsSimilarityChecker->isSimilar()) {
                return $this->error(
                    'Similar job has already been created, make sure all fields are not the same',
                    Response::HTTP_CONFLICT);
            }
            $jobPost = $request->storeJobPost()->load('user');
        } catch (Throwable $e) {
            if ($jobPost->id) {
                JobPost::where('id', $jobPost->id)->delete();
            }

            return $this->error($e->getMessage(),Response::HTTP_CONFLICT);
        }

        return $this->success([
            'data' => new JobPostResource($jobPost),
            ],'Job post created successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostRequest $request, JobPost $job): JsonResponse
    {
        try {
            $jobPost = $request->updateJobPost($job)->load('user');
        } catch (Throwable $e) {
            return $this->error($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->success([
            'jobPost' => new JobPostResource($jobPost)->toArray($request),
        ],'Job post updated successfully',
        Response::HTTP_OK,);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPost $job): JsonResponse
    {
        $job->delete();
        return $this->success([],'Job post deleted successfully',
        Response::HTTP_OK,);
    }
}
