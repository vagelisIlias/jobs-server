<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\JobPostExperienceLevel;
use App\Models\Enum\JobPostStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enum\JobPostEmploymentType;
use App\Models\Enum\JobPostPosition;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPost extends Model
{
    /** @use HasFactory<\Database\Factories\JobPostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    protected $attributes = [
        'employment_type' => JobPostEmploymentType::FullTime->value,
        'experience_level' => JobPostExperienceLevel::Entry->value,
        'position' => JobPostPosition::Office->value,
        'status' => JobPostStatus::Open->value,
    ];

    // Reference for the graphQL to solve Enum to string
    public function getEmploymentTypeAttribute($value): string
    {
        return JobPostEmploymentType::from($value)->value;
    }

   // Reference for the graphQL to solve Enum to string
    public function getStatusAttribute($value): string
    {
        return JobPostStatus::from($value)->value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'employment_type' => JobPostEmploymentType::class,
            'experience_level' => JobPostExperienceLevel::class,
            'position' => JobPostPosition::class,
            'status' => JobPostStatus::class,
        ];
    }
}
