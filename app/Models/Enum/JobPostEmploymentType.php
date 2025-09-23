<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum JobPostEmploymentType: string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
    case Contract = 'contract';
}
