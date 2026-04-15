<?php

declare(strict_types=1);

namespace App\Domain\Order\Contracts;

use App\Domain\Order\Order;

interface OrderRepositoryInterface
{
    public function findById(string $id): ?Order;

    public function findByUserId(string $userId): array;

    public function save(Order $order): void;

    public function delete(string $id): void;
}
