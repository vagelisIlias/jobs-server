<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\JobPosts;

use Throwable;
use App\Models\JobPost;
use App\Http\Controllers\Controller;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;
use App\Http\Resources\Api\V1\JobResource\JobPostResource;


class JobPostController extends Controller
{
    use ApiResponseTrait;

    private const CREATE = 'Job post created successfully';
    private const UPDATE = 'Job post updated successfully';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JobPostResource::collection(JobPost::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreJobPostRequest $storeJobPostRequest, JobPostsSimilarityChecker $jobPostsSimilarityChecker): JsonResponse
    // {
    //     if (!Auth::user()) {
    //         return $this->error('Unauthorized user', 401);
    //     }

    //     DB::beginTransaction();
    //     try {

    //         if ($jobPostsSimilarityChecker->isSimilar()) {
    //             return $this->error('Similar job has already been created, make sure all fields are different', 409);
    //         }

    //         $jobPost = $storeJobPostRequest->storeJobPost();
    //         DB::commit();
    //     } catch (Throwable $e) {
    //          DB::rollBack();
    //         return $this->error(
    //             'Failed to create job post' . ': ' . $e->getMessage(),
    //             409
    //         );
    //     }

    //     return $this->success('Job post update successfully', 200);

    // }

    // /**
    //  * Display the specified resource.
    //  */
    public function show(JobPost $job): JobPostResource
    {
        return new JobPostResource($job);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateJobPostRequest $request, JobPost $jobPost): JsonResponse
    // {
    //     // if (Auth::id() !== $jobPost->user_id) {
    //     //     return $this->sendError(self::UNAUTHORIZED, [], Response::HTTP_UNAUTHORIZED);
    //     // }

    //     $this->authorize('update', $jobPost);

    //     try {
    //         JobPost::create([
    //             'title' => $request->title,
    //             'description' => $request->description,
    //             'requirements' => $request->requirements,
    //             'location' => $request->location,
    //             'department' => $request->department,
    //         ]);
    //     } catch (Throwable $e) {

    //         return $this->sendError(
    //             self::FAILED . ': ' . $e->getMessage(),
    //             [],
    //             Response::HTTP_CONFLICT
    //         );
    //     }

    //     return $this->sendResponse(
    //         new JobPostResource($jobPost)->toArray($request),self::UPDATE, Response::HTTP_OK);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(JobPost $jobPost)
    // {
    //     //
    // }
}
