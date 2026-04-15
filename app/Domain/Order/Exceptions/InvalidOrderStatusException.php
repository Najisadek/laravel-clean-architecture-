<?php

declare(strict_types=1);

namespace App\Domain\Order\Exceptions;

use Exception;

class InvalidOrderStatusException extends Exception
{
    public function __construct(string $message = 'Invalid order status')
    {
        parent::__construct($message, 422);
    }
}
