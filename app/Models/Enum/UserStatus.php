<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
