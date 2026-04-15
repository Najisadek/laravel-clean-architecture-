<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User Resource (JSON:API format)
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->resource->id(),
            'type' => 'users',
            'attributes' => [
                'name' => $this->resource->name(),
                'email' => (string) $this->resource->email(),
                'created_at' => $this->resource->createdAt()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function with($request): array
    {
        return [
            'meta' => [
                'token' => $this->additional['token'] ?? null,
            ],
        ];
    }
}
