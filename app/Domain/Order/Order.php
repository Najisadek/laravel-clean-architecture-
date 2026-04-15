<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Order\Exceptions\InvalidOrderStatusException;
use App\Models\Order as OrderModel;

final class Order
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    private OrderModel $model;

    public function __construct(OrderModel $model)
    {
        $this->model = $model;
    }

    public static function create(string $userId, int $totalAmount, string $description): self
    {
        $model = OrderModel::create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'description' => $description,
            'status' => self::STATUS_PENDING,
        ]);

        return new self($model);
    }

    public static function fromModel(OrderModel $model): self
    {
        return new self($model);
    }

    public function id(): string
    {
        return $this->model->id;
    }

    public function userId(): string
    {
        return $this->model->user_id;
    }

    public function totalAmount(): int
    {
        return $this->model->total_amount;
    }

    public function description(): string
    {
        return $this->model->description;
    }

    public function status(): string
    {
        return $this->model->status;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->model->created_at;
    }

    public function canBeProcessed(): bool
    {
        return $this->model->status === self::STATUS_PENDING;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->model->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function canBeRefunded(): bool
    {
        return $this->model->status === self::STATUS_COMPLETED;
    }

    public function process(): void
    {
        if (! $this->canBeProcessed()) {
            throw new InvalidOrderStatusException(
                'Order cannot be processed. Current status: '.$this->model->status
            );
        }

        $this->model->status = self::STATUS_PROCESSING;
        $this->model->save();
    }

    public function complete(): void
    {
        if ($this->model->status !== self::STATUS_PROCESSING) {
            throw new InvalidOrderStatusException(
                'Order cannot be completed. Current status: '.$this->model->status
            );
        }

        $this->model->status = self::STATUS_COMPLETED;
        $this->model->save();
    }

    public function cancel(): void
    {
        if (! $this->canBeCancelled()) {
            throw new InvalidOrderStatusException(
                'Order cannot be cancelled. Current status: '.$this->model->status
            );
        }

        $this->model->status = self::STATUS_CANCELLED;
        $this->model->save();
    }

    public function refund(): void
    {
        if (! $this->canBeRefunded()) {
            throw new InvalidOrderStatusException(
                'Order cannot be refunded. Current status: '.$this->model->status
            );
        }

        $this->model->status = self::STATUS_REFUNDED;
        $this->model->save();
    }

    public function getModel(): OrderModel
    {
        return $this->model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'user_id' => $this->userId(),
            'total_amount' => $this->totalAmount(),
            'description' => $this->description(),
            'status' => $this->status(),
            'created_at' => $this->createdAt()->format('Y-m-d H:i:s'),
        ];
    }
}
