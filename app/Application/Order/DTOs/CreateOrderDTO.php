<?php

declare(strict_types=1);

namespace App\Application\Order\DTOs;

final class CreateOrderDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly int $totalAmount,
        public readonly string $description
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            totalAmount: (int) $data['total_amount'],
            description: $data['description']
        );
    }
}
