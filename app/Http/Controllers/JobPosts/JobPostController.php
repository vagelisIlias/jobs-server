<?php

declare(strict_types=1);

namespace App\Http\Controllers\JobPosts;

use Throwable;
use App\Models\JobPost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\JobPostResource;
use App\ApiResponseTrait\ApiResponseTrait;
use App\Services\JobPostsSimilarityChecker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\JobPostsRequest\StoreJobPostRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostController extends Controller
{
    use ApiResponseTrait;

    private const SUCCESS = 'Job post created successfully';
    private const FAILED = 'Failed to create job post';
    private const WARNING = 'Similar job has already been created, make sure all fields are different';
    private const UNAUTHORIZED = 'Unauthorized user';

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        return JobPostResource::collection(JobPost::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostRequest $storeJobPostRequest, JobPostsSimilarityChecker $jobPostsSimilarityChecker): JsonResponse
    {
        DB::beginTransaction();
        try {

            if (!Auth::user()) {
                return $this->sendError(self::UNAUTHORIZED, [], Response::HTTP_UNAUTHORIZED);
            }

            if ($jobPostsSimilarityChecker->isSimilar()) {
                return $this->sendError(self::WARNING, [], Response::HTTP_CONFLICT);
            }

            $jobPost = $storeJobPostRequest->storeJobPost();
            DB::commit();
        } catch (Throwable $e) {
             DB::rollBack();
            return $this->sendError(
                self::FAILED . ': ' . $e->getMessage(),
                [],
                Response::HTTP_CONFLICT
            );
        }

        return $this->sendResponse(
            new JobPostResource($jobPost)->toArray($storeJobPostRequest),self::SUCCESS, Response::HTTP_CREATED);
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(JobPost $jobPost)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateJobPostRequest $request, JobPost $jobPost)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(JobPost $jobPost)
    // {
    //     //
    // }
}
