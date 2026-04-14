<?php

declare(strict_types=1);

namespace App\Domain\User\Exceptions;

use Exception;

class EmailAlreadyExistsException extends Exception
{
    public function __construct(string $message = 'Email already exists')
    {
        parent::__construct($message, 409);
    }
}