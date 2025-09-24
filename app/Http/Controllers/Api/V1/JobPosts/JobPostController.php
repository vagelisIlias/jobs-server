<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\JobPosts;

use Throwable;
use App\Models\JobPost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function jobDescription(int $id): JobPostResource|JsonResponse
    {
        try {
            $job = JobPost::with('user')->findOrFail($id);
            return new JobPostResource($job);
        } catch (Throwable $e) {
            return $this->error(
                'Job post cannot be found',
                Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JobPostResource|JsonResponse
    {
        try {
            $job = JobPost::with('user')->findOrFail($id);
            $this->authorize('view', $job);
            return new JobPostResource($job);
        } catch (Throwable $e) {
            return $this->error(
                'Job post cannot be found',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostRequest $request, JobPostsSimilarityChecker $jobPostsSimilarityChecker): JsonResponse
    {
        try {
            if ($jobPostsSimilarityChecker->isSimilar()) {
                return $this->error(
                    'Similar job has already been created',
                    Response::HTTP_CONFLICT
                );
            }

            $jobPost = DB::transaction(function () use ($request) {
                return $request->storeJobPost()->load('user');
            });

            return $this->success(
                ['data' => new JobPostResource($jobPost)],
                'Job post created successfully',
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return $this->error(
                $e->getMessage(),
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostRequest $request, JobPost $jobPost): JsonResponse
    {
        try {
            $this->authorize('update', $jobPost);
            $jobPost = $request->updateJobPost($jobPost)->load('user');

            return $this->success([
                'jobPost' => new JobPostResource($jobPost),
            ], 'Job post updated successfully', Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return $this->error(
                'You are unauthorized for this action',
                Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            return $this->error(
                'Something went wrong',
                Response::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $job = JobPost::findOrFail($id);
            $this->authorize('delete', $job);
            $job->delete();

            return $this->success([],
                'Job post deleted successfully',
                Response::HTTP_OK
            );
        } catch (AuthorizationException $e) {
            return $this->error(
                'You are unauthorized for this action',
                Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            return $this->error(
                'Job post cannot be found',
                Response::HTTP_NOT_FOUND);
        }
    }
}
