<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum JobPostStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Draft = 'draft';
}
