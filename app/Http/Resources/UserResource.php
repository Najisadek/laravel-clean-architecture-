<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'] ?? null,
            'name' => $this->resource['name'] ?? null,
            'email' => $this->resource['email'] ?? null,
            'created_at' => $this->resource['created_at'] ?? null,
            'token' => $this->resource['token'] ?? null,
        ];
    }
}
