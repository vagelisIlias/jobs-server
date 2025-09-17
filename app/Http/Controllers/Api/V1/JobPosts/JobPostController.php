<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\JobPosts;

use Throwable;
use App\Models\JobPost;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Api\V1\JobResource\JobPostResource;
use App\Http\Requests\Api\V1\JobPostsRequest\StoreJobPostRequest;
use App\Services\Api\V1\JobPostService\JobPostsSimilarityChecker;

class JobPostController extends Controller
{
    use AuthorizesRequests;
    use ApiResponseTrait;

    private const CREATE = 'Job post created successfully';
    private const UPDATE = 'Job post updated successfully';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JobPostResource::collection(JobPost::with('user')->paginate());
    }

    public function show(JobPost $job): JobPostResource
    {
        return new JobPostResource($job->load('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostRequest $storeJobPostRequest, JobPostsSimilarityChecker $jobPostsSimilarityChecker): JsonResponse
    {
        $this->authorize('store', );
        try {
            DB::beginTransaction();
            if ($jobPostsSimilarityChecker->isSimilar()) {
                return $this->error(
                    'Similar job has already been created, make sure all fields are different',
                    Response::HTTP_CONFLICT);
            }

            $jobPost = $storeJobPostRequest->storeJobPost();
            DB::commit();
        } catch (Throwable $e) {
             DB::rollBack();
            return $this->error(
                'Failed to create job post' . ': ' . $e->getMessage(),
                Response::HTTP_CONFLICT
            );
        }

        return $this->success([
            new JobPostResource($jobPost)
            ],'Job post updated successfully',
            Response::HTTP_OK
        ); 
    }

    // /**
    //  * Display the specified resource.
    //  */


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
