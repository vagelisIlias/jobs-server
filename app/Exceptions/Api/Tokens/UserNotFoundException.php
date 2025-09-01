<?php

declare(strict_types=1);

namespace App\Exceptions\Api\Tokens;

use Exception;
use Throwable;

class UserNotFoundException extends Exception
{
    public const MESSAGE = 'No user found with this email';
    public const CODE = 404;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
