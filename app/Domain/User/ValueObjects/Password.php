<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final class Password
{
    private string $hashedValue;

    public function __construct(string $plainPassword, bool $isHashed = false)
    {
        if ($isHashed) {
            $this->hashedValue = $plainPassword;

            return;
        }

        $validator = Validator::make(
            ['password' => $plainPassword],
            ['password' => 'required|min:8']
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first('password'));
        }

        // Password is validated but not hashed yet - that's infrastructure concern
        $this->hashedValue = '';
    }

    public function setHashedValue(string $hashedValue): self
    {
        $this->hashedValue = $hashedValue;

        return $this;
    }

    public function hashedValue(): string
    {
        return $this->hashedValue;
    }

    public function isHashed(): bool
    {
        return ! empty($this->hashedValue);
    }
}
