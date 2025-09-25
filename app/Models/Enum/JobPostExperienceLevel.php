<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum JobPostExperienceLevel: string
{
    case Entry = 'entry';
    case Junior = 'junior';
    case JuniorMid = 'junior-mid';
    case Mid = 'mid';
    case MidSenior = 'mid-senior';
    case Senior = 'senior';
    case Lead = 'lead';
    case Mentor = 'mentor';
}
