<?php

declare(strict_types=1);

namespace App\Traits;

use Laravel\Sanctum\NewAccessToken;

trait CreatesAuthToken
{
    public function createAuthToken(string $name = 'auth_token'): NewAccessToken
    {
        return $this->createToken($name, [
            'user:read',
            'user:create',
            'user:delete',
            'user:update',
            'jobs:create',
            'jobs:read',
            'jobs:update',
            'jobs:delete',
            'applications:create',
            'applications:read',
            'applications:update',
            'applications:delete'
        ], now()->addDays(7));
    }
}
