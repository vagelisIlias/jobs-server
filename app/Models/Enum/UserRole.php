<?php

declare(strict_types=1);

namespace App\Models\Enum;

enum UserRole: string
{
    case Admin = 'admin';
    case User = 'user';
}
