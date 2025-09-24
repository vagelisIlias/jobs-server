<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\JobPostStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enum\JobPostEmploymentType;
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
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'requirements', 'wage', 'location', 'department',
        'employment_type', 'experience_level', 'position', 'status'
    ];

    protected $attributes = [
        'employment_type' => JobPostEmploymentType::FullTime->value,
        'status' => JobPostStatus::Open->value,
    ];

    protected function casts(): array
    {
        return [
            'employment_type' => JobPostEmploymentType::class,
            'status' => JobPostStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
