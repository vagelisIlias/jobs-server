<?php

declare(strict_types=1);

namespace App\Services\Api\V1\JobPostService;

use Illuminate\Support\Str;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\V1\JobPostsRequest\StoreJobPostRequest;

class JobPostsSimilarityChecker
{
    public function __construct(private StoreJobPostRequest $storeJobPostRequest)
    {
    }

    public function isSimilar(int $threshold = 85): bool
    {
        $userId = Auth::id();
        $similarJobs = JobPost::where('user_id', $userId)
            ->where('location', $this->storeJobPostRequest->location)
            ->where('department', $this->storeJobPostRequest->department)
            ->get();

        foreach ($similarJobs as $job) {
            if ($this->checkSimilarity($job->title, $this->storeJobPostRequest->title, $threshold)) {
                return true;
            }
        }

        return false;
    }

    private function checkSimilarity(string $text1, string $text2, int $threshold = 85): bool
    {
        similar_text(Str::lower($text1), Str::lower($text2), $percent);
        return $percent > $threshold;
    }
}
