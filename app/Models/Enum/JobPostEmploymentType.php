<?php

namespace App\Models\Enum;

enum JobPostEmploymentType: string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
    case Contract = 'contract';
}
