<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum JobPostPosition: string
{
    case Office = 'office';
    case WorkFormHome = 'work-from-home';
    case Hybrid = 'hybrid';
    case Remote = 'remote';
    case Worldwide = 'worldwide';
}
