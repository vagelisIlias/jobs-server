<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\JobPostEmploymentType;
use App\Models\Enum\JobPostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status' => JobPostStatus::Open->value,
    ];

    protected function casts(): array
    {
        return [
            'employment_type' => JobPostEmploymentType::class,
            'status' => JobPostStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
