<?php

declare(strict_types=1);

namespace App\Application\Order\Actions;

use App\Application\Order\DTOs\CreateOrderDTO;
use App\Domain\Order\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Exceptions\OrderNotFoundException;
use App\Domain\Order\Order;

final class CreateOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function execute(CreateOrderDTO $dto): Order
    {
        return Order::create(
            userId: $dto->userId,
            totalAmount: $dto->totalAmount,
            description: $dto->description
        );
    }
}

final class ProcessOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function execute(string $orderId): Order
    {
        $order = $this->repository->findById($orderId);

        if ($order === null) {
            throw new OrderNotFoundException;
        }

        $order->process();
        $this->repository->save($order);

        return $order;
    }
}

final class CompleteOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function execute(string $orderId): Order
    {
        $order = $this->repository->findById($orderId);

        if ($order === null) {
            throw new OrderNotFoundException;
        }

        $order->complete();
        $this->repository->save($order);

        return $order;
    }
}

final class CancelOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function execute(string $orderId): Order
    {
        $order = $this->repository->findById($orderId);

        if ($order === null) {
            throw new OrderNotFoundException;
        }

        $order->cancel();
        $this->repository->save($order);

        return $order;
    }
}
