<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Order\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Order as DomainOrder;
use App\Models\Order as OrderModel;

final class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(string $id): ?DomainOrder
    {
        $model = OrderModel::find($id);

        return $model ? DomainOrder::fromModel($model) : null;
    }

    public function findByUserId(string $userId): array
    {
        $models = OrderModel::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return $models->map(fn ($model) => DomainOrder::fromModel($model))->all();
    }

    public function save(DomainOrder $order): void
    {
        $order->getModel()->save();
    }

    public function delete(string $id): void
    {
        OrderModel::where('id', $id)->delete();
    }
}
