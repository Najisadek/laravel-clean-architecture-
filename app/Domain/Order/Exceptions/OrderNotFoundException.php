<?php

declare(strict_types=1);

namespace App\Domain\Order\Exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    public function __construct(string $message = 'Order not found')
    {
        parent::__construct($message, 404);
    }
}
