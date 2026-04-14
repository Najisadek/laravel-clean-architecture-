<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        $validator = Validator::make(
            ['email' => $value],
            ['email' => 'required|email']
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid email format: '.$validator->errors()->first('email'));
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
