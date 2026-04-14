<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Stringable;

final class UserId implements Stringable
{
    private string $value;

    public function __construct(?string $value = null)
    {
        if ($value === null) {
            $this->value = (string) Str::uuid();
        } else {
            if (! Str::isUuid($value)) {
                throw new InvalidArgumentException('Invalid UUID format');
            }
            $this->value = $value;
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
